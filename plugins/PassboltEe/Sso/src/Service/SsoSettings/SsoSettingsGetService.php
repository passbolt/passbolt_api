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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Service\SsoSettings;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Dto\AbstractSsoSettingsDto;
use Passbolt\Sso\Model\Dto\SsoSettingsDefaultDto;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;

class SsoSettingsGetService
{
    /**
     * Return a setting identified with its id
     *
     * @param string $id uuid
     * @throws \Cake\Http\Exception\BadRequestException if $id is not a valid uuid
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if setting cannot be found
     * @throws \Cake\Http\Exception\InternalErrorException if there is an issue with settings data decryption
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    public function getByIdOrFail(string $id): SsoSettingsDto
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO setting id should be a uuid.'));
        }

        try {
            return $this->getOrFail(['id' => $id], true);
        } catch (RecordNotFoundException $exception) {
            throw new RecordNotFoundException(__('The SSO setting does not exist.'), 404, $exception);
        }
    }

    /**
     * Get the currently active setting or return default setting (disabled)
     *
     * @param bool $withData with settings data, e.g. provider specific data
     * @return \Passbolt\Sso\Model\Dto\AbstractSsoSettingsDto
     */
    public function getActiveOrDefault(?bool $withData = false): AbstractSsoSettingsDto
    {
        try {
            return $this->getActiveOrFail($withData);
        } catch (RecordNotFoundException $exception) {
            return new SsoSettingsDefaultDto();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            return new SsoSettingsDefaultDto();
        }
    }

    /**
     * Get the currently active setting or return default setting (disabled)
     *
     * @param bool $withData with settings data, e.g. provider specific data
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if setting cannot be found
     * @throws \Cake\Http\Exception\InternalErrorException if there is an issue with settings data decryption
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    public function getActiveOrFail(?bool $withData = false): SsoSettingsDto
    {
        return $this->getOrFail(['status' => SsoSetting::STATUS_ACTIVE], $withData);
    }

    /**
     * Get the currently active setting or return default setting (disabled)
     *
     * @param string $id uuid
     * @param bool $withData with settings data, e.g. provider specific data
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if setting cannot be found
     * @throws \Cake\Http\Exception\InternalErrorException if there is an issue with settings data decryption
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    public function getDraftByIdOrFail(string $id, ?bool $withData = false): SsoSettingsDto
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO setting id should be a uuid.'));
        }

        return $this->getOrFail(['id' => $id, 'status' => SsoSetting::STATUS_DRAFT], $withData);
    }

    /**
     * Get the setting or return default setting (disabled)
     *
     * @param array $where conditions
     * @param bool $withData with settings data, e.g. provider specific data
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if setting cannot be found
     * @throws \Cake\Http\Exception\InternalErrorException if there is an issue with settings data decryption
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    protected function getOrFail(array $where, ?bool $withData = false): SsoSettingsDto
    {
        $ssoSettingsTable = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');
        try {
            /** @var \Passbolt\Sso\Model\Entity\SsoSetting $ssoSettingEntity */
            $ssoSettingEntity = $ssoSettingsTable->find()
                ->where($where)
                ->order(['modified' => 'DESC'])
                ->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new RecordNotFoundException(__('The SSO settings do not exist.'), 400, $exception);
        }

        if ($withData) {
            $jsonData = $this->decrypt($ssoSettingEntity->data);
        }

        return new SsoSettingsDto($ssoSettingEntity, $jsonData ?? null);
    }

    /**
     * Decrypt the data part of the SsoSetting entity
     *
     * @param string $data openpgp data
     * @throws \Cake\Http\Exception\InternalErrorException if there is an issue with settings data decryption
     * @return array
     */
    protected function decrypt(string $data): array
    {
        $gpg = OpenPGPBackendFactory::get();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');

        try {
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        try {
            $decryptedData = $gpg->decrypt($data);
        } catch (\Exception $exception) {
            throw new InternalErrorException(__('The SSO setting cannot be decrypted.'), 500, $exception);
        }

        $decodedData = json_decode($decryptedData, true);
        if (!isset($decodedData) || !is_array($decodedData)) {
            throw new InternalErrorException(__('The SSO setting cannot be decoded.'));
        }

        return $decodedData;
    }
}
