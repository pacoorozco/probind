;
; This file has been automatically generated using ProBIND v3 on {{ $date }}.

$ORIGIN {{ $zone->present()->domain }}
$TTL {{ $zone->present()->default_ttl }}

{{ $zone->present()->SOA() }}

; Name Servers of this zone.
@foreach($servers as $server)
{{ $server->getNSRecord() }}
@endforeach

; Resource Records.
@foreach($records as $record)
{!! $record->present()->asString() !!}
@endforeach
