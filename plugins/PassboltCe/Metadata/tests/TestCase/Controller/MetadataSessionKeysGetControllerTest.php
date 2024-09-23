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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Service\OpenPGP\MessageValidationService;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataSessionKeysGetController
 */
class MetadataSessionKeysGetControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataSessionKeysGetController_Success_User(): void
    {
        $user = $this->logInAsUser();
        MetadataSessionKeyFactory::make(2)->withUser($user)->persist();

        $this->getJson('/metadata/session-keys.json');

        $this->assertSuccess();
        $results = $this->getResponseBodyAsArray();
        $this->assertCount(2, $results);
        $responseKeys = ['id', 'user_id', 'data', 'created', 'modified'];
        foreach ($results as $result) {
            $this->assertArrayHasAttributes($responseKeys, $result);
            $this->assertSame($user->get('id'), $result['user_id']);
            $this->assertTrue(MessageValidationService::isParsableArmoredMessage($result['data']));
        }
    }

    public function testMetadataSessionKeysGetController_Success_Admin(): void
    {
        $user = $this->logInAsAdmin();
        MetadataSessionKeyFactory::make(2)->withUser($user)->persist();

        $this->getJson('/metadata/session-keys.json');

        $this->assertSuccess();
        $results = $this->getResponseBodyAsArray();
        $this->assertCount(2, $results);
        $responseKeys = ['id', 'user_id', 'data', 'created', 'modified'];
        foreach ($results as $result) {
            $this->assertArrayHasAttributes($responseKeys, $result);
            $this->assertSame($user->get('id'), $result['user_id']);
            $this->assertTrue(MessageValidationService::isParsableArmoredMessage($result['data']));
        }
    }

    public function testMetadataSessionKeysGetController_Success_Empty(): void
    {
        $this->logInAsUser();
        $this->getJson('/metadata/session-keys.json');
        $this->assertSuccess();
        $this->assertEmpty($this->getResponseBodyAsArray());
    }

    public function testMetadataSessionKeysGetController_Error_AuthenticationRequired()
    {
        $this->getJson('/metadata/session-keys.json');
        $this->assertAuthenticationError();
    }

    public function testMetadataSessionKeyCreateController_Error_NotJson()
    {
        $this->logInAsUser();
        $this->post('/metadata/session-keys');
        $this->assertResponseCode(404);
    }
}
