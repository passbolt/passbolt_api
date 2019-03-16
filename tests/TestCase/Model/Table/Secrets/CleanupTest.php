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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CleanupTest extends AppTestCase
{

    public $Secrets;
    public $Groups;
    public $fixtures = [
        'app.Base/Users', 'app.Base/GroupsUsers', 'app.Base/Groups', 'app.Base/Permissions',
        'app.Base/Resources', 'app.Base/Secrets'
    ];
    public $options;

    use CleanupTrait;

    public function setUp()
    {
        parent::setUp();
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->options = ['accessibleFields' => [
           'resource_id' => true,
           'user_id' => true,
           'data' => true
        ]];
    }

    public function tearDown()
    {
        unset($this->Secrets);
        parent::tearDown();
    }

    public function testCleanupSecretsSoftDeletedResourcesSuccess()
    {
        $originalCount = $this->Secrets->find()->count();
        $sec = $this->Secrets->newEntity(self::getDummySecret([
            'resource_id' => UuidFactory::uuid('resource.id.jquery'),
            'user_id' => UuidFactory::uuid('user.id.ada')]), $this->options);
        $this->Secrets->save($sec, ['checkRules' => false]);
        $this->runCleanupChecks('Secrets', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupSecretsHardDeletedResourcesSuccess()
    {
        $originalCount = $this->Secrets->find()->count();
        $sec = $this->Secrets->newEntity(self::getDummySecret([
            'resource_id' => UuidFactory::uuid('resource.id.nope'),
            'user_id' => UuidFactory::uuid('user.id.ada')]), $this->options);
        $this->Secrets->save($sec, ['checkRules' => false]);
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedResources', $originalCount);
    }

    public function testCleanupSecretsSoftDeletedUsersSuccess()
    {
        $originalCount = $this->Secrets->find()->count();
        $sec = $this->Secrets->newEntity(self::getDummySecret([
            'resource_id' => UuidFactory::uuid('resource.id.jquery'),
            'user_id' => UuidFactory::uuid('user.id.sofia')]), $this->options);
        $this->Secrets->save($sec, ['checkRules' => false]);
        $this->runCleanupChecks('Secrets', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupSecretsHardDeletedUsersSuccess()
    {
        $originalCount = $this->Secrets->find()->count();
        $sec = $this->Secrets->newEntity(self::getDummySecret([
            'resource_id' => UuidFactory::uuid('resource.id.jquery'),
            'user_id' => UuidFactory::uuid('user.id.nope')]), $this->options);
        $this->Secrets->save($sec, ['checkRules' => false]);
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupSecretsHardDeletedPermissionsSuccess()
    {
        $originalCount = $this->Secrets->find()->count();
        $sec = $this->Secrets->newEntity(self::getDummySecret([
            'resource_id' => UuidFactory::uuid('resource.id.apache'),
            'user_id' => UuidFactory::uuid('user.id.frances')]), $this->options);
        $this->Secrets->save($sec, ['checkRules' => false]);
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedPermissions', $originalCount);
    }
}
