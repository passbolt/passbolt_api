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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Webauthn;

use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class WebauthnSettingsGetControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnSettingsGetController_NotAuthenticated(): void
    {
        $this->getJson('/mfa/settings/webauthn.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSettingsGetController_NotJson_Returns404(): void
    {
        $this->logInAsUser();

        $this->get('/mfa/settings/webauthn');
        $this->assertResponseCode(404);
    }

    public function testWebauthnSettingsGetController_Success_ReturnsCeDefaults(): void
    {
        $this->logInAsUser();

        $this->getJson('/mfa/settings/webauthn.json?api-version=v2');
        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();

        $this->assertNull($body['authenticator_attachment']);
        $this->assertFalse($body['reject_backup_eligible']);
        $this->assertSame(MfaWebauthnPolicyInterface::RESIDENT_KEY_DISCOURAGED, $body['resident_key']);
        $this->assertSame(MfaWebauthnPolicyInterface::USER_VERIFICATION_PREFERRED, $body['user_verification']);
        $this->assertSame([], $body['hints']);
        $this->assertSame(60_000, $body['timeout_ms']);
        $this->assertSame(10, $body['max_credentials_per_user']);
        $this->assertSame(MfaWebauthnPolicyInterface::ATTESTATION_NONE, $body['attestation']);
        $this->assertSame([], $body['aaguid_allowlist']);
        $this->assertSame([], $body['aaguid_blocklist']);
    }
}
