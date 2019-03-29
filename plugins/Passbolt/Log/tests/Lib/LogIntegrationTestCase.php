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
 * @since         2.8.0
 */
namespace Passbolt\Log\Test\Lib;

use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Model\Table\SecretsTable;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UserAction;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\ActionLog;
use Passbolt\Log\Model\Table\EntitiesHistoryTable;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTrait;
use Passbolt\Log\Test\Lib\Traits\EntitiesHistoryTrait;

abstract class LogIntegrationTestCase extends AppIntegrationTestCase
{
    use ActionLogsTrait;
    use EntitiesHistoryTrait;

    /** @var ResourcesTable */
    protected $Resources;

    /** @var PermissionsTable */
    protected $Permissions;

    /** @var SecretsTable */
    protected $Secrets;

    /** @var SecretAccesses */
    protected $SecretAccesses;

    /** @var EntitiesHistoryTable */
    protected $EntitiesHistory;

    /** @var ActionLog */
    protected $ActionLogs;

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.log.enabled', true);

        UserAction::destroy();

        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->SecretAccesses = TableRegistry::getTableLocator()->get('Passbolt/Log.SecretAccesses');
        $this->EntitiesHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $this->ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');

        // Make sure associations are loaded correctly, e.g. without depending on
        // ActionListeners -> model.Initialize, as the callback will not be fired twice
        // and controller actions can be called several times
        $this->Permissions->belongsTo('Passbolt/Log.PermissionsHistory', [
            'foreignKey' => 'foreign_key'
        ]);
        $this->Resources->belongsTo('Passbolt/Log.EntitiesHistory', [
            'foreignKey' => 'foreign_key'
        ]);
        $this->Secrets->belongsTo('Passbolt/Log.SecretsHistory', [
            'foreignKey' => 'foreign_key'
        ]);
        $this->Secrets->hasMany('Passbolt/Log.SecretAccesses');

        $this->SecretAccesses->belongsTo('Passbolt/Log.EntitiesHistory', [
            'foreignKey' => 'foreign_key'
        ]);
    }
}
