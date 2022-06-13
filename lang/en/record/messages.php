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

return [
    'already_exists' => 'ResourceRecord already exists!',
    'does_not_exist' => 'ResourceRecord does not exist.',

    'create' => [
        'error'   => 'ResourceRecord was not created, please try again.',
        'success' => 'ResourceRecord created successfully.',
    ],

    'update' => [
        'error'   => 'ResourceRecord was not updated, please try again',
        'success' => 'ResourceRecord updated successfully.',
    ],

    'delete'       => [
        'error'   => 'There was an issue deleting the record. Please try again.',
        'success' => 'The record was deleted successfully.',
    ],
    'records_help' => '
A
	Maps a host name to IPv4 address.
	Data is a IPv4 address in dotted decimal format.
	Multiple addresses are defined with the same name.

AAAA
	Maps a host name to IPv6 address.
	Data is a IPv6 address.
	Multiple addresses are defined with the same name.

CNAME
	Maps an alias to the real or canonical name.
	Data is a host name.
	Ends Data with a \'.\' if you supply Fully Qualified Domain Name (FQDN).
	CNAME records must be unique on a domain.

MX
	Specifies the name and relative preference of mail exchangers.
	Priority field is relative to other MX record for the same name.
	Data is a host name.
	Ends Data with a \'.\' if you supply FQDN.
	Any number of MX records may be defined.

NS
	Can\'t use \'@\' as a Name.
	Delegates a subdomain to a DNS server.
	Data is a host name.
	Ends Data with a \'.\' if you supply FQDN.

PTR
	Only used on IN-ADDR.ARPA. zones.
	Maps an IP address (IPv4 or IPv6) to a host name.
	Data is a host name. Must use an FQDN format (they end with a dot).

SRV
	Identifies the host(s) that will support a particular service.
	Name is defined as \'_service._proto\' FQDN format (see RFC 2782).
	Priority field is relative to other SRV record for the same service.
	Data field include weight, port and target information (see RFC 2782).

TXT
	 Associate some arbirary and unformatted text with a host or other name.',
];
