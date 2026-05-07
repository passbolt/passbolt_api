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

namespace Passbolt\Scim\Identifier\Resolver;

use App\Model\Entity\Role;
use ArrayAccess;
use Authentication\Identifier\Resolver\ResolverInterface;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Exception;
use Passbolt\Scim\Service\ScimGetSettingsService;
use Passbolt\Scim\Service\ScimSetSettingsService;
use Passbolt\Scim\Utility\ScimTokenVerifier;

class ScimResolver implements ResolverInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function find(array $conditions, string $type = self::TYPE_AND): ArrayAccess|array|null
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $settings */
        $settings = $scimSettingsTable->find()->first();
        if (!$settings) {
            return null;
        }

        $scimConfig = (new ScimGetSettingsService())->getSettingsDecryptedValue();
        if ($scimConfig['setting_id'] !== Configure::read('Scim.settingId')) {
            //TODO Check if we want to notify admin if a wrong setting is passed
            return null;
        }

        if (empty($scimConfig['scim_user_id'])) {
            return null;
        }

        $rawToken = $conditions['secret_token'];
        $storedHash = $scimConfig['secret_token'];

        if (!ScimTokenVerifier::verify($rawToken, $storedHash)) {
            return null;
        }

        $this->warnIfTokenExpired($scimConfig);

        // Rehash legacy tokens to bcrypt so they don't stay as unsalted SHA-256 indefinitely.
        // If rehashing fails, auth still succeeds — rehash retries on next request.
        if ($this->needsRehash($storedHash)) {
            try {
                (new ScimSetSettingsService())->rehashToken($rawToken);
            } catch (Exception $e) {
                Log::error('Failed to rehash SCIM token to bcrypt: ' . $e->getMessage());
            }
        }

        /** @var \App\Model\Table\UsersTable $Users */
        $Users = $this->fetchTable('Users');

        return $Users->findIndex(Role::GUEST)->where([
            $Users->aliasField('id') => $scimConfig['scim_user_id'],
        ])->first();
    }

    /**
     * Log a warning if the SCIM secret token has expired.
     *
     * @param array $scimConfig The decrypted SCIM settings.
     * @return void
     */
    private function warnIfTokenExpired(array $scimConfig): void
    {
        $expired = $scimConfig['expired'] ?? null;
        if ($expired === null) {
            return;
        }

        if (Date::now()->greaterThan(new Date($expired))) {
            Log::warning(__('The SCIM secret token is expired, you are requested to rotate it.'));
        }
    }

    /**
     * Check if the stored hash needs to be rehashed to bcrypt.
     *
     * @param string $storedHash The stored hash value.
     * @return bool
     */
    private function needsRehash(string $storedHash): bool
    {
        if (!ScimTokenVerifier::isBcryptHash($storedHash)) {
            return true; // Legacy SHA-256 — always needs rehash
        }

        /** @var int $cost */
        $cost = Configure::read('passbolt.plugins.scim.security.secretToken.cost', 12);

        return password_needs_rehash($storedHash, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}
