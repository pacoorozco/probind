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

    'push_updates_warning' => 'You are about to copy modified data from this database to the actual DNS servers. The DNS servers are what makes this company and our customers accessible to users everywhere on the Internet. If you have screwed up the contents of the database, the end result may be the partial or complete disruption of Internet services for us and/or our customers. Real money may be lost, and real people may get real upset. By clicking on this button, you accept full legal, financial and moral responsibility for the consequences of your action.',
    'push_updates_nothing_to_do' => 'There is no zone with pending changes to be pushed.',
    'push_updates_no_servers' => 'There is no server with <strong>Push Updates</strong> capability. Please review servers configuration an enable one as a minimum.',
    'push_updates_success' => 'All pending changes has been pushed to servers.',

    'bulk_update_warning' => 'You are about to mark all domains in the database as having been updated. This means that the next time you push database updates to the DNS servers, it will take a very long time. This operation is only appropriate in a situation where one or more of the DNS servers is known to be out of synchronization with this database, or if there has been a change to the set of DNS servers which should appear in NS records. ',
    'bulk_update_success' => 'All zones has been marked with pending changes',

    'zonefile' => 'A RFC 1033 / BIND file containing Zone data',
    'import_zone_success' => 'Your file is being processed, data will be imported to :zone.',
    'import_button' => 'Import',
];
