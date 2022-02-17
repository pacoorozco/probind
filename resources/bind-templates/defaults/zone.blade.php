{{--
This `zone.blade.php` file is used to render the BIND zone file.

If you modify this template, it will be used to all zones, except if a more specific template exists.

See the README file to know how to modify this template and how to create more specific templates.
--}}

;
; This file has been automatically generated using ProBIND v3.

$ORIGIN {{ $zone->present()->domain }}
$TTL {{ $zone->present()->default_ttl }}

{{ $zone->present()->SOA() }}

; Name Servers of this zone.
@foreach($servers as $server)
{{ $server->present()->asString() }}
@endforeach

; Resource Records.
@foreach($records as $record)
{!! $record->present()->asString() !!}
@endforeach
