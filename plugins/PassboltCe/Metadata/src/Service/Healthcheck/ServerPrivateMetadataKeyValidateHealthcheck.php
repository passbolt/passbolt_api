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
 * @since         5.4.0
 */

namespace Passbolt\Metadata\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Exception;
use Passbolt\Metadata\Form\MetadataCleartextPrivateKeyForm;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;

class ServerPrivateMetadataKeyValidateHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    use LocatorAwareTrait;
    use OpenPGPCommonServerOperationsTrait;

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
        $metadataKeysSettingsDto = MetadataTypesSettingsGetService::getSettings();
        if (!$metadataKeysSettingsDto->isV5Enabled()) {
            $this->status = true;

            return $this;
        }

        // Find metadata private keys
        $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');
        $serverMetadataPrivateKeys = $metadataPrivateKeysTable
            ->find()
            ->contain('MetadataKeys')
            ->innerJoinWith('MetadataKeys', function (SelectQuery $q) {
                $expr = $q->newExpr()->isNull('MetadataKeys.deleted');

                return $q->where([$expr]);
            })
            ->where(['MetadataPrivateKeys.user_id IS' => null])
            ->all();

        if ($serverMetadataPrivateKeys->isEmpty()) {
            $this->status = true;

            return $this;
        }

        $openpgp = OpenPGPBackendFactory::get();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $serverMetadataPrivateKey */
        foreach ($serverMetadataPrivateKeys as $serverMetadataPrivateKey) {
            $id = $serverMetadataPrivateKey->get('id');
            // Try to decrypt it
            try {
                $openpgp->clearKeys();
                $openpgp = $this->setDecryptKeyWithServerKey($openpgp);
                if ($serverMetadataPrivateKey->metadata_key->modified_by === null) {
                    $this->setVerifyKeyWithServerKey($openpgp);
                    $metadataPrivateKey = $openpgp->decrypt($serverMetadataPrivateKey->data, true);
                } else {
                    // TODO verify with user key
                    $metadataPrivateKey = $openpgp->decrypt($serverMetadataPrivateKey->data, false);
                }
            } catch (Exception $exception) {
                $this->errorMessage = __('Unable to decrypt the metadata private key (id: {0}) data.', $id) . ' ';
                $this->errorMessage .= $exception->getMessage();

                return $this;
            }

            try {
                $privateKeyArray = json_decode($metadataPrivateKey, true, 512, JSON_THROW_ON_ERROR);
            } catch (Exception $exception) {
                $this->errorMessage = __('The metadata private key (id: {0}) cleartext data should be in JSON format.', $id); // phpcs:ignore
                if (Configure::read('debug')) {
                    Log::error('[ServeMetadataKeyValidateHealthcheck] ' . $exception->getMessage());
                }

                return $this;
            }
            if (!is_array($privateKeyArray) || empty($privateKeyArray)) {
                $this->errorMessage = __('The metadata private key (id: {0}) cleartext data should not be empty.', $id);

                return $this;
            }

            $form = new MetadataCleartextPrivateKeyForm();
            if (!$form->validate($privateKeyArray)) {
                $this->errorMessage = __('Unable to validate metadata private key (id: {0}) cleartext data.', $id);
                if (Configure::read('debug')) {
                    Log::error('[ServeMetadataKeyValidateHealthcheck] Validation errors: ' . json_encode($form->getErrors())); // phpcs:ignore
                }

                return $this;
            }
        }

        // validation passed
        $this->status = true;

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
        return __('The server metadata private key is valid.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $msg = __('The server metadata private key is not valid.');
        if (!is_null($this->errorMessage)) {
            $msg .= ' ' . $this->errorMessage;
        }

        return $msg;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
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
        return 'canValidatePrivateMetadataKey';
    }
}
