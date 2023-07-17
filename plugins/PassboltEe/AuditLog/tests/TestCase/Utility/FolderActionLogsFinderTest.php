<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.8.0
 */

namespace Passbolt\AuditLog\Test\TestCase\Utility;

use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Passbolt\AuditLog\Utility\FolderActionLogsFinder;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class FolderActionLogsFinderTest extends LogIntegrationTestCase
{
    public function testFolderActionLogsFinder_Find()
    {
        $isFolderPluginDisabled = !$this->isFeaturePluginEnabled(FoldersPlugin::class);
        TableRegistry::getTableLocator()->clear();
        $this->enableFeaturePlugin(FoldersPlugin::class);
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl($user->role->name, $user->id);
        $folder = FolderFactory::make()->withPermissionsFor([$user])->persist();

        $finder = new FolderActionLogsFinder();
        $results = $finder->find($uac, $folder->id);
        $this->assertSame(0, $results->count());

        if ($isFolderPluginDisabled) {
            $this->disableFeaturePlugin(FoldersPlugin::class);
        }
    }
}
