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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Utility\UuidFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataSessionKeyDeleteController
 */
class MetadataSessionKeyDeleteControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataSessionKeyDeleteController_Success()
    {
        $adaSessionKey = MetadataSessionKeyFactory::make()->withUser()->persist();
        $makiSessionKey = MetadataSessionKeyFactory::make()->withMakiSessionKey()->persist();
        $this->logInAs($makiSessionKey->get('user'));

        $sessionKeyId = $makiSessionKey->get('id');
        $this->deleteJson("/metadata/session-keys/{$sessionKeyId}.json");

        $this->assertSuccess();
        // Assert entry is deleted in the database
        $metadataSessionKeys = MetadataSessionKeyFactory::find()->toArray();
        $this->assertCount(1, $metadataSessionKeys);
        $this->assertSame($adaSessionKey->get('id'), $metadataSessionKeys[0]->get('id'));
    }

    public function testMetadataSessionKeyDeleteController_Error_AuthenticationRequired()
    {
        $makiSessionKey = MetadataSessionKeyFactory::make()->withMakiSessionKey()->persist();
        $sessionKeyId = $makiSessionKey->get('id');
        $this->deleteJson("/metadata/session-keys/{$sessionKeyId}.json");
        $this->assertAuthenticationError();
    }

    public function testMetadataSessionKeyDeleteController_Error_NotJson()
    {
        $makiSessionKey = MetadataSessionKeyFactory::make()->withMakiSessionKey()->persist();
        $this->logInAs($makiSessionKey->get('user'));
        $sessionKeyId = $makiSessionKey->get('id');
        $this->delete("/metadata/session-keys/{$sessionKeyId}");
        $this->assertResponseCode(404);
    }

    public function testMetadataSessionKeyDeleteController_Error_SessionKeyNotFound()
    {
        $this->logInAsUser();
        $sessionKeyId = UuidFactory::uuid();
        $this->deleteJson("/metadata/session-keys/{$sessionKeyId}.json");
        $this->assertResponseCode(404);
        $this->assertResponseContains('The metadata session key does not exist or does not belong to this user');
    }

    public function testMetadataSessionKeyDeleteController_Error_SessionKeyBelongsToAnotherUser()
    {
        $user = UserFactory::make()->active()->persist();
        $sessionKeyId = MetadataSessionKeyFactory::make()->withUser($user)->persist()->get('id');

        $this->logInAsUser();
        $this->deleteJson("/metadata/session-keys/{$sessionKeyId}.json");

        $this->assertResponseCode(404);
        $this->assertResponseContains('The metadata session key does not exist or does not belong to this user');
    }
}
