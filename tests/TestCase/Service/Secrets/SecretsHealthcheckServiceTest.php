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

namespace App\Test\TestCase\Service\Secrets;

use App\Service\Secrets\SecretsHealthcheckService;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\Healthchecks\Healthcheck;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SecretsHealthcheckService::class)]
class SecretsHealthcheckServiceTest extends AppTestCase
{
    public function testSecretsHealthcheckService_Success(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $secret = SecretFactory::make([
            'user_id' => $ada->id,
            'data' => $this->encryptMessageFor($ada->id, 'secret'),
        ])->withSecretRevision()->persist();

        $result = (new SecretsHealthcheckService())->check();

        $check = $result[SecretsHealthcheckService::CATEGORY][SecretsHealthcheckService::CHECK_ENCRYPTED_FOR_RECIPIENT];
        $expectedDetails = [
            [
                'status' => Healthcheck::STATUS_SUCCESS,
                'message' => "Secret {$secret->id} is encrypted for its recipient.",
            ],
        ];
        $this->assertSame($expectedDetails, $check->getDetails());
    }

    public function testSecretsHealthcheckService_Error_NotEncryptedForRecipient(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $secret = SecretFactory::make([
            'user_id' => $betty->id,
            'data' => $this->encryptMessageFor($ada->id, 'secret'),
        ])->withSecretRevision()->persist();

        $result = (new SecretsHealthcheckService())->check();

        $check = $result[SecretsHealthcheckService::CATEGORY][SecretsHealthcheckService::CHECK_ENCRYPTED_FOR_RECIPIENT];
        $expectedMessage = sprintf(
            'Secret %s (resource %s, user %s) is not encrypted for the user\'s current OpenPGP key.',
            $secret->id,
            $secret->resource_id,
            $betty->id,
        );
        $expectedDetails = [
            [
                'status' => Healthcheck::STATUS_ERROR,
                'message' => $expectedMessage,
            ],
        ];
        $this->assertSame($expectedDetails, $check->getDetails());
    }

    public function testSecretsHealthcheckService_Success_DeletedSecretIgnored(): void
    {
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        SecretFactory::make(['user_id' => $betty->id])->withSecretRevision()->deleted()->persist();

        $result = (new SecretsHealthcheckService())->check();

        $check = $result[SecretsHealthcheckService::CATEGORY][SecretsHealthcheckService::CHECK_ENCRYPTED_FOR_RECIPIENT];
        $this->assertEmpty($check->getDetails());
    }
}
