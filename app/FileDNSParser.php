<?php

namespace App;

use App\Helpers\TimeHelper;
use Exception;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;


/**
 * An RFC1033 style zone file editor
 *
 * The File::DNS class provides an Object Oriented interface to read, edit and create DNS Zone files.
 *
 * @category   File
 * @package    FileDNS
 * @author     Paco Orozco <paco@pacoorozco.info>
 * @author     Cipriano Groenendal <cipri@php.net>
 * @copyright  2016 Paco Orozco <paco@pacoorozco.info>
 * @copyright  2004-2005 Cipriano Groenendal <cipri@php.net>
 * @license    http://www.php.net/license/3_0.txt PHP License 3.0
 * @link       http://pear.php.net/package/File_DNS
 * @link       http://www.rfc-editor.org/rfc/rfc1033.txt
 * @link       http://www.rfc-editor.org/rfc/rfc1537.txt
 * @link       http://www.rfc-editor.org/rfc/rfc2308.txt
 */
class FileDNSParser
{

    /**
     * Contains all supported Resource Records.
     *
     * This list contains all supported resource records.
     * This currently is:
     *
     * SOA
     * A
     * AAAA
     * NS
     * MX
     * CNAME
     * PTR
     * SRV
     * TXT
     *
     * @var array
     */
    private $types = array('SOA', 'A', 'AAAA', 'NS', 'MX', 'CNAME', 'PTR', 'SRV', 'TXT');
    /**
     * Contains all the records in this zone.
     *
     * An unindexed array of Resource Records (RR's) for this zone. Each item is a separate array representing a RR.
     *
     * Each RR item is an array:
     *
     * $record = [
     *  'name'      => 'sub.domain',
     *  'ttl'       => 7200,
     *  'class'     => 'IN',
     *  'type'      => 'MX',
     *  'data'      => '10.10.10.1',
     *  'options'   => [
     *          'preference'    => 10,
     *                  ],
     * ];
     *
     * @var array
     */
    private $records = array();
    /**
     * Zone data of the loaded zone.
     *
     * This contains all the relevant data stored in the SOA (Start of Authority) record.
     * It's stored in an associative array, that should be pretty self-explaining.
     *
     * $zoneData = [
     *       'domain' => 'example.com.',
     *       'mname' => 'ns1.example.com.',
     *       'rname' => 'hostmaster.example.com.',
     *       'serial' => '204041514',
     *       'refresh' => '14400',
     *       'retry' => '1800',
     *       'expire' => '86400',
     *       'negative_ttl' => '10800',
     *       'default_ttl' => '16400',
     *   ];
     *
     * @var array
     */
    private $zoneData = [
        'domain'       => null,
        'mname'        => null,
        'rname'        => null,
        'serial'       => null,
        'refresh'      => null,
        'retry'        => null,
        'expire'       => null,
        'negative_ttl' => null,
        'default_ttl'  => null,
    ];

    /**
     * FileDNSParser constructor.
     *
     * @param string $domain Domain name of this zone.
     */
    public function __construct(string $domain)
    {
        $this->zoneData['domain'] = $domain;
    }

    /**
     * Return an array with zone parsed data from file.
     *
     * $zone = [
     *  'domain'        => 'example.com',
     *  'serial'        => 2016091100,
     *  'refresh'       => 14400,
     *  'retry'         => 1800,
     *  'expire'        => 86400,
     *  'negative_ttl'  => 10800,
     *  'default_ttl'   => 16400,
     * ];
     *
     * @return array
     */
    public function getZoneData(): array
    {
        return Arr::only($this->zoneData, [
            'domain',
            'serial',
            'refresh',
            'retry',
            'expire',
            'negative_ttl',
            'default_ttl',
        ]);
    }

    /**
     * Return an array with the records parsed from file..
     *
     * Returns an unindexed array of Resource Records (RR's) for this zone.
     *
     * $records = $fileDNS->getRecords();
     *
     * @return array
     */
    public function getRecords(): array
    {
        $another = [];
        foreach ($this->records as $record) {
            $record['name'] = preg_replace('/\.' . $this->zoneData['domain'] . '\.$/', '', $record['name']);
            $record['name'] = preg_replace('/' . $this->zoneData['domain'] . '\.$/', '@', $record['name']);
            $another[] = $record;
        }
        return $another;
    }

