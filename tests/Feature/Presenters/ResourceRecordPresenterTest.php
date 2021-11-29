<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Feature\Presenters;

use App\Models\ResourceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceRecordPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        setting()->set([
            'zone_default_mname' => 'dns1.domain.local',
            'zone_default_rname' => 'hostmaster@domain.local',
            'zone_default_refresh' => '86400',
            'zone_default_retry' => '7200',
            'zone_default_expire' => '3628800',
            'zone_default_negative_ttl' => '7200',
            'zone_default_default_ttl' => '172800',

            'ssh_default_user' => 'probinder',
            'ssh_default_key' => '-----BEGIN OPENSSH PRIVATE KEY-----',
            'ssh_default_port' => '22',
            'ssh_default_remote_path' => '/home/probinder/data',
        ]);
    }

    /**
     * @test
     * @dataProvider provideDataForFormattedTXT
     */
    public function it_returns_a_formatted_TXT(string $recordName, string $recordData, string $want)
    {
        $record = ResourceRecord::factory()->asTXTRecord()->make([
            'name' => $recordName,
            'data' => $recordData,
        ]);

        $formattedRecord = $record->present()->asString();

        $this->assertSame($want, $formattedRecord);
    }

    public function provideDataForFormattedTXT(): array
    {
        return [
            'basic text' => [
                'name' => 'test',
                'data' => 'myData',
                'want' => 'test                                     	IN	TXT	"myData"',
            ],
            'quoted text' => [
                'name' => 'test',
                'data' => '"This is some quoted text". It\'s a nice piece of text.',
                'expected' => 'test                                     	IN	TXT	"\"This is some quoted text\". It\'s a nice piece of text."',
            ],
            'text with spaces' => [
                'name' => 'test',
                'data' => 'my data contains spaces',
                'expected' => 'test                                     	IN	TXT	"my data contains spaces"',
            ],
            'very long text' => [
                'name' => 'test',
                'data' => 'v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApxJI+ldB/P7ocCsU4MjgC0iK+sIQ2g1Ft1RG3LuqquzaY8dmK+SsLVQi8uuo8t7DzAhsAGcHgHNOi189twbtQEz4R3KOLhESd3xGUYP0FTvyejDOaAeZzvjCzI6oj42Y0pNDRrmuOgAd61qJy46+smfKc+QrI4E1DGHnjrlXzrsBK73DMqX9JuD9oGRaXDghakGdAebBjNcRsZfjIv84DPmrHE9/nqacqUnpK8Z71jAEcnklPIfC6LNmrWPzG7+6fN+LbAAUSjvXGw0GpB6EkhRsrcSwilE+vPe+S42aE4dBCvAbLjcZgJIA/gVqnNlm8jfL8qshXpQjIUObfd+o4wIDAQAB',
                'expected' => 'test                                     	IN	TXT	"v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApxJI+ldB/P7ocCsU4MjgC0iK+sIQ2g1Ft1RG3LuqquzaY8dmK+SsLVQi8uuo8t7DzAhsAGcHgHNOi189twbtQEz4R3KOLhESd3xGUYP0FTvyejDOaAeZzvjCzI6oj42Y0pNDRrmuOgAd61qJy46+smfKc+QrI4E1DGHnjrlXzrsBK73DMqX9JuD9oGRaXDgha" "kGdAebBjNcRsZfjIv84DPmrHE9/nqacqUnpK8Z71jAEcnklPIfC6LNmrWPzG7+6fN+LbAAUSjvXGw0GpB6EkhRsrcSwilE+vPe+S42aE4dBCvAbLjcZgJIA/gVqnNlm8jfL8qshXpQjIUObfd+o4wIDAQAB"',
            ],
        ];
    }
}
