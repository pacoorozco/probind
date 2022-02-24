<?php

namespace Tests\Unit\Presenters;

use App\Enums\ResourceRecordType;
use App\Models\ResourceRecord;
use Tests\TestCase;

class ResourceRecordPresenterTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideDataForResourceRecords()
     */
    public function it_formats_TXT_records_properly(string $recordName, string $recordData, string $want): void
    {
        /** @var ResourceRecord $record */
        $record = ResourceRecord::factory()->make([
            'name' => $recordName,
            'type' => ResourceRecordType::TXT,
            'data' => $recordData,
        ]);

        $this->assertEquals($want, $record->present()->asString());
    }

    public function provideDataForResourceRecords(): array
    {
        return [
            'TXT w/ short data' => [
                'name' => 'testRecord',
                'data' => 'testData',
                'want' => 'testrecord                               	IN	TXT	"testData"',
            ],
            'TXT w/ very long data' => [
                'name' => 'testRecord',
                'data' => '"v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApxJI+ldB/P7ocCsU4MjgC0iK+sIQ2g1Ft1RG3LuqquzaY8dmK+SsLVQi8uuo8t7DzAhsAGcHgHNOi189twbtQEz4R3KOLhESd3xGUYP0FTvyejDOaAeZzvjCzI6oj42Y0pNDRrmuOgAd61qJy46+smfKc+QrI4E1DGHnjrlXzrsBK73DMqX9JuD9oGRaXDghakGdAebBjNcRsZfjIv84DPmrHE9/nqacqUnpK8Z71jAEcnklPIfC6LNmrWPzG7+6fN+LbAAUSjvXGw0GpB6EkhRsrcSwilE+vPe+S42aE4dBCvAbLjcZgJIA/gVqnNlm8jfL8qshXpQjIUObfd+o4wIDAQAB"',
                'want' => 'testrecord                               	IN	TXT	"v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApxJI+ldB/P7ocCsU4MjgC0iK+sIQ2g1Ft1RG3LuqquzaY8dmK+SsLVQi8uuo8t7DzAhsAGcHgHNOi189twbtQEz4R3KOLhESd3xGUYP0FTvyejDOaAeZzvjCzI6oj42Y0pNDRrmuOgAd61qJy46+smfKc+QrI4E1DGHnjrlXzrsBK73DMqX9JuD9oGRaXDgha" "kGdAebBjNcRsZfjIv84DPmrHE9/nqacqUnpK8Z71jAEcnklPIfC6LNmrWPzG7+6fN+LbAAUSjvXGw0GpB6EkhRsrcSwilE+vPe+S42aE4dBCvAbLjcZgJIA/gVqnNlm8jfL8qshXpQjIUObfd+o4wIDAQAB"',
            ],
        ];
    }
}