    /**
     * Loads the specified zone file.
     *
     * @param string $zonefile filename of zonefile to load.
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function load(string $zonefile): bool
    {
        try {
            $zone = File::get($zonefile);
        } catch (Exception $e) {
            throw new FileNotFoundException('Unable to read file: ' . $zonefile);
        }

        // Parse zone file contents to create an array of RR.
        return $this->parseZone($zone);
    }

    /**
     * Parses a zone file to object
     *
     * This function parses the zone file and saves the data collected from it to the _domain, _SOA and _records
     * variables.
     *
     * @param string $fileContents The zone file contents to parse.
     *
     * @return boolean
     */
    private function parseZone(string $fileContents): bool
    {
        // Remove comments and unused data from contents.
        $fileContents = $this->prepareZoneContent($fileContents);

        /*
         * Origin is the current origin(@) that we're at now.
         * OriginFQDN is the FQDN origin, that gets appended to
         * non FQDN origins.
         *
         * FQDN == Fully Qualified Domain Name.
         *
         * Example:
         *
         *  $ORIGIN example.com.
         *  $ORIGIN sub1
         *  @ is sub1.example.com.
         *  $ORIGIN sub2
         *  @ is sub2.example.com.
         *  $ORIGIN new.sub3.example.com.
         *  @ is new.sub3.example.com.
         */
        $origin = $lastRecordName = $this->zoneData['domain'] . '.';
        $ttl = 86400; // RFC1537 advices this value as a default TTL.

        // We will parse file contents line by line.
        $fileContents = explode(PHP_EOL, $fileContents);
        foreach ($fileContents as $line) {
            // Remove end character and multiple spaces and tabs from line.
            $line = rtrim($line);
            $line = preg_replace('/\s+/', ' ', $line);

            if (!$line) {
                // Empty lines are stripped.
                continue;
            } elseif (preg_match('/^\$TTL([^0-9]*)([0-9]+)/i',
                $line, $matches)) {
                //RFC 2308 define the $TTL keyword as default TTL from here.
                $ttl = intval($matches[2]);
                $this->setZoneDataAttributeIfNotExist('default_ttl', $matches[2]);
            } elseif (preg_match('/^\$ORIGIN (.*\.)/', $line, $matches)) {
                //FQDN origin. Note the trailing dot(.)
                $origin = trim($matches[1]);
            } elseif (preg_match('/^\$ORIGIN (.*)/', $line, $matches)) {
                //New origin. Append to current origin.
                $origin = trim($matches[1]) . '.' . $origin;
            } elseif (stristr($line, ' SOA ')) {
                // Parse SOA line, if there is any error an Exception is thrown.
                $this->parseSOA($line);
            } else {
                $record = $this->parseRR($line, $origin, $ttl, $lastRecordName);
                if (empty($record)) {
                    return false;
                }
                $lastRecordName = $record['name'];
                $this->records[] = $record;
            }
        }

        return true;
    }

    /**
     * Remove comments and other unused data from Zone file contents.
     *
     * @param string $content
     *
     * @return string
     */
    private function prepareZoneContent(string $content): string
    {
        // RFC1033: A semicolon (';') starts a comment; the remainder of the line is ignored.
        $fileContents = preg_replace('/(;.*)$/m', '', $content);

        // RFC1033: Parenthesis '(' and ')' are used to group data that crosses a line boundary.
        $fileContents = preg_replace_callback(
            '/(\([^()]*\))/',
            function ($matches) {
                return str_replace(PHP_EOL, '', $matches[0]);
            },
            $fileContents
        );
        $fileContents = str_replace('(', '', $fileContents);
        return str_replace(')', '', $fileContents);
    }

    /**
     * Set an attribute in $this->zoneData, only if it has not been set before.
     *
     * @param string      $attribute    The attribute of $this->zoneData to be set.
     * @param string      $value        The value for this attribute.
     * @param string|null $validPattern A regexp to validate value. Default is null, to no validate.
     * @param bool        $force        This flags determine if value is set although it has been set before.
     *                                  Default is false, to no overwrite.
     *
     * @return bool
     * @throws Exception
     */
    private function setZoneDataAttributeIfNotExist(
        string $attribute,
        string $value,
        string $validPattern = null,
        bool $force = false
    ): bool
    {
        if (empty($this->zoneData[$attribute]) || $force) {
            // Check if $value is a correct one.
            if (!is_null($validPattern) && !preg_match($validPattern, $value)) {
                throw new Exception('Invalid value \'' . $value . '\'. Does not match with \'' . $validPattern . '\' pattern.');
            }

            // Set the attribute.
            $this->zoneData[$attribute] = $value;
        }
        return true;
    }

    /**
     * Parses a SOA (Start Of Authority) record line.
     *
     * This function parses SOA and set $this->zoneData. Throws Exception if there is any parser problem.
     *
     * @param string $line   the SOA line to be parsed.
     *                       Should be stripped of comments and on 1 line.
     *
     * @return bool
     * @throws Exception
     */
    private function parseSOA(string $line): bool
    {
        /*
         * $this->zoneData already set. Only one SOA per zone is possible. Done parsing.
         *
         * A second SOA is added by programs such as dig, to indicate the end of a zone.
         */
        if (!empty($this->zoneData['serial'])) {
            return true;
        }

        // Parse supplied line to find all SOA fields.
        $regexp = '/(.*) SOA (\S*) (\S*) (\S*) (\S*) (\S*) (\S*) (\S*)/i';
        preg_match($regexp, $line, $matches);
        if (sizeof($matches) != 9) {
            throw new Exception('Unable to parse SOA.');
        }
        try {
            /*
             * The first field, matches[1], could be '@' or a domain name 'example.com.', followed by a SOA TTL and
             * class (IN).
             * The second field, matches[2], is the 'mname' SOA field.
             * The third field, matches[3], is the 'rname' SOA field.
             * The fourth fielss, matches[4], is the 'serial' SOA field.
             * The next 4 fields, are the 'refresh', 'retry', 'expire' and 'negative_ttl' SOA fields.
             */
            $this->setZoneDataAttributesFromArray([
                'mname'        => $matches[2],
                'rname'        => $matches[3],
                'serial'       => $matches[4],
                'refresh'      => $matches[5],
                'retry'        => $matches[6],
                'expire'       => $matches[7],
                'negative_ttl' => $matches[8],
            ]);
        } catch (Exception $e) {
            throw new Exception('Unable to set SOA value.' . $e);
        }

        return true;
    }

