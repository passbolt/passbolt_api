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
 * @since         4.11.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\MetadataKeys;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataKeyCreateController
 */
class MetadataKeyUpdateControllerTest extends AppIntegrationTestCaseV5
{
    use EmailQueueTrait;
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataKeyUpdateController_Success(): void
    {
        $key = MetadataKeyFactory::make()->patchData([
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
        ])->persist();

        $admin = $this->logInAsAdmin();
        $someOtherAmin = UserFactory::make()->admin()->persist();
        $this->putJson('/metadata/keys/' . $key->get('id') . '.json', [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => DateTime::yesterday()->setTimezone('Asia/Kolkata')->toIso8601String(), // e.g. 2025-01-29T13:45:06+00:00
        ]);

        $this->assertResponseCode(200);

        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains(
            'You have expired a metadata key.',
            $admin->username,
        );
        $this->assertEmailInBatchContains(
            [
                'Fingerprint: 67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                $admin->profile->first_name . ' has expired a metadata key',
            ],
            $someOtherAmin->get('username'),
        );
    }

    public function testMetadataKeyUpdateController_Error_FingerprintMismatch(): void
    {
        $key = MetadataKeyFactory::make()->patchData([
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAF',
        ])->persist();

        $this->logInAsAdmin();
        $this->putJson('/metadata/keys/' . $key->get('id') . '.json', [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAF',
            'expired' => DateTime::yesterday()->setTimezone('Asia/Kolkata')->toIso8601String(),
        ]);

        $this->assertResponseCode(400);
    }

    public function testMetadataKeyUpdateController_Error_KeyNotRevoked(): void
    {
        $key = MetadataKeyFactory::make()->patchData([
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
        ])->persist();

        $this->logInAsAdmin();
        $this->putJson('/metadata/keys/' . $key->get('id') . '.json', [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => DateTime::yesterday()->setTimezone('Asia/Kolkata')->toIso8601String(),
        ]);

        $this->assertResponseCode(400);
    }

    public function testMetadataKeyUpdateController_Error_NotLoggedIn(): void
    {
        $id = UuidFactory::uuid();
        $this->putJson('/metadata/keys/' . $id . '.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataKeyUpdateController_Error_NotAdmin(): void
    {
        $id = UuidFactory::uuid();
        $this->logInAsUser();
        $this->putJson('/metadata/keys/' . $id . '.json', []);
        $this->assertResponseCode(403);
    }

    public function testMetadataKeyUpdateController_Error_NotJson(): void
    {
        $id = UuidFactory::uuid();
        $this->logInAsAdmin();
        $this->put('/metadata/keys/' . $id, []);
        $this->assertResponseCode(404);
    }

    public function testMetadataKeyUpdateController_Error_EmptyData(): void
    {
        $id = UuidFactory::uuid();
        $this->logInAsAdmin();
        $this->putJson('/metadata/keys/' . $id . '.json', []);
        $this->assertError(400);
    }

    public function testMetadataKeyUpdateController_Error_NotUuid(): void
    {
        $this->logInAsAdmin();
        $this->putJson('/metadata/keys/nope.json', []);
        $this->assertError(400);
    }
}
