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
 * @since         5.16.0
 */
namespace Passbolt\OfflineModePolicies\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineModePolicies\OfflineModePoliciesPlugin;

/**
 * The payload Community refuses, accepted here through the real plugin binding.
 *
 * @uses \Passbolt\OfflineModePolicies\Form\OfflineSettingsUpdateForm
 */
class OfflineSettingsPostControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.edition', EditionDto::EDITION_PRO);
        EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_PRO)
            ->persist();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
        $this->enableFeaturePlugin(OfflineModePoliciesPlugin::class);
        $this->mockUserAgent();
        $this->mockUserIp();
    }

    public function testOfflineSettingsPostController_Success_NonDefaultValuesAcceptedOnPro(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 600,
            'data_retention_period' => 14,
            'max_items' => 500,
        ]);

        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();
        $this->assertSame(600, $body['max_session_duration']);
        $this->assertSame(14, $body['data_retention_period']);
        $this->assertSame(500, $body['max_items']);
    }

    public function testOfflineSettingsPostController_Error_OutOfRangeValuesRejectedOnPro(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 600,
            'data_retention_period' => 31,
            'max_items' => 500,
        ]);

        $this->assertBadRequestError('Could not validate offline settings data');
        $this->assertArrayHasKey('range', $this->getResponseBodyAsArray()['data_retention_period']);
    }
}
