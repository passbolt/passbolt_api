<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.13.0
 */

use App\Command\CleanupCommand;
use Cake\Core\Configure;

Configure::load('Passbolt/Folders.config', 'default', true);

// Add cleanup tasks jobs.
if (PHP_SAPI === 'cli') {
    $cleanups = [
        'Permissions' => [
            'Hard Deleted Folders',
        ],
        'Passbolt/Folders.FoldersRelations' => [
            'Hard Deleted Users',
            'Soft Deleted Users',
            'Hard Deleted Resources',
            'Soft Deleted Resources',
            'Hard Deleted Folders',
            'Hard Deleted Folders Parents',
            'Missing Folders Folders Relations', // Ensure this cleanup is run before 'Missing Resources Folders Relations'
            'Missing Resources Folders Relations',
            'Duplicated Folders Relations',
            // @todo missing Hard Delete Permissions
        ],
    ];
    CleanupCommand::addCleanups($cleanups);
}
