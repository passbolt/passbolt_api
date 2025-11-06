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
 * @since         2.7.0
 */

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Model\Table\SecretsTable;
use App\Test\Factory\SecretFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FindByResourceUserTest extends AppTestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SecretsTable
     */
    public $Secrets;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Secrets') ? [] : ['className' => SecretsTable::class];
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets', $config);
    }

    public function testSecretTableFindByResourceUserTestExists()
    {
        $secret = SecretFactory::make()
            ->with('Users')
            ->with('Resources')
            ->persist();

        $userId = $secret->user_id;
        $resourceId = $secret->resource_id;
        $secrets = $this->Secrets->findByResourceUser($resourceId, $userId)->all();
        $this->assertCount(1, $secrets);
        $secret = $secrets->first();
        $this->assertSecretAttributes($secret);
        $this->assertEquals($resourceId, $secret->resource_id);
        $this->assertEquals($userId, $secret->user_id);
    }

    public function testSecretTableFindByResourceUser_deleted_should_be_ignored()
    {
        $secret = SecretFactory::make()
            ->with('Users')
            ->with('Resources')
            ->deleted()
            ->persist();

        $userId = $secret->user_id;
        $resourceId = $secret->resource_id;
        $secrets = $this->Secrets->findByResourceUser($resourceId, $userId)->all();
        $this->assertEmpty($secrets);
    }

    public function testSecretTableFindByResourceUserTestNotExist()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $secrets = $this->Secrets->findByResourceUser($resourceId, $userId)->all();
        $this->assertEmpty($secrets);
    }
}
