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
 * @since         5.18.0
 */

namespace App\Test\TestCase\Service\Gpgkeys;

use App\Service\Gpgkeys\GpgkeysHealthcheckService;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Utility\Healthchecks\Healthcheck;
use App\Utility\OpenPGP\Backends\Gnupg;
use Cake\Core\Configure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(GpgkeysHealthcheckService::class)]
class GpgkeysHealthcheckServiceTest extends AppTestCase
{
    use GpgkeysModelTrait;

    public function testGpgkeysHealthcheckService_Validates_Success(): void
    {
        $user = UserFactory::make()->user()->active()->withAdaKey()->persist();

        $result = (new GpgkeysHealthcheckService())->check();

        $errors = $this->getStatusDataFromCheckResult($result, GpgkeysHealthcheckService::CHECK_VALIDATES, Healthcheck::STATUS_ERROR);
        $successes = $this->getStatusDataFromCheckResult($result, GpgkeysHealthcheckService::CHECK_VALIDATES, Healthcheck::STATUS_SUCCESS);
        $this->assertEmpty($errors);
        $this->assertCount(1, $successes);
        $this->assertStringContainsString($user->gpgkey->fingerprint, $successes[0]);
    }

    public static function invalidPublicKeysProvider(): array
    {
        return [
            'private key modified as public key' => [self::getPrivateKeyModifiedAsPublicKey()],
            'public key with secret subkey' => [self::getPublicKeyWithSecretSubkey()],
            'two armor blocks' => [self::getTwoPublicKeyBlocks()],
        ];
    }

    #[DataProvider('invalidPublicKeysProvider')]
    public function testGpgkeysHealthcheckService_Validates_Error_NotOnlyPublicKeyData(string $armoredKey): void
    {
        $user = UserFactory::make()
            ->user()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make(['armored_key' => $armoredKey]))
            ->persist();
        $gpgkey = $user->gpgkey;

        $result = (new GpgkeysHealthcheckService())->check();

        $errors = $this->getStatusDataFromCheckResult($result, GpgkeysHealthcheckService::CHECK_VALIDATES, Healthcheck::STATUS_ERROR);
        $this->assertCount(1, $errors);
        $this->assertStringContainsString($gpgkey->fingerprint, $errors[0]);
    }

    #[DataProvider('invalidPublicKeysProvider')]
    public function testGpgkeysHealthcheckService_Validates_Error_StrictParsingDisabled(string $armoredKey): void
    {
        Configure::write('passbolt.gpg.security.strictPublicKeyParsing', false);
        $user = UserFactory::make()
            ->user()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make(['armored_key' => $armoredKey]))
            ->persist();
        $gpgkey = $user->gpgkey;

        $result = (new GpgkeysHealthcheckService())->check();

        $errors = $this->getStatusDataFromCheckResult($result, GpgkeysHealthcheckService::CHECK_VALIDATES, Healthcheck::STATUS_ERROR);
        $this->assertCount(1, $errors);
        $this->assertStringContainsString($gpgkey->fingerprint, $errors[0]);
        // Make sure configuration value is reset
        $this->assertFalse(Configure::read('passbolt.gpg.security.strictPublicKeyParsing'));
    }

    #[DataProvider('invalidPublicKeysProvider')]
    public function testGpgkeysHealthcheckService_InvalidKeyIsNotImportedInKeyring(string $armoredKey): void
    {
        UserFactory::make()
            ->user()
            ->active()
            ->with('Gpgkeys', GpgkeyFactory::make(['armored_key' => $armoredKey]))
            ->persist();
        $gpg = $this->getMockBuilder(Gnupg::class)
            ->onlyMethods(['importKeyIntoKeyring', 'setEncryptKeyFromFingerprint'])
            ->getMock();
        $gpg->expects($this->never())->method('importKeyIntoKeyring');
        $gpg->expects($this->never())->method('setEncryptKeyFromFingerprint');

        (new GpgkeysHealthcheckService(null, $gpg))->check();
    }

    /**
     * @param array $result Datacheck result array.
     * @param string $check Check key to get data from.
     * @param string $status Healthcheck::STATUS_ERROR or Healthcheck::STATUS_SUCCESS
     * @return array<string>
     */
    private function getStatusDataFromCheckResult(array $result, string $check, string $status): array
    {
        /** @var Healthcheck $healthcheck */
        $healthcheck = $result[GpgkeysHealthcheckService::CATEGORY][$check];

        $messages = [];
        foreach ($healthcheck->getDetails() as $detail) {
            if ($detail['status'] === $status) {
                $messages[] = $detail['message'];
            }
        }

        return $messages;
    }
}
