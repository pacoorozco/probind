;
; This file has been automatically generated using ProBIND v3 on {{ $date }}.

$ORIGIN {{ $zone->domain }}
$TTL {{ $zone->getDefaultTTL }}

{{ $zone->getSOARecord() }}

; Name Servers of this zone.
@foreach($servers as $server)
{{ $server->getNSRecord() }}
@endforeach

; Resource Records.
@foreach($records as $record)
{{ $record->formatResourceRecord() }}
@endforeach
