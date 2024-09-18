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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

class ServerCanDecryptMetadataPrivateKeyHealthcheck implements HealthcheckServiceInterface
{
    use LocatorAwareTrait;

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @var string|null
     */
    private ?string $errorMessage = null;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');
            /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $serverMetadataPrivateKey */
            $serverMetadataPrivateKey = $metadataPrivateKeysTable
                ->find()
                ->innerJoinWith('MetadataKeys', function (Query $q) {
                    $expr = $q->newExpr()->isNull('MetadataKeys.deleted');

                    return $q->where([$expr]);
                })
                ->where(['MetadataPrivateKeys.user_id IS' => null])
                ->order(['MetadataPrivateKeys.created' => 'DESC'])
                ->firstOrFail();
        } catch (\PDOException | RecordNotFoundException $exception) {
            $this->errorMessage = __('No server metadata private key found.');
            if (Configure::read('debug')) {
                $this->errorMessage .= ' ' . $exception->getMessage();
            }

            // No metadata private key found
            return $this;
        }

        // Try to decrypt it
        try {
            $this->decrypt($serverMetadataPrivateKey->data);

            // mark as succeed if able to decrypt
            $this->status = true;
        } catch (\Exception $exception) {
            // failure
            $this->errorMessage = __('Unable to decrypt the metadata private key data.') . ' ';
            $this->errorMessage .= $exception->getMessage();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_METADATA;
    }

    /**
     * @inheritDoc
     */
    public function isPassed(): bool
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function level(): string
    {
        return HealthcheckServiceCollector::LEVEL_ERROR;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The server is able to decrypt the metadata private key.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        if (is_null($this->errorMessage)) {
            $this->errorMessage = __('Unable to decrypt the metadata private key.');
        }

        return $this->errorMessage;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return null;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_METADATA;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'canDecryptMetadataPrivateKey';
    }

    /**
     * @param string $data Message to decrypt.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException When unable to decrypt metadata private key data.
     */
    private function decrypt(string $data): void
    {
        $gpg = OpenPGPBackendFactory::get();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');

        try {
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setVerifyKeyFromFingerprint($fingerprint);
                $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('Unable to decrypt the metadata private key.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        $gpg->decrypt($data, true);
    }
}
