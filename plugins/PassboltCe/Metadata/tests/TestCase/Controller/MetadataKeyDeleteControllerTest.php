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

use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataKeyDeleteController
 */
class MetadataKeyDeleteControllerTest extends AppIntegrationTestCaseV5
{
    public function testMetadataKeyDeleteController_Success(): void
    {
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->expired()->persist();
        $this->assertFalse($key->isDeleted());
        $id = $key->get('id');
        $this->logInAsAdmin();
        $this->deleteJson('/metadata/keys/' . $id . '.json');
        $this->assertSuccess();
        $this->assertTrue(MetadataKeyFactory::count() === 1);
        $this->assertTrue(MetadataPrivateKeyFactory::count() === 0);

        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $updatedKey */
        $updatedKey = MetadataKeyFactory::get($id);
        $this->assertTrue($updatedKey->isDeleted());
    }

    public function testMetadataKeyDeleteController_Error_NotLoggedIn(): void
    {
        $id = UuidFactory::uuid();
        $this->deleteJson('/metadata/keys/' . $id . '.json');
        $this->assertError(401);
    }

    public function testMetadataKeyDeleteController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $id = UuidFactory::uuid();
        $this->deleteJson('/metadata/keys/' . $id . '.json');
        $this->assertError(403);
    }

    public function testMetadataKeyDeleteController_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $id = UuidFactory::uuid();
        $this->delete('/metadata/keys/' . $id);
        $this->assertResponseCode(404);
    }

    public function testMetadataKeyDeleteController_Error_EndpointDisabled(): void
    {
        $setting = Configure::read('passbolt.security.metadata.settings.editionDisabled');
        Configure::write('passbolt.security.metadata.settings.editionDisabled', true);

        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->expired()->persist();
        $id = $key->get('id');
        $this->logInAsAdmin();
        $this->deleteJson('/metadata/keys/' . $id . '.json');
        $this->assertResponseCode(403);

        Configure::write('passbolt.security.metadata.settings.editionDisabled', $setting);
    }
}
