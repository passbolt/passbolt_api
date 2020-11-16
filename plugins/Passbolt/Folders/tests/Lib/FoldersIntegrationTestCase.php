<?php
declare(strict_types=1);

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
namespace Passbolt\Folders\Test\Lib;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;

abstract class FoldersIntegrationTestCase extends AppIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        Configure::write('passbolt.plugins.folders.enabled', true);

        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $permissionsTable->belongsTo('Passbolt/Log.PermissionsHistory', [
            'foreignKey' => 'foreign_key',
        ]);

        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $resourcesTable->belongsTo('Passbolt/Log.EntitiesHistory', [
            'foreignKey' => 'foreign_key',
        ]);

        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $resourcesTable->addBehavior(FolderizableBehavior::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        Configure::write('passbolt.plugins.folders.enabled', false);
    }
}
