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
    'database' => [
        'title'          => 'Database Configuration',
        'header'         => 'Connection Settings',
        'sub-title'      => 'ProBIND stores all of its data in a database. This form gives the installation program the information needed to configure this database.',
        'dbname-label'   => 'Database Name',
        'dbname-help'    => 'The name of the database you want to run ProBIND in.',
        'username-label' => 'Username',
        'username-help'  => 'Your database username.',
        'password-label' => 'Password',
        'password-help'  => 'Your database password.',
        'host-label'     => 'Host Name',
        'host-help'      => 'The host name where database resides in.',
        'seed-label'     => 'Seed database with sample data.',
        'error-message'  => 'We cant connect to database with your settings. If your are not very sure to understand all these terms you should contact your administrator.',
    ],
];
