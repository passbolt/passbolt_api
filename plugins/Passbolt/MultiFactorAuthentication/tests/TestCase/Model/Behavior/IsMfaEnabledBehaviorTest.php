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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Model\Behavior;

use App\Model\Entity\Role;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\PluginApplicationInterface;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Model\Behavior\IsMfaEnabledBehavior;
use Passbolt\MultiFactorAuthentication\Model\EntityMapper\User\MfaEntityMapper;
use Passbolt\MultiFactorAuthentication\Plugin;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;

/**
 * Class IsMfaEnabledBehaviorTest
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class IsMfaEnabledBehaviorTest extends TestCase
{
    use ModelAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadModel('Users');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->Users);
    }

    public function testIsMfaEnabledBehaviorTest_Behavior_Is_Loaded_In_MFA_Middleware()
    {
        $this->Users->hasBehavior(IsMfaEnabledBehavior::class);
        $MfaPlugin = new Plugin();
        $appSut = $this->createMock(PluginApplicationInterface::class);
        $appSut->method('getEventManager')->willReturn(EventManager::instance());
        $MfaPlugin->bootstrap($appSut);
        $this->assertTrue($this->Users->hasBehavior(IsMfaEnabledBehavior::class));
    }

    public function testIsMfaEnabledBehaviorTest_addIsMfaEnabledBehavior_triggered_on_user_find_index()
    {
        RoleFactory::make()->guest()->persist();
        UserFactory::make()
            ->user()
            ->with('AccountSettings', MfaAccountSettingFactory::make()->totp())
            ->persist();
        MfaOrganizationSettingFactory::make()->totp()->persist();

        // No MFA virtual field because the behavior is not loaded
        $user = $this->Users->findIndex(Role::USER)->first();
        $this->assertNull($user->get(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY));

        $this->Users->addBehavior(IsMfaEnabledBehavior::class);

        // MFA virtual field because the behavior is loaded
        $user = $this->Users->findIndex(Role::USER)->first();
        $this->assertIsBool($user->get(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY));
    }
}
