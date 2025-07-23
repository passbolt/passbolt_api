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
 * @since         3.6.0
 */

namespace Passbolt\Scim\Test\Utility;

use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Scim\Test\Factory\ScimOrgSettingFactory;

/**
 * BaseIntegrationTest class
 */
abstract class BaseIntegrationTest extends AppIntegrationTestCase
{
    use ScimTestRequestBodyDataTrait;
    use ScimTestUsersTrait;

    /**
     * Placeholder for setting id value to replace in expected SCIM responses
     */
    public const PLACEHOLDER_SETTING_ID = 'PLACEHOLDER_SETTING_ID';

    /**
     * Path to fixture files for SCIM responses
     */
    public const FIXTURE_SCIM_PATH = PLUGINS . 'PassboltEe' . DS . 'Scim' . DS . 'tests' . DS . 'Fixture' . DS . 'Scim' . DS;

    /**
     * Setting ID for the SCIM endpoint
     *
     * @var string
     */
    protected string $settingId = '';

    /**
     * Scim user id for the SCIM operations logs
     *
     * @var string|null
     */
    protected ?string $scimUserId = '';

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('Scim');

        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->admin()->persist();
        $scimOrgSetting = ScimOrgSettingFactory::make()->default()->persist();
        $settingsData = json_decode($scimOrgSetting->value, associative: true);
        $this->settingId = $settingsData['setting_id'] ?? '';
        $this->scimUserId = $settingsData['scim_user_id'] ?? '';
    }

    /**
     * @param string $action
     * @return string
     */
    protected function getScimEndpoint(string $action): string
    {
        return '/scim/v2/' . $this->settingId . '/' . $action;
    }

    /**
     * @param string $text
     * @return string
     */
    protected function replaceSettingIdString(string $text): string
    {
        return str_replace(self::PLACEHOLDER_SETTING_ID, $this->settingId, $text);
    }

    /**
     * Return the content of a scim fixture response, replacing the setting id placeholder if needed
     * Note: trim is done to remove possible end of file break line in the fixture file
     *
     * @param string $filename
     * @return string
     */
    protected function getScimFixtureData(string $filename): string
    {
        return trim($this->replaceSettingIdString(file_get_contents(self::FIXTURE_SCIM_PATH . $filename)));
    }

    protected function configScimAuth(): void
    {
        $this->configRequest([
            'headers' => [
                'Authorization' => 'Bearer ' . ScimOrgSettingFactory::SCIM_TEST_SECRET_TOKEN,
            ],
        ]);
    }
}
