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
 * @since         2.13.0
 */
namespace App\Service\Gpgkeys;

use App\Model\Entity\Gpgkey;
use App\Model\Table\GpgkeysTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use App\Utility\OpenPGP\OpenPGPBackend;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GpgkeysHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Gpgkeys';
    public const CHECK_CANENCRYPT = 'Can encrypt';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\GpgkeysTable
     */
    private $table;

    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackend
     */
    private $gpg;

    /**
     * Service constructor.
     *
     * @param \App\Model\Table\GpgkeysTable|null $table gpgkeys table
     * @param \App\Utility\OpenPGP\OpenPGPBackend|null $gpg gpg backend to use
     */
    public function __construct(?GpgkeysTable $table = null, ?OpenPGPBackend $gpg = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->gpg = $gpg ?? OpenPGPBackendFactory::get();
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Gpgkeys');
        $this->checks[self::CHECK_CANENCRYPT] = $this->healthcheckFactory(self::CHECK_CANENCRYPT, true);
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $recordIds = $this->table->find()
            ->select('id')
            ->where(['deleted' => false])
            ->all()
            ->toArray();
        $recordIds = Hash::extract($recordIds, '{n}.id');

        foreach ($recordIds as $i => $id) {
            $gpgkey = $this->table->get($id);
            $this->canEncrypt($gpgkey);
            $this->canValidate($gpgkey);
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\Gpgkey $gpgkey gpg key
     * @return void
     */
    private function canValidate(Gpgkey $gpgkey): void
    {
        try {
            $copy = $this->table->buildEntityFromArmoredKey($gpgkey->armored_key, $gpgkey->user_id);
            if (count(array_diff($copy->toArray(), $gpgkey->toArray()))) {
                new Exception('Parse data does not match data in database.');
            }
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for key {0}', $gpgkey->fingerprint), Healthcheck::STATUS_SUCCESS);
        } catch (Exception $exception) {
            $msg = __('Validation failed for key {0}. {1}', $gpgkey->fingerprint, $exception->getMessage());
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        }
    }

    /**
     * Can encrypt
     *
     * @param \App\Model\Entity\Gpgkey $gpgkey gpg key
     * @return void
     */
    private function canEncrypt(Gpgkey $gpgkey): void
    {
        try {
            $this->initUserKey($gpgkey->fingerprint, $gpgkey->armored_key);
            $this->gpg->encrypt('test');
            $msg = __('Encryption success for key {0}', $gpgkey->fingerprint);
            $this->checks[self::CHECK_CANENCRYPT]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        } catch (Exception $exception) {
            $msg = __('Failed to encrypt with key {0}. {1}', $gpgkey->fingerprint, $exception->getMessage());
            $this->checks[self::CHECK_CANENCRYPT]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        }
    }

    /**
     * Set user key for encryption and import it in the keyring if needed
     *
     * @param string $fingerprint fingerprint
     * @param string $armored armored
     * @throws \Cake\Http\Exception\InternalErrorException when the key is not valid
     * @return void
     */
    private function initUserKey(string $fingerprint, string $armored): void
    {
        try {
            $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (Exception $exception) {
            // Try to import the key in keyring again
            try {
                $this->gpg->importKeyIntoKeyring($armored);
                $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (Exception $exception) {
                throw new InternalErrorException(__('The OpenPGP key for the user could not be imported in GnuPG.'));
            }
        }
    }
}
