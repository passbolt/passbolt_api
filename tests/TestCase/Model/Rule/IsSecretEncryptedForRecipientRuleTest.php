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

namespace App\Test\TestCase\Model\Rule;

use App\Model\Entity\Secret;
use App\Model\Rule\IsSecretEncryptedForRecipientRule;
use App\Service\Secrets\SecretRecipientValidationService;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsSecretEncryptedForRecipientRule::class)]
class IsSecretEncryptedForRecipientRuleTest extends AppTestCase
{
    public function testIsSecretEncryptedForRecipientRule_Success(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $secret = new Secret([
            'user_id' => $betty->id,
            'data' => $this->encryptMessageFor($betty->id, 'secret'),
        ]);

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertTrue($result);
    }

    public function testIsSecretEncryptedForRecipientRule_Error_OtherUserKey(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $secret = new Secret([
            'user_id' => $betty->id,
            'data' => $this->encryptMessageFor($ada->id, 'secret'),
        ]);

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertFalse($result);
    }

    public function testIsSecretEncryptedForRecipientRule_Error_MissingData(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $secret = new Secret(['user_id' => $betty->id]);

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertFalse($result);
    }

    public function testIsSecretEncryptedForRecipientRule_Error_InvalidUserId(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $ada = UserFactory::make()->withAdaKey()->persist();
        $secret = new Secret([
            'user_id' => 'not-a-uuid',
            'data' => $this->encryptMessageFor($ada->id, 'secret'),
        ]);

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertFalse($result);
    }

    public function testIsSecretEncryptedForRecipientRule_Success_ValidationDisabled(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, false);
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $secret = new Secret([
            'user_id' => $betty->id,
            'data' => $this->encryptMessageFor($ada->id, 'secret'),
        ]);

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertTrue($result);
    }

    public function testIsSecretEncryptedForRecipientRule_Success_ExistingSecretDataNotChanged(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        // An existing secret already stored with the wrong key, loaded without any change.
        $secret = new Secret([
            'user_id' => $betty->id,
            'data' => $this->encryptMessageFor($ada->id, 'secret'),
        ], ['markNew' => false, 'markClean' => true]);

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertTrue($result);
    }

    public function testIsSecretEncryptedForRecipientRule_Error_ExistingSecretDataChanged(): void
    {
        Configure::write(SecretRecipientValidationService::CONFIG_KEY_VALIDATE_RECIPIENT, true);
        $ada = UserFactory::make()->withAdaKey()->persist();
        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $secret = new Secret([
            'user_id' => $betty->id,
            'data' => $this->encryptMessageFor($betty->id, 'secret'),
        ], ['markNew' => false, 'markClean' => true]);
        $secret->set('data', $this->encryptMessageFor($ada->id, 'new secret'));

        $result = (new IsSecretEncryptedForRecipientRule())($secret, []);

        $this->assertFalse($result);
    }
}