    /**
     * Set $this->zoneData attributes from a array with its data.
     *
     * @param array $values The attribute values of $this->zoneData to be set.
     *
     * @return bool
     * @throws Exception
     */
    private function setZoneDataAttributesFromArray(array $values): bool
    {
        try {
            $this->setZoneDataAttributeIfNotExist('mname', $values['mname'], '/^[A-Za-z0-9\-\_\.]*\.$/');
            $this->setZoneDataAttributeIfNotExist('rname', $values['rname'], '/^[A-Za-z0-9\-\_\.]*\.$/');
            $this->setZoneDataAttributeIfNotExist('serial', $values['serial']);
            $this->setZoneDataAttributeIfNotExist('refresh', $values['refresh']);
            $this->setZoneDataAttributeIfNotExist('retry', $values['retry']);
            $this->setZoneDataAttributeIfNotExist('expire', $values['expire']);
            $this->setZoneDataAttributeIfNotExist('negative_ttl', $values['negative_ttl']);
        } catch (Exception $e) {
            throw new Exception('Unable to set SOA value.' . $e);
        }
        return true;
    }

    /**
     * Parses a (Resource Record) into an array
     *
     * @param string $line           the RR line to be parsed.
     * @param string $origin         the current origin of this record.
     * @param int    $ttl            the TTL of this record.
     * @param string $lastRecordName the current domain name we're working on.
     *
     * @return array  array of RR info.
     * @throws Exception
     */
    private
    function parseRR(
        string $line,
        string $origin,
        int $ttl,
        string $lastRecordName
    ): array
    {
        $items = explode(' ', $line);

        $record = [];
        $record['name'] = $items[0];
        $record['ttl'] = null;
        $record['class'] = null;
        $record['type'] = null;
        $record['data'] = null;

        /*
         * The first field, items[0], could be '' (inherit last record, 'ftp.example.com.' (FQDN) or 'ftp'.
         */
        if (empty($record['name'])) {
            // No name specified, inherit last parsed RR name.
            $record['name'] = $lastRecordName;
        }
        // If it's a FQDN, add the current origin.
        if (!preg_match('/(.*\.)/', $record['name'])) {
            $record['name'] .= '.' . $origin;
        }
        unset($items[0]);

        /*
         * The remaining fields could be:
         *      7200    IN  A   10.10.10.1
         *              IN  A   10.10.10.1
         *
         */
        foreach ($items as $key => $item) {
            $item = trim($item);
            if (preg_match('/^[0-9]/', $item) && is_null($record['ttl'])
            ) {
                // Only a TTL can start with a number.
                $record['ttl'] = TimeHelper::parseToSeconds($item);
            } elseif ((strtoupper($item) == 'IN') && is_null($record['class'])
            ) {
                // This is the class definition.
                $record['class'] = 'IN';
            } elseif (array_search($item, $this->types) && is_null($record['type'])
            ) {
                // We found our type!
                if (is_null($record['ttl'])) {
                    // TTL was left out. Use default.
                    $record['ttl'] = $ttl;
                }
                $record['class'] = 'IN';
                $record['type'] = $item;
            } elseif (!is_null($record['type'])) {
                // We found out what type we are. This must be the data field.
                switch (strtoupper($record['type'])) {
                    case 'A':
                    case 'AAAA':
                    case 'NS':
                    case 'CNAME':
                    case 'PTR':
                        $record['data'] = $item;
                        break 2;

                    case 'SRV':
                    case 'MX':
                        // MX have an extra element. Save both right away.
                        // The setting itself is in the next item.
                        $record['data'] = $items[$key + 1];
                        $record['options'] = [
                            'preference' => $item,
                        ];
                        break 2;

                    case 'TXT':
                        $record['data'] = (empty($record['data']))
                            ? $item
                            : implode(' ', [$record['data'], trim($item)]);
                        break;

                    default:
                        throw new Exception('Unable to parse RR. ' . $record['type'] . ' not recognized.');
                }
                //We're done parsing this RR now. Break out of the loop.
            } else {
                throw new Exception('Unable to parse RR. ' . $item . ' not recognized.');
            }
        }
        return $record;
    }


}
