<?php

use App\FileDNSParser;

class FileDNSParserUnitTest extends TestCase
{
    protected $fileContents = '
; This is a comment
$TTL 172800
@               IN      SOA     dns1.domain.com. hostmaster.domain.col. (
                                        2016080401      ; serial (aaaammddvv)
                                        86400           ; Refresh
                                        7200            ; Retry
                                        3628800         ; Expire
                                        7200 )  ; Minimum TTL
                                IN      NS      dns1.domain.com.
                                IN      NS      dns2.domain.com.
@                                               IN      MX      10 10.10.10.1
@                                       7200    IN      MX      20 10.10.10.2

ftp                                     7200    IN      A       10.10.10.3
www                                     7200    IN      CNAME   webserver.domain.com.
; webserver.domain.com.                   7200    IN      A       10.10.10.4

$ORIGIN subdomain
www1                                    7200    IN      A       10.10.10.10
                                                IN      A       10.10.10.11
text                                    7200    IN      TXT     "Somewhere over the rainbow"
';
    protected $expectedRecords = [

        [
            'name'  => '@',
            'ttl'   => 172800,
            'class' => 'IN',
            'type'  => 'NS',
            'data'  => 'dns1.domain.com.',
        ],
        [
            'name'  => '@',
            'ttl'   => 172800,
            'class' => 'IN',
            'type'  => 'NS',
            'data'  => 'dns2.domain.com.',
        ],
        [
            'name'    => '@',
            'ttl'     => 172800,
            'class'   => 'IN',
            'type'    => 'MX',
            'data'    => '10.10.10.1',
            'options' => [
                'preference' => '10',
            ],
        ],
        [
            'name'    => '@',
            'ttl'     => 7200,
            'class'   => 'IN',
            'type'    => 'MX',
            'data'    => '10.10.10.2',
            'options' => [
                'preference' => '20',
            ],
        ],
        [
            'name'  => 'ftp',
            'ttl'   => 7200,
            'class' => 'IN',
            'type'  => 'A',
            'data'  => '10.10.10.3',
        ],
        [
            'name'  => 'www',
            'ttl'   => 7200,
            'class' => 'IN',
            'type'  => 'CNAME',
            'data'  => 'webserver.domain.com.',
        ],
        [
            'name'  => 'www1.subdomain',
            'ttl'   => 7200,
            'class' => 'IN',
            'type'  => 'A',
            'data'  => '10.10.10.10',
        ],
        [
            'name'  => 'www1.subdomain',
            'ttl'   => 172800,
            'class' => 'IN',
            'type'  => 'A',
            'data'  => '10.10.10.11',
        ],
        [
            'name'  => 'text.subdomain',
            'ttl'   => 7200,
            'class' => 'IN',
            'type'  => 'TXT',
            'data'  => '"Somewhere over the rainbow"',
        ]
    ];

    /**
     * Test RFC 1033 file parser.
     */
    public function testLoad()
    {
        // Mock Filesystem with $this->fileContents.
        File::shouldReceive('get')
            ->with('zonefile')
            ->andReturn($this->fileContents);

        $fileDNS = new FileDNSParser();
        $fileDNS->load('domain.com', 'zonefile');

        $records = $fileDNS->getRecords();
        $expectedRecords = $this->expectedRecords;

        $this->assertEquals($expectedRecords, $records);
    }

    /**
     * Test static parseToSeconds() function.
     */
    public function testParseToSeconds()
    {
        $testTimeTranslations = [
            '7200'   => 7200,
            '10800S' => 10800 * 1,
            '15m'    => 15 * 60,
            '3W12h'  => 3 * 7 * 24 * 60 * 60 + 12 * 60 * 60,
        ];

        foreach (array_keys($testTimeTranslations) as $time) {
            $seconds = FileDNSParser::parseToSeconds($time);
            $this->assertEquals($testTimeTranslations[$time], $seconds);
        }
    }
}
