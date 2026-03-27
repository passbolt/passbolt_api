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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Date;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;
use Passbolt\Scim\Utility\ScimTokenVerifier;

class ScimSetSettingsService extends ScimBaseSettingsService
{
    use EventDispatcherTrait;

    /**
     * Secret token prefix
     */
    public const SCIM_SECRET_TOKEN_PREFIX = 'pb_';

    /**
     * Dummy token sent by the client when the token should not be changed.
     */
    public const SCIM_SECRET_TOKEN_DUMMY = 'pb_0000000000000000000000000000000000000000000';

    public const SCIM_SETTINGS_UPDATE_EVENT_NAME = 'scim_settings_update_event_name';

    /**
     * @param \App\Utility\UserAccessControl $uac
     * @param array $data
     * @param string|null $id
     * @return array
     * @throws \Exception
     */
    public function saveSettings(UserAccessControl $uac, array $data, ?string $id = null): array
    {
        // Capture the raw plaintext token before form hashes it with bcrypt
        $rawSecretToken = $data['secret_token'] ?? null;

        $form = new ScimSettingsForm();
        if ($id) {
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The SCIM setting identifier should be a valid UUID.'));
            }
            $data['id'] = $id;
        }

        // Using this approach to avoid checking for setting_id duplicates on update
        $validate = $id ? 'update' : 'extended';
        if (!$form->execute($data, ['validate' => $validate])) {
            throw new FormValidationException(
                __('Could not validate the SCIM settings.'),
                $form
            );
        }

        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $current */
        $current = $scimSettingsTable->find()->first();
        if (!$current && $id) {
            throw new NotFoundException(__('The SCIM plugin is disabled.'));
        }
        if (!$id && $current) {
            throw new BadRequestException(__('Please delete previous settings before creating again.'));
        }
        if ($current && $current->id !== $id) {
            throw new NotFoundException(__('The uuid in the url doesn\'t match any known setting record.'));
        }

        $currentValue = [];
        $isDummyToken = $rawSecretToken === self::SCIM_SECRET_TOKEN_DUMMY;
        if ($current) {
            $currentValue = $this->decryptSettings($current);
            $form->set('setting_id', Hash::get($currentValue, 'setting_id'));
            if (!$form->getData('secret_token') || $isDummyToken) {
                $form->set('secret_token', Hash::get($currentValue, 'secret_token'));
            }
        }

        $settingsData = $form->getData();
        $isTokenRotated = $this->isTokenRotated($rawSecretToken, $currentValue);
        if (!$current || $isTokenRotated) {
            $settingsData['expired'] = $this->computeExpiredDate();
        } else {
            $settingsData['expired'] = Hash::get($currentValue, 'expired');
        }

        $value = $this->encryptSettings($settingsData);
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $setting */
        $setting = $scimSettingsTable->createOrUpdateSetting(
            $scimSettingsTable->getProperty(),
            $value,
            $uac
        );

        $renderedSettings = $this->getRenderedValue($setting, $form);

        $this->dispatchEvent(
            self::SCIM_SETTINGS_UPDATE_EVENT_NAME,
            $renderedSettings,
        );

        return $renderedSettings;
    }

    /**
     * Rehash a SCIM bearer token from legacy SHA-256 to bcrypt.
     *
     * Called transparently during authentication when a legacy token
     * format is detected. This ensures all tokens converge to bcrypt
     * over time without requiring admin intervention.
     *
     * @param string $rawToken The plaintext bearer token.
     * @return void
     */
    public function rehashToken(string $rawToken): void
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $settings */
        $settings = $scimSettingsTable->find()->first();
        if (!$settings) {
            return;
        }

        /** @var int $cost */
        $cost = Configure::read('passbolt.plugins.scim.security.secretToken.cost', 12);

        $scimConfig = $this->decryptSettings($settings);
        $scimConfig['secret_token'] = password_hash($rawToken, PASSWORD_BCRYPT, ['cost' => $cost]);

        $settings->set('value', $this->encryptSettings($scimConfig));
        $scimSettingsTable->save($settings);
    }

    /**
     * Determine if the raw token sent by the client differs from the stored hash.
     *
     * @param string|null $rawToken The plaintext token from the request (before bcrypt hashing).
     * @param array $currentValue The decrypted current settings from the database.
     * @return bool True if the token was rotated (i.e. a new token was provided).
     */
    private function isTokenRotated(?string $rawToken, array $currentValue): bool
    {
        if ($rawToken === null || $rawToken === self::SCIM_SECRET_TOKEN_DUMMY) {
            return false;
        }

        $storedHash = Hash::get($currentValue, 'secret_token');
        if ($storedHash === null) {
            return true;
        }

        return !ScimTokenVerifier::verify($rawToken, $storedHash);
    }

    /**
     * Compute the expiration date for the SCIM secret token based on the configured expiry duration.
     *
     * @return string Date in Y-m-d format.
     * @throws \Cake\Http\Exception\InternalErrorException If the expiry configuration is invalid.
     */
    private function computeExpiredDate(): string
    {
        /** @var string|null $expiry */
        $expiry = Configure::read('passbolt.plugins.scim.security.secretToken.expiry');
        if ($expiry === null) {
            throw new InternalErrorException(__('The SCIM secret token expiry configuration is invalid.'));
        }

        return Date::now()->modify('+' . $expiry)->format('Y-m-d');
    }

    /**
     * Generate crypto-secure token for authentication
     *
     * @return string
     * @throws \Exception
     */
    public static function generateToken(): string
    {
        // Generate 256-bit entropy token
        $bin = random_bytes(32);
        // Base64 gives exact 43 characters (cutting "==" from last)
        $token = rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');

        // Including prefix total length becomes 46
        return self::SCIM_SECRET_TOKEN_PREFIX . $token;
    }
}
