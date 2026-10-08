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
namespace App\Test\Factory;

use App\Model\Entity\User;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Symfony\Component\Uid\Uuid;
use Webauthn\CredentialRecord;
use Webauthn\TrustPath\EmptyTrustPath;

/**
 * WebauthnCredentialFactory
 */
abstract class WebauthnCredentialFactory extends CakephpBaseFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            $credentialId = 'cred_' . bin2hex(random_bytes(16));

            return [
                'credential_id' => $credentialId,
                'data' => self::makeCredentialRecord($credentialId),
                'sign_count' => 0,
                'name' => $faker->words(2, true),
                'last_used' => null,
                'created' => DateTime::now()->subMinutes($faker->numberBetween(1, 100)),
                'modified' => DateTime::now()->subMinutes($faker->numberBetween(1, 100)),
            ];
        });

        /** @var \App\Model\Table\WebauthnCredentialsTable $table */
        $table = $this->getTable();
        $this->setField('type', $table->getType());
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @return $this
     */
    public function withUser(User $user)
    {
        return $this->with('Users', $user);
    }

    /**
     * @param string $credentialId Credential id.
     * @return $this
     */
    public function credentialId(string $credentialId)
    {
        return $this->patchData([
            'credential_id' => $credentialId,
            'data' => self::makeCredentialRecord($credentialId),
        ]);
    }

    /**
     * @param string $name Friendly name.
     * @return $this
     */
    public function name(string $name)
    {
        return $this->patchData(['name' => $name]);
    }

    /**
     * @param int $signCount Sign count.
     * @return $this
     */
    public function signCount(int $signCount)
    {
        return $this->patchData(['sign_count' => $signCount]);
    }

    /**
     * @param \Cake\I18n\DateTime|null $lastUsed Last used date.
     * @return $this
     */
    public function lastUsed(?DateTime $lastUsed)
    {
        return $this->patchData(['last_used' => $lastUsed]);
    }

    /**
     * @param string $credentialId Credential id.
     * @return \Webauthn\CredentialRecord
     */
    public static function makeCredentialRecord(string $credentialId): CredentialRecord
    {
        return CredentialRecord::create(
            $credentialId,
            'public-key',
            ['usb'],
            'none',
            EmptyTrustPath::create(),
            Uuid::fromString('00000000-0000-0000-0000-000000000000'),
            'fake-public-key-' . $credentialId,
            'fake-user-handle',
            0,
        );
    }
}
