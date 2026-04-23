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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Model\Table;

use App\Error\Exception\ValidationException;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @covers \Passbolt\Sso\Model\Table\SsoAuthenticationTokensTable
 */
class SsoAuthenticationTokensTableTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Model\Table\SsoAuthenticationTokensTable
     */
    protected $SsoAuthenticationTokens;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->SsoAuthenticationTokens = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoAuthenticationTokens');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SsoAuthenticationTokens);
        parent::tearDown();
    }

    /**
     * New entity success
     *
     * @return void
     */
    public function testSsoAuthenticationTokensTable_NewEntitySuccess(): void
    {
        $user = UserFactory::make()->user()->persist();
        $token = $this->SsoAuthenticationTokens->newEntity(
            [
                'user_id' => $user->id,
                'token' => UuidFactory::uuid(),
                'active' => true,
                'type' => SsoState::TYPE_SSO_GET_KEY,
                'data' => null,
            ],
            ['accessibleFields' => [
                'user_id' => true,
                'token' => true,
                'active' => true,
                'type' => true,
                'data' => true,
            ]]
        );

        $this->assertEmpty($token->getErrors());
    }

    /**
     * Generate success
     *
     * @return void
     */
    public function testSsoAuthenticationTokensTable_GenerateSuccess(): void
    {
        $user = UserFactory::make()->user()->persist();
        $token = null;
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests', 'sso_setting_id' => UuidFactory::uuid()];
        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_GET_KEY, $token, $data);
        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_SET_SETTINGS, $token, $data);

        $this->assertEquals(2, SsoAuthenticationTokenFactory::count());
    }

    /**
     * Check token can not be created for non-existing user
     */
    public function testSsoAuthenticationTokensTable_GenerateErrors_UserDoesNotExist(): void
    {
        $token = null;
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests', 'sso_setting_id' => UuidFactory::uuid()];
        $this->expectException(ValidationException::class);
        $this->SsoAuthenticationTokens->generate(UuidFactory::uuid(), SsoState::TYPE_SSO_GET_KEY, $token, $data);
    }

    /**
     * Check token can not be created for deleted user
     */
    public function testSsoAuthenticationTokensTable_GenerateErrors_UserDeleted(): void
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        $token = null;
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests', 'sso_setting_id' => UuidFactory::uuid()];
        $this->expectException(ValidationException::class);
        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_GET_KEY, $token, $data);
    }

    /**
     * Check token can not be created for inactive user
     */
    public function testSsoAuthenticationTokensTable_GenerateErrors_UserInactive(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $token = null;
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests', 'sso_setting_id' => UuidFactory::uuid()];
        $this->expectException(ValidationException::class);
        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_GET_KEY, $token, $data);
    }

    /**
     * Check token can not be created if setting id is invalid
     */
    public function testSsoAuthenticationTokensTable_GenerateErrors_SsoSettingInvalid(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $token = null;
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests', 'sso_setting_id' => 'nope'];
        $this->expectException(ValidationException::class);
        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_GET_KEY, $token, $data);
    }

    /**
     * Check token can not be created if setting id is invalid
     */
    public function testSsoAuthenticationTokensTable_GenerateErrors_SsoSettingMissing(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $token = null;
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests'];
        $this->expectException(ValidationException::class);
        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_GET_KEY, $token, $data);
    }

    public function testSsoAuthenticationTokensTable_GenerateSuccess_TypeSsoRecover(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $token = UuidFactory::uuid();
        $data = ['ip' => '127.0.0.1', 'user_agent' => 'cakephp tests', 'sso_setting_id' => UuidFactory::uuid()];

        $this->SsoAuthenticationTokens->generate($user->id, SsoState::TYPE_SSO_RECOVER, $token, $data);

        $this->assertEquals(1, SsoAuthenticationTokenFactory::count());
    }
}
