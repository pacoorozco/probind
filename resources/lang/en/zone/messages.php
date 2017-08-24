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

return [
    'already_exists' => 'Zone already exists!',
    'does_not_exist' => 'Zone does not exist.',

    'create' => [
        'error'   => 'Zone was not created, please try again.',
        'success' => 'Zone created successfully.',
    ],

    'update' => [
        'error'   => 'Zone was not updated, please try again',
        'success' => 'Zone updated successfully.',
    ],

    'delete' => [
        'error'        => 'There was an issue deleting the zone. Please try again.',
        'success'      => 'The zone was deleted successfully.',
        'warning'      => 'This will delete this zone and all related information. Please be sure you want to do this. This action is
        irreversible.',
        'confirmation' => 'This will permanently delete the <strong>:domain</strong> zone and all associated records.',
    ],

    'activity' => [
        'created' => 'New zone <strong>:domain</strong> was created.',
        'updated' => 'Zone <strong>:domain</strong> was modified.',
        'deleted' => 'Zone <strong>:domain</strong> was deleted.'
    ]
];
