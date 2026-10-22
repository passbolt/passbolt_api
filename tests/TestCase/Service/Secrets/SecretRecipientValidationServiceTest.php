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

use App\Service\Secrets\SecretRecipientValidationService;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SecretRecipientValidationService::class)]
class SecretRecipientValidationServiceTest extends AppTestCase
{
    private const MESSAGES_FIXTURES = FIXTURES . 'OpenPGP' . DS . 'Messages' . DS;
    private const PUBLIC_KEYS_FIXTURES = FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS;

    private ?SecretRecipientValidationService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SecretRecipientValidationService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Success(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = $this->encryptMessageFor($ada->id, 'secret');

        $result = $this->service->isEncryptedForUser($message, $ada->id);

        $this->assertTrue($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Success_PrimaryKeyRecipient(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withKeyInfo([
                'armored_key' => file_get_contents(self::PUBLIC_KEYS_FIXTURES . 'rsa3072_primary_encrypt_public.key'),
                'fingerprint' => 'B31B1CD04668D021DE22577456F03B95FF39FA98',
            ]))
            ->persist();
        $message = file_get_contents(self::MESSAGES_FIXTURES . 'rsa3072_primary_encrypt_primary_recipient.msg');

        $result = $this->service->isEncryptedForUser($message, $user->id);

        $this->assertTrue($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Success_MultipleRecipients(): void
    {
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $message = file_get_contents(self::MESSAGES_FIXTURES . 'ada_for_betty_and_ada.msg');

        $result = $this->service->isEncryptedForUser($message, $betty->id);

        $this->assertTrue($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_OtherUserKey(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $messageForAda = $this->encryptMessageFor($ada->id, 'secret');

        $result = $this->service->isEncryptedForUser($messageForAda, $betty->id);

        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_NoCurrentKey(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = $this->encryptMessageFor($ada->id, 'secret');
        $userWithDeletedKey = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey()->deleted())
            ->persist();
        $userWithoutKey = UserFactory::make()->persist();

        $resultWithDeletedKey = $this->service->isEncryptedForUser($message, $userWithDeletedKey->id);
        $resultWithoutKey = $this->service->isEncryptedForUser($message, $userWithoutKey->id);

        $this->assertFalse($resultWithDeletedKey);
        $this->assertFalse($resultWithoutKey);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_HiddenRecipient(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = file_get_contents(self::MESSAGES_FIXTURES . 'ada_hidden_recipient.msg');

        $result = $this->service->isEncryptedForUser($message, $ada->id);

        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_SymmetricMessage(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = file_get_contents(self::MESSAGES_FIXTURES . 'symetric_secret_password.msg');

        $result = $this->service->isEncryptedForUser($message, $ada->id);

        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_NotParsable(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();

        $result = $this->service->isEncryptedForUser('not an openpgp message', $ada->id);

        $this->assertFalse($result);
    }
}
