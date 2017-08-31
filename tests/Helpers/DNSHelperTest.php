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
 * @link        https://github.com/pacoorozco/probind
 */

use App\Helpers\DNSHelper;

class DNSHelperTest extends TestCase
{
    public function testValidateRecordType()
    {
        // A VALID record type
        $this->assertTrue(DNSHelper::validateRecordType('CNAME'));

        // An INVALID record type
        $this->assertFalse(DNSHelper::validateRecordType('SPF'));
    }

}
