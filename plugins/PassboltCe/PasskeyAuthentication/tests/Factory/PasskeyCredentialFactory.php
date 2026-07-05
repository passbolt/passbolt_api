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
namespace Passbolt\PasskeyAuthentication\Test\Factory;

use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * PasskeyCredentialFactory
 *
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential getEntity()
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential[] getEntities()
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential persist()
 */
class PasskeyCredentialFactory extends CakephpBaseFactory
{
    /**
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/PasskeyAuthentication.PasskeyCredentials';
    }

    /**
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            $credentialId = rtrim(strtr(base64_encode($faker->sha256()), '+/', '-_'), '=');

            return [
                'id' => UuidFactory::uuid(),
                'user_id' => $faker->uuid(),
                'credential_id' => $credentialId,
                // Placeholder serialized credential material; real records are exercised end-to-end.
                'data' => '{"placeholder":"credential-record"}',
                'server_kit_key' => base64_encode($faker->sha256()),
                'sign_count' => 0,
                'transports' => json_encode(['usb']),
                'aaguid' => UuidFactory::uuid(),
                'name' => $faker->words(2, true),
                'last_used' => null,
                'created' => DateTime::now()->subDays(1),
                'modified' => DateTime::now()->subDays(1),
            ];
        });
    }

    /**
     * Assign the credential to a given user.
     *
     * @param string $userId User id
     * @return self
     */
    public function withUser(string $userId): self
    {
        return $this->patchData(['user_id' => $userId]);
    }

    /**
     * Persist a credential without its kit's server half (a credential that cannot be used for
     * passkey login).
     *
     * @return self
     */
    public function withoutServerKitKey(): self
    {
        return $this->patchData(['server_kit_key' => null]);
    }
}
