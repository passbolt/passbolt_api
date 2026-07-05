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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\ORM\TableRegistry;

/**
 * Passkey login.
 *
 * Lets a user enrol a WebAuthn security key to sign in without typing their passphrase. On login the
 * key is verified server-side (reusing the WebAuthn relying party building blocks from the
 * MultiFactorAuthentication plugin) and, only then, the server releases its half of a split kit that
 * the browser combines with a locally kept half to decrypt the passphrase and run the normal GPGAuth
 * challenge. This mirrors the existing SSO kit mechanism, swapping the identity provider round-trip
 * for a passkey assertion. It is orthogonal to MFA (which gates the session AFTER GPGAuth).
 */
class PasskeyAuthenticationPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        TableRegistry::getTableLocator()->get('Users')
            ->hasMany('PasskeyCredentials')
            ->setClassName('Passbolt/PasskeyAuthentication.PasskeyCredentials')
            ->setForeignKey('user_id')
            ->setDependent(true);
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // Ceremony services are registered here so controllers resolve them through action injection
        // and tests can replace the assertion/attestation verifiers (which would otherwise require a
        // real authenticator) with stubs.
        $container->add(Service\PasskeyStartRegistrationService::class);
        $container->add(Service\PasskeyVerifyAttestationService::class);
        $container->add(Service\PasskeyStartAssertionService::class);
        $container->add(Service\PasskeyVerifyAssertionService::class);
        $container->add(Service\PasskeyCredentialsService::class);
        $container->add(Service\PasskeyCeremonyStoreService::class);
        $container->add(Service\PasskeyOrganizationSettingsService::class);
    }
}
