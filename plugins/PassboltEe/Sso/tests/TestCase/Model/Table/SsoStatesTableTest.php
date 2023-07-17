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
 * @since         3.11.0
 */
namespace Passbolt\Sso\Test\TestCase\Model\Table;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @see \Passbolt\Sso\Model\Table\SsoStatesTable
 */
class SsoStatesTableTest extends SsoTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Sso\Model\Table\SsoStatesTable
     */
    protected $SsoStates;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->SsoStates = $this->fetchTable('Passbolt/Sso.SsoStates');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->SsoStates);

        parent::tearDown();
    }

    public function testSsoStatesTableValidationDefault_Success(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $ssoState = $this->SsoStates->newEntity([
            'nonce' => SsoState::generate(),
            'state' => SsoState::generate(),
            'type' => SsoState::TYPE_SSO_SET_SETTINGS,
            'sso_settings_id' => $ssoSetting->id,
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Foo user agent',
        ]);

        $this->assertEmpty($ssoState->getErrors());
    }

    public function testSsoStatesTableValidationDefault_Success_WithUserIdOptional(): void
    {
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $ssoState = $this->SsoStates->newEntity(
            [
                'nonce' => SsoState::generate(),
                'state' => SsoState::generate(),
                'type' => SsoState::TYPE_SSO_RECOVER,
                'sso_settings_id' => $ssoSetting->id,
                'ip' => '127.0.0.1',
                'user_agent' => 'Foo user agent',
            ],
            [
                'accessibleFields' => [
                    'nonce' => true,
                    'state' => true,
                    'type' => true,
                    'sso_settings_id' => true,
                    'ip' => true,
                    'user_agent' => true,
                ],
            ],
        );

        $this->SsoStates->save($ssoState);

        $this->assertEmpty($ssoState->getErrors());
    }

    /**
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::validationDefault()
     */
    public function testSsoStatesTableValidationDefault_ErrorEmptyValue(): void
    {
        $ssoState = $this->SsoStates->newEntity([]);

        $errors = $ssoState->getErrors();
        $keys = ['nonce', 'state', 'type', 'ip', 'user_agent'];
        $this->assertNotEmpty($errors);
        $this->assertArrayHasAttributes($keys, $errors);
        foreach ($keys as $key) {
            $this->assertArrayHasAttributes(['_required'], $errors[$key]);
        }
    }

    /**
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::validationDefault()
     */
    public function testSsoStatesTableValidationDefault_ErrorInvalidValues(): void
    {
        $ssoState = $this->SsoStates->newEntity([
            'nonce' => '123456',
            'state' => '123456',
            'type' => 'not-valid',
            'sso_settings_id' => 1,
            'user_id' => 1,
            'ip' => 'foo',
            'user_agent' => 1,
        ]);

        $errors = $ssoState->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasAttributes(
            ['nonce', 'state', 'sso_settings_id', 'user_id', 'type', 'ip', 'user_agent'],
            $errors
        );
        $this->assertArrayHasAttributes(['isValidNonce'], $errors['nonce']);
        $this->assertArrayHasAttributes(['isValidState'], $errors['state']);
        $this->assertArrayHasAttributes(['isValidType'], $errors['type']);
        $this->assertArrayHasAttributes(['ascii', 'isValidUserAgent'], $errors['user_agent']);
        $this->assertArrayHasAttributes(['isValidIp'], $errors['ip']);
        $this->assertArrayHasAttributes(['uuid'], $errors['sso_settings_id']);
        $this->assertArrayHasAttributes(['uuid'], $errors['user_id']);
    }

    /**
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::validationDefault()
     */
    public function testSsoStatesTableValidationDefault_ErrorMinimumLength(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $ssoState = $this->SsoStates->newEntity([
            'nonce' => '123456',
            'state' => '123456',
            'type' => SsoState::TYPE_SSO_SET_SETTINGS,
            'sso_settings_id' => $ssoSetting->id,
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $errors = $ssoState->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertCount(2, $errors);
        $this->assertArrayHasAttributes(['nonce', 'state'], $errors);
        $this->assertArrayHasAttributes(['minLength'], $errors['state']);
        $this->assertArrayHasAttributes(['minLength'], $errors['nonce']);
    }

    /**
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::buildRules()
     */
    public function testSsoStatesTableBuildRules_ErrorExistsIn(): void
    {
        $ssoState = $this->SsoStates->newEntity(
            [
                'nonce' => SsoState::generate(),
                'state' => SsoState::generate(),
                'type' => SsoState::TYPE_SSO_SET_SETTINGS,
                'sso_settings_id' => UuidFactory::uuid(),
                'user_id' => UuidFactory::uuid(),
                'ip' => '127.0.0.1',
                'user_agent' => 'Foo user agent',
            ],
            [
                'accessibleFields' => [
                    'nonce' => true,
                    'state' => true,
                    'type' => true,
                    'sso_settings_id' => true,
                    'user_id' => true,
                    'ip' => true,
                    'user_agent' => true,
                ],
            ],
        );
        $this->SsoStates->save($ssoState);

        $errors = $ssoState->getErrors();
        $this->assertCount(2, $errors);
        $this->assertArrayHasAttributes(['_existsIn'], $errors['sso_settings_id']);
        $this->assertArrayHasAttributes(['_existsIn'], $errors['user_id']);
    }

    /**
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::buildRules()
     */
    public function testSsoStatesTableBuildRules_ErrorUnique(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $state = SsoState::generate();
        $nonce = SsoState::generate();
        $accessibleFields = [
            'accessibleFields' => [
                'nonce' => true,
                'state' => true,
                'type' => true,
                'sso_settings_id' => true,
                'user_id' => true,
                'ip' => true,
                'user_agent' => true,
            ],
        ];
        $this->SsoStates->save($this->SsoStates->newEntity([
            'nonce' => $state,
            'state' => $nonce,
            'type' => SsoState::TYPE_SSO_SET_SETTINGS,
            'sso_settings_id' => $ssoSetting->id,
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Foo user agent',
        ], $accessibleFields));

        // Create duplicate entry
        $ssoState = $this->SsoStates->newEntity([
            'nonce' => $state,
            'state' => $nonce,
            'type' => SsoState::TYPE_SSO_SET_SETTINGS,
            'sso_settings_id' => $ssoSetting->id,
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Foo user agent',
        ], $accessibleFields);
        $this->SsoStates->save($ssoState);

        $errors = $ssoState->getErrors();
        $this->assertCount(2, $errors);
        $this->assertArrayHasAttributes(['_isUnique'], $errors['nonce']);
        $this->assertArrayHasAttributes(['_isUnique'], $errors['state']);
    }

    /**
     * For certain types like sso_set_settings, sso_get_key, etc. user is mandatory
     *
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::buildRules()
     */
    public function testSsoStatesTableBuildRules_ErrorUserIdMandatory(): void
    {
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $state = SsoState::generate();
        $nonce = SsoState::generate();
        $accessibleFields = [
            'accessibleFields' => [
                'nonce' => true,
                'state' => true,
                'type' => true,
                'sso_settings_id' => true,
                'user_id' => true,
                'ip' => true,
                'user_agent' => true,
            ],
        ];

        $ssoState = $this->SsoStates->newEntity([
            // `user_id` is not set intentionally
            'nonce' => $state,
            'state' => $nonce,
            'type' => SsoState::TYPE_SSO_SET_SETTINGS,
            'sso_settings_id' => $ssoSetting->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Foo user agent',
        ], $accessibleFields);
        $this->SsoStates->save($ssoState);

        $errors = $ssoState->getErrors();
        $this->assertCount(1, $errors);
        $this->assertArrayHasAttributes(['user_is_soft_deleted'], $errors['user_id']);
    }

    /**
     * @uses \Passbolt\Sso\Model\Table\SsoStatesTable::findActive()
     */
    public function testSsoStatesTableFindActive(): void
    {
        // Active
        $ssoState = SsoStateFactory::make()->persist();
        $result = SsoStateFactory::find('active')->where(['state' => $ssoState->state])->first();
        $this->assertNotNull($result);
        $this->assertInstanceOf(SsoState::class, $result);

        // Deleted
        $ssoState = SsoStateFactory::make(['deleted' => Chronos::now()->subMinute(1)])->persist();
        $result = SsoStateFactory::find('active')->where(['state' => $ssoState->state])->first();
        $this->assertNull($result);
    }
}
