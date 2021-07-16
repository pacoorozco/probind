<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

use App\Enums\ActivityStatus;
use App\Enums\ControlRuleAction;
use App\Enums\ResourceRecordType;
use App\Enums\ServerType;

return [
    ServerType::class => [
        ServerType::Primary => 'Primary',
        ServerType::Secondary => 'Secondary',
    ],
    ResourceRecordType::class => [
        ResourceRecordType::A => 'A (IPv4 address)',
        ResourceRecordType::AAAA => 'AAAA (IPv6 address)',
        ResourceRecordType::CNAME => 'CNAME (canonical name)',
        ResourceRecordType::MX => 'MX (mail exchange)',
        ResourceRecordType::NAPTR => 'NAPTR (name authority pointer)',
        ResourceRecordType::NS => 'NS (name server)',
        ResourceRecordType::PTR => 'PTR (pointer)',
        ResourceRecordType::SRV => 'SRV (service locator)',
        ResourceRecordType::TXT => 'TXT (text)',
    ],
];
