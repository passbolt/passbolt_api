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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Service;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Lib\AppTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Passbolt\Scim\Service\ScimGetSettingsService;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;

/**
 * ScimGetSettingsServiceTest class
 */
class ScimSetSettingsServiceTest extends AppTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    /**
     * @var \Passbolt\Scim\Service\ScimGetSettingsService
     */
    protected ScimGetSettingsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ScimGetSettingsService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testGetSettingsDecryptedValue()
    {
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $data = json_decode($gpg->decrypt($scimSettings->value), associative: true);
        $value = $this->service->getSettingsDecryptedValue();
        $this->assertSame([
            'setting_id',
            'scim_user_id',
            'secret_token',
        ], array_keys($value));
        $this->assertSame($data, $value);
    }

    public function testGetSettings()
    {
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $data = json_decode($gpg->decrypt($scimSettings->value), associative: true);
        $settings = $this->service->getSettings();
        $this->assertSame([
            'setting_id',
            'scim_user_id',
            'id',
            'base_api_endpoint',
            'created',
            'modified',
            'created_by',
            'modified_by',
        ], array_keys($settings));
        $this->assertSame($data['setting_id'], $settings['setting_id']);
        $this->assertSame($data['scim_user_id'], $settings['scim_user_id']);
        $this->assertStringContainsString('/scim/v2/' . $data['setting_id'], $settings['base_api_endpoint']);
    }
}
