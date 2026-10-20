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
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SecretRecipientValidationService::class)]
class SecretRecipientValidationServiceTest extends AppTestCase
{
    private const MESSAGES_FIXTURES = FIXTURES . 'OpenPGP' . DS . 'Messages' . DS;
    private const PUBLIC_KEYS_FIXTURES = FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        SecretRecipientValidationService::reset();
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Success(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = $this->encryptMessageFor($ada->id, 'secret');

        $result = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $ada->id);

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

        $result = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $user->id);

        $this->assertTrue($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_OtherUserKey(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $messageForAda = $this->encryptMessageFor($ada->id, 'secret');

        $result = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($messageForAda, $betty->id);

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

        $resultWithDeletedKey = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $userWithDeletedKey->id);
        $resultWithoutKey = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $userWithoutKey->id);

        $this->assertFalse($resultWithDeletedKey);
        $this->assertFalse($resultWithoutKey);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_HiddenRecipient(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = file_get_contents(self::MESSAGES_FIXTURES . 'ada_hidden_recipient.msg');

        $result = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $ada->id);

        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_SymmetricMessage(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = file_get_contents(self::MESSAGES_FIXTURES . 'symetric_secret_password.msg');

        $result = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $ada->id);

        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_NotParsable(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();

        $result = SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey('not an openpgp message', $ada->id);

        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEnabled_Success(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $result = SecretRecipientValidationService::isEnabled();
        $this->assertTrue($result);
    }

    public function testSecretRecipientValidationService_IsEnabled_Disabled(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, false);
        $result = SecretRecipientValidationService::isEnabled();
        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_IsEnabled_MissingConfiguration(): void
    {
        Configure::delete(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT);
        $result = SecretRecipientValidationService::isEnabled();
        $this->assertFalse($result);
    }

    public function testSecretRecipientValidationService_PreloadKeys_Success(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $messageForAda = $this->encryptMessageFor($ada->id, 'secret');
        $messageForBetty = $this->encryptMessageFor($betty->id, 'secret');

        SecretRecipientValidationService::preloadKeys([$ada->id, $betty->id]);

        $this->assertTrue(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($messageForAda, $ada->id));
        $this->assertTrue(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($messageForBetty, $betty->id));
        $this->assertFalse(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($messageForAda, $betty->id));
    }

    public function testSecretRecipientValidationService_PreloadKeys_Success_NewestKeyWins(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $messageForAda = $this->encryptMessageFor($ada->id, 'secret');
        $messageForBetty = $this->encryptMessageFor($betty->id, 'secret');
        // A user with two non-deleted keys: Betty's key is the oldest, Ada's key the newest.
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey()->patchData(['created' => DateTime::yesterday()]))
            ->persist();
        GpgkeyFactory::make()
            ->withAdaKey()
            ->patchData(['user_id' => $user->id, 'created' => DateTime::now()])
            ->persist();
        $this->assertSame(2, GpgkeyFactory::find()->where(['user_id' => $user->id])->count());

        SecretRecipientValidationService::preloadKeys([$user->id]);

        $this->assertTrue(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($messageForAda, $user->id));
        $this->assertFalse(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($messageForBetty, $user->id));
    }

    public function testSecretRecipientValidationService_IsEncryptedForUser_Error_UserWithoutKeyPreloaded(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $userWithoutKey = UserFactory::make()->persist();
        $message = $this->encryptMessageFor($ada->id, 'secret');

        SecretRecipientValidationService::preloadKeys([$userWithoutKey->id]);

        $this->assertFalse(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, $userWithoutKey->id));
    }

    public function testSecretRecipientValidationService_PreloadKeys_Success_IgnoresInvalidUserIds(): void
    {
        $ada = UserFactory::make()->withAdaKey()->persist();
        $message = $this->encryptMessageFor($ada->id, 'secret');

        SecretRecipientValidationService::preloadKeys([]);
        SecretRecipientValidationService::preloadKeys(['not-a-uuid', null, 42, ['array']]);

        $this->assertFalse(SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($message, 'not-a-uuid'));
    }
}
