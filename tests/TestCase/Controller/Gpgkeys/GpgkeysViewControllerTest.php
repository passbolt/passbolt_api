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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Gpgkeys;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;

class GpgkeysViewControllerTest extends AppIntegrationTestCase
{
    public function testGpgkeysViewController_Success(): void
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();
        $gpgkey = $user->gpgkey;
        $gpgkeyId = $gpgkey->id;
        $this->logInAs($user);

        $this->getJson("/gpgkeys/{$gpgkeyId}.json");

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertGpgkeyAttributes($this->_responseJsonBody);
        $this->assertEquals($gpgkey->armored_key, $this->_responseJsonBody->armored_key);
        $this->assertEquals($gpgkey->key_id, $this->_responseJsonBody->key_id);
        $this->assertEquals($gpgkey->fingerprint, $this->_responseJsonBody->fingerprint);
    }

    public function testGpgkeysViewController_Error_NotAuthenticated(): void
    {
        $uuid = UuidFactory::uuid();
        $this->getJson('/gpgkeys/' . $uuid . '.json');
        $this->assertAuthenticationError();
    }

    public function testGpgkeysViewController_Error_InvalidId(): void
    {
        $this->logInAsUser();
        $this->getJson('/gpgkeys/notuuid.json');
        $this->assertError(400, 'The OpenPGP key identifier should be a valid UUID.');
    }

    public function testGpgkeysViewController_Error_NotFound(): void
    {
        $this->logInAsUser();
        $uuid = UuidFactory::uuid('gpgkey.id.notagpgkey');
        $this->getJson('/gpgkeys/' . $uuid . '.json');
        $this->assertError(404, 'The OpenPGP key does not exist.');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testGpgkeysViewController_Error_NotJson(): void
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->persist();
        $gpgkey = $user->gpgkey;
        $gpgkeyId = $gpgkey->id;
        $this->logInAs($user);

        $this->get("/gpgkeys/{$gpgkeyId}");
        $this->assertResponseCode(404);
    }
}
