<?php

namespace App;

use Exception;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;


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
 * @version    Release: @version@
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
     * TXT
     *
     * @var array
     */
    private $types = array('SOA', 'A', 'AAAA', 'NS', 'MX', 'CNAME', 'PTR', 'TXT');
    /**
     * Contains all the records in this zone.
     *
     * An unindexed array of Resource Records (RR's) for this zone. Each item is a separate RR.
     * It's format should be pretty self explaining.
     * See manual for exact definition.
     *
     * @var array
     */
    private $records = array();
    /**
     * SOA Record of the loaded zone.
     *
     * This contains all the relevant data stored in the SOA (Start of Authority) record.
     * It's stored in an associative array, that should be pretty self-explaining.
     *
     * $_SOA = [
     *       'name' => 'example.com.',
     *       'ttl' => '345600',
     *       'class' => 'IN',
     *       'origin' => 'ns1.example.com.',
     *       'person' => 'hostmaster.example.com.',
     *       'serial' => '204041514',
     *       'refresh' => '14400',
     *       'retry' => '1800',
     *       'expire' => '86400',
     *       'negative_ttl' => '10800'
     *   ];
     *
     * @var array
     */
    private $SOA = array();

    public function getZoneData() : array
    {
        return [
            'domain'       => rtrim($this->SOA['name'], '.'),
            'serial'       => $this->SOA['serial'],
            'refresh'      => $this->SOA['refresh'],
            'retry'        => $this->SOA['retry'],
            'expire'       => $this->SOA['expire'],
            'negative_ttl' => $this->SOA['negative_ttl'],
            'default_ttl'  => $this->SOA['ttl'],
        ];
    }

    /**
     * Contains the domain name of the loaded zone.
     *
     * The domain name will automatically be appended to any and all records.
     * Unused if set to null.
     *
     * @var string
     */
    private $domain = null;

    /**
     * Checks if a value is an IP address or not.
     *
     * @param string $value Value to check.
     *
     * @return bool     true or false.
     */
    public static function isIP(string $value) : bool
    {
        // http://www.regular-expressions.info/regexbuddy/ipaccurate.html
        $ipaccurate = '/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}' .
            '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/';

        return preg_match($ipaccurate, $value);
    }

    /**
     * Get an array with the records of this zone file.
     *
     * Returns an unindexed array of Resource Records (RR's) for this zone. Each item is a separate RR.
     *
     * $records = $fileDNS->getRecords();
     *
     * @return array
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Loads the specified zone file.
     *
     * @param string $domain   domain name of this zone
     * @param string $zonefile filename of zonefile to load.
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function load(string $domain, string $zonefile) : bool
    {

        try {
            $zone = File::get($zonefile);
        } catch (Exception $e) {
            throw new FileNotFoundException('Unable to read file ' . $zonefile);
        }
        $ret = $this->setDomainName($domain);
        if (!$ret) {
            return $ret;
        }
        $parse = $this->parseZone($zone);

        return $parse;
    }

    /**
     * Sets the domain name of the currently loaded zone.
     *
     * @param string $domain the new domain name
     *
     * @return boolean
     * @throws Exception
     */
    private function setDomainName(string $domain) : bool
    {
        $valid = '/^[A-Za-z0-9\-\_\.]*$/';
        if (!preg_match($valid, $domain)) {
            throw new Exception('Unable to set domain name: ' . $domain);
        }
        $domain = rtrim($domain, '.');
        $this->domain = $domain;

        return true;
    }

    /**
     * Parses a zone file to object
     *
     * This function parses the zone file and saves the data collected from it to the _domain, _SOA and _records
     * variables.
     *
     * @param string $zone The zone file contents to parse.
     *
     * @return boolean
     */
    private function parseZone(string $zone) : bool
    {
        // RFC1033: A semicolon (';') starts a comment; the remainder of the line is ignored.
        $zone = preg_replace('/(;.*)$/m', '', $zone);

        // FIXME:
        // There has to be an easier way to do that, but for now it'll do.

        // RFC1033: Parenthesis ('(',')') are used to group data that crosses a line boundary.
        $zone = preg_replace_callback(
            '/(\([^()]*\))/',
            create_function(
                '$matches',
                'return str_replace("\\n", "", $matches[0]);'
            )
            , $zone);
        $zone = str_replace('(', '', $zone);
        $zone = str_replace(')', '', $zone);

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

        $originFQDN = $origin = $current = $this->domain . '.';
        $ttl = 86400; // RFC1537 advices this value as a default TTL.

        $zone = explode("\n", $zone);
        foreach ($zone as $line) {
            $line = rtrim($line);
            $line = preg_replace('/\s+/', ' ', $line);

            $record = array();
            if (!$line) {
                //Empty lines are stripped.
            } elseif (preg_match('/^\$TTL([^0-9]*)([0-9]+)/i',
                $line, $matches)) {
                //RFC 2308 define the $TTL keyword as default TTL from here.
                $ttl = intval($matches[2]);
            } elseif (preg_match('/^\$ORIGIN (.*\.)/', $line, $matches)) {
                //FQDN origin. Note the trailing dot(.)
                $origin = $originFQDN = trim($matches[1]);
            } elseif (preg_match('/^\$ORIGIN (.*)/', $line, $matches)) {
                //New origin. Append to current origin.
                $origin = trim($matches[1]) . '.' . $origin;
            } elseif (stristr($line, ' SOA ')) {
                if ($this->SOA) {
                    //SOA already set. Only one per zone is possible.
                    //Done parsing.
                    //A second SOA is added by programs such as dig,
                    //to indicate the end of a zone.
                    break;
                }
                $soa = $this->parseSOA($line, $origin, $ttl);
                if (!$soa) {
                    return false;
                }
                $soa = $this->setSOAValue($soa);
                if (!$soa) {
                    return false;
                }
            } else {
                $rr = $this->parseRR($line, $origin, $ttl, $current);
                if (!$rr) {
                    return false;
                }
                $current = $rr['name'];
                $this->records[] = $rr;
            }
        }

        return true;
    }

    /**
     * Parses a SOA (Start Of Authority) record line.
     *
     * This function returns the parsed SOA in array form.
     *
     * @param string $line   the SOA line to be parsed.
     *                       Should be stripped of comments and on 1 line.
     * @param string $origin the current origin of this SOA record
     * @param int    $ttl    the TTL of this record
     *
     * @return array array of SOA info .
     * @throws Exception
     */
    private function parseSOA(string $line, string $origin, int $ttl) : array
    {
        $soa = [];
        $regexp = '/(.*) SOA (\S*) (\S*) (\S*) (\S*) (\S*) (\S*) (\S*)/i';
        preg_match($regexp, $line, $matches);
        if (sizeof($matches) != 9) {
            throw new Exception('Unable to parse SOA.');
        }
        $pre = explode(' ', strtolower($matches[1]));
        if ($pre[0] == '@') {
            $soa['name'] = $origin;
        } else {
            $soa['name'] = $pre[0];
        }
        if (isset($pre[1])) {
            if (strtoupper($pre[1]) == 'IN') {
                $soa['ttl'] = $ttl;
                $soa['class'] = 'IN';
            } else {
                $soa['ttl'] = $this->parseToSeconds($pre[1]);
            }
            if (isset($pre[2])) {
                $soa['class'] = $pre[2];
            }
        } else {
            $soa['ttl'] = $ttl;
            $soa['class'] = 'IN';
        }
        $soa['origin'] = $matches[2];
        $soa['person'] = $matches[3];
        $soa['serial'] = $matches[4];
        $soa['refresh'] = $this->parseToSeconds($matches[5]);
        $soa['retry'] = $this->parseToSeconds($matches[6]);
        $soa['expire'] = $this->parseToSeconds($matches[7]);
        $soa['negative_ttl'] = $this->parseToSeconds($matches[8]);
        foreach (array_values($soa) as $item) {
            //Scan all items to see if any are a pear error.
            if (!$item) {
                return $item;
            }
        }

        return $soa;
    }

    /**
     * Converts a BIND-style timeout(1D, 2H, 15M) to seconds.
     *
     * @param string $time Time to convert.
     *
     * @return integer
     * @throws Exception
     */
    public static function parseToSeconds(string $time) : int
    {
        if (is_numeric($time)) {
            // Already a number. Return.
            return $time;
        }

        $pattern = '/([0-9]+)([a-zA-Z]+)/';
        $split = preg_split($pattern, $time, -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if (count($split) != 2) {
            throw new Exception('Unable to parse time. ' . $time);
        }
        list($num, $what) = $split;
        switch (strtoupper($what)) {
            case 'S':
                $times = 1; // Seconds
                break;
            case 'M':
                $times = 1 * 60; // Minute
                break;
            case 'H':
                $times = 1 * 60 * 60; // Hour
                break;
            case 'D':
                $times = 1 * 60 * 60 * 24; // Day
                break;
            case 'W':
                $times = 1 * 60 * 60 * 24 * 7; // Week
                break;
            default:
                throw new Exception('Unable to parse time. ' . $time);
                break;
        }
        $time = $num * $times;

        return $time;

    }

    /**
     * Sets a specific value in the SOA field.
     *
     * This function updates the list of SOA data we have.
     * List of accepted key => value pairs:
     * <pre>
     * Array
     *   (
     *       [name] => example.com.
     *       [ttl] => 345600
     *       [class] => IN
     *       [origin] => ns1.example.com.
     *       [person] => hostmaster.example.com.
     *       [serial] => 204041514
     *       [refresh] => 14400
     *       [retry] => 1800
     *       [expire] => 86400
     *       [negative_ttl] => 10800
     *   )
     * </pre>
     *
     * @param array $values A list of key -> value pairs
     *
     * @return boolean
     * @throws Exception
     */
    protected function setSOAValue(array $values) : bool
    {
        $soa = array();
        if (!is_array($values)) {
            throw new Exception('Unable to set SOA value.');
        }
        $validKeys = array(
            'name',
            'ttl',
            'class',
            'origin',
            'person',
            'serial',
            'refresh',
            'retry',
            'expire',
            'negative_ttl'
        );
        foreach ($values as $key => $value) {
            if (array_search($key, $validKeys) === false) {
                throw new Exception('Unable to set SOA value. ' . $key . ' not recognized');
            }

            switch (strtolower($key)) {
                case 'person':
                    $value = str_replace('@', '.', $value);
                    $value = trim($value, '.') . '.';
                case 'name':
                case 'origin':
                    $valid = '/^[A-Za-z0-9\-\_\.]*\.$/';
                    if (preg_match($valid, $value)) {
                        $soa[$key] = $value;
                    } else {
                        throw new Exception('Unable to set SOA value. ' . $key . ' not valid');
                    }
                    break;
                case 'class':
                    $soa[$key] = $value;
                    break;
                case 'ttl':
                case 'serial':
                case 'refresh':
                case 'retry':
                case 'expire':
                case 'negative_ttl':
                    if (is_numeric($value)) {
                        $soa[$key] = $value;
                    } else {
                        throw new Exception('Unable to set SOA value. ' . $key . ' not recognized');
                    }
                    break;
            }
        }
        // If all got parsed, save values.
        $this->SOA = array_merge($this->SOA, $soa);

        return true;
    }

    /**
     * Parses a (Resource Record) into an array
     *
     * @param string $line    the RR line to be parsed.
     * @param string $origin  the current origin of this record.
     * @param int    $ttl     the TTL of this record.
     * @param string $current the current domain name we're working on.
     *
     * @return array  array of RR info.
     * @throws Exception
     */
    private function parseRR(string $line, string $origin, int $ttl, string $current) : array
    {
        $record = array();
        $items = explode(' ', $line);
        $record['name'] = $items[0];
        $record['ttl'] = null;
        $record['class'] = null;
        $record['type'] = null;
        $record['data'] = null;
        if (!$record['name']) {
            // No name specified, inherit current name.
            $record['name'] = $current;
        }
        unset($items[0]);
        foreach ($items as $key => $item) {
            $item = trim($item);
            if (preg_match('/^[0-9]/', $item) &&
                is_null($record['ttl'])
            ) {
                // Only a TTL can start with a number.
                $record['ttl'] = $this->parseToSeconds($item);
            } elseif ((strtoupper($item) == 'IN') &&
                is_null($record['class'])
            ) {
                // This is the class definition.
                $record['class'] = 'IN';
            } elseif (array_search($item, $this->types) &&
                is_null($record['type'])
            ) {
                // We found our type!
                if (is_null($record['ttl'])) {
                    // TTL was left out. Use default.
                    $record['ttl'] = $ttl;
                }
                if (is_null($record['class'])) {
                    // Class was left out. Use default.
                    $record['class'] = 'IN';
                }
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

                    case 'MX':
                        // MX have an extra element. Save both right away.
                        // The setting itself is in the next item.
                        $record['data'] = $items[$key + 1];
                        $record['options'] = [
                            'preference' => $item
                        ];
                        break 2;

                    case 'TXT':
                        $record['data'] .= ' ' . $item;
                        break;

                    default:
                        throw new Exception('Unable to parse RR. ' . $record['type'] . ' not recognized');
                }
                //We're done parsing this RR now. Break out of the loop.
            } else {
                throw new Exception('Unable to parse RR. ' . $item . ' not recognized');
            }
        }
        foreach (array_values($record) as $item) {
            // Scan all items to see if any are a pear error.
            if (!$item) {
                return [];
            }
        }

        return $record;
    }

    /**
     * Returns a string with the zone file generated from this object.
     *
     * @param  string $separator The line ending separator. Defaults to \n
     *
     * @return string  The generated zone.
     */
    public function toString($separator = "\n")
    {
        $zone = $this->generateZone();
        if (!$zone) {
            return $zone;
        }
        $zone = implode($separator, $zone);

        return $zone;
    }

    /**
     * Converts seconds to BIND-style time(1D, 2H, 15M).
     *
     * @param  integer $time seconds to convert
     *
     * @return string String with time.
     * @throws Exception
     *
     */
    public static function parseFromSeconds(int $time) : string
    {
        $time = intval($time);
        if (!is_int($time)) {
            throw new Exception('Unable to parse time back. ' . $time);
        } elseif (is_int($num = ($time / (1 * 60 * 60 * 24 * 7)))) {
            return "$num" . 'W';
        } elseif (is_int($num = ($time / (1 * 60 * 60 * 24)))) {
            return "$num" . 'D';
        } elseif (is_int($num = ($time / (1 * 60 * 60)))) {
            return "$num" . 'H';
        } elseif (is_int($num = ($time / (1 * 60)))) {
            return "$num" . 'M';
        } elseif (is_int($num = ($time / (1)))) {
            return "$num";
        }
    }
}