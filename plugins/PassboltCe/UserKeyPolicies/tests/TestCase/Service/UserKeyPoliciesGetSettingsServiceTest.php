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
 * @since         5.2.0
 */

namespace Passbolt\UserKeyPolicies\Test\TestCase\Service;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto;
use Passbolt\UserKeyPolicies\Service\UserKeyPoliciesGetSettingsService;
use Passbolt\UserKeyPolicies\UserKeyPoliciesPlugin;

/**
 * @covers \Passbolt\UserKeyPolicies\Service\UserKeyPoliciesGetSettingsService
 */
class UserKeyPoliciesGetSettingsServiceTest extends AppTestCase
{
    private ?UserKeyPoliciesGetSettingsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UserKeyPoliciesGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    /**
     * Default settings returned when nothing is set.
     *
     * @return void
     */
    public function testUserKeyPoliciesGetSettingsService_Default(): void
    {
        $result = $this->service->get();

        $this->assertInstanceOf(UserKeyPoliciesSettingsDto::class, $result);
        $expectedSettings = UserKeyPoliciesSettingsDto::createFromDefault()->toArray();
        $this->assertArrayEqualsCanonicalizing($expectedSettings, $result->toArray());
    }

    /**
     * RSA with an elliptic-curve setting (preferred_key_curve) should not be allowed and return fallback defaults.
     *
     * @return void
     */
    public function testUserKeyPoliciesGetSettingsService_RSAShouldNotHaveKeyCurve(): void
    {
        $rootConfigKey = UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY;
        Configure::write($rootConfigKey . '.' . 'preferred_key_type', 'RSA');
        Configure::write($rootConfigKey . '.' . 'preferred_key_size', UserKeyPoliciesSettingsDto::KEY_SIZE_4096);
        Configure::write($rootConfigKey . '.' . 'preferred_key_curve', UserKeyPoliciesSettingsDto::KEY_CURVE_ED25519_LEGACY);

        $result = $this->service->get();

        $expectedSettings = UserKeyPoliciesSettingsDto::createFromDefault()->toArray();
        $this->assertArrayEqualsCanonicalizing($expectedSettings, $result->toArray());
    }
}
