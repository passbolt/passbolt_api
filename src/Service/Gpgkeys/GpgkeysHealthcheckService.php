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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Gpgkey;
use App\Model\Rule\Gpgkeys\GopengpgFormatRule;
use App\Model\Table\GpgkeysTable;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use App\Utility\OpenPGP\OpenPGPBackend;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Exception\CakeException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class GpgkeysHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Gpgkeys';
    public const CHECK_CAN_ENCRYPT = 'Can encrypt';
    public const CHECK_VALIDATES = 'Pass validation service checks';
    public const CHECK_DATA_MATCHES = 'Entity data and armored key data matches';
    public const CHECK_IS_NOT_EXPIRED = 'Is not expired';
    public const CHECK_IS_KEY_FORMAT_VALID = 'Is armored key format valid';

    /**
     * @var \App\Model\Table\GpgkeysTable
     */
    private $table;

    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackend
     */
    private $gpg;

    /**
     * @var \App\Model\Rule\Gpgkeys\GopengpgFormatRule
     */
    private $gopengpgFormatRule;

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
        $this->gopengpgFormatRule = new GopengpgFormatRule();
        $this->checks = [
            self::CHECK_CAN_ENCRYPT => $this->healthcheckFactory(self::CHECK_CAN_ENCRYPT, true),
            self::CHECK_VALIDATES => $this->healthcheckFactory(self::CHECK_VALIDATES, true),
            self::CHECK_DATA_MATCHES => $this->healthcheckFactory(self::CHECK_DATA_MATCHES, true),
            self::CHECK_IS_NOT_EXPIRED => $this->healthcheckFactory(self::CHECK_IS_NOT_EXPIRED, true),
            self::CHECK_IS_KEY_FORMAT_VALID => $this->healthcheckFactory(self::CHECK_IS_KEY_FORMAT_VALID, true),
        ];
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $gpgkeys = $this->table->find()
            ->innerJoinWith('Users', function (Query $q) {
                return $q->find('activeNotDeleted');
            })
            ->contain('Users')
            ->where([$this->table->aliasField('deleted') => false]);

        foreach ($gpgkeys as $gpgkey) {
            $this->canEncrypt($gpgkey);
            $this->isKeyMatchingEntity($gpgkey);
            $this->isKeyExpired($gpgkey);
            $this->isArmoredKeyFormatValid($gpgkey);
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
    private function isKeyMatchingEntity(Gpgkey $gpgkey): void
    {
        try {
            $copy = $this->table->buildEntityFromArmoredKey($gpgkey->armored_key, $gpgkey->user_id);
            $gpgkeyData = $gpgkey->toArray();
            unset($gpgkeyData['user']);
            if (count(array_diff($copy->toArray(), $gpgkeyData))) {
                new CakeException('Parse data does not match data in database.');
            }
            $this->checks[self::CHECK_DATA_MATCHES]
                ->addDetail(__('Validation success for key {0}', $gpgkey->fingerprint), Healthcheck::STATUS_SUCCESS);
        } catch (CakeException $exception) {
            $msg = __('Validation failed for key {0}. {1}', $gpgkey->fingerprint, $exception->getMessage());
            $this->checks[self::CHECK_DATA_MATCHES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        }
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
            PublicKeyValidationService::parseAndValidatePublicKey($gpgkey->armored_key);
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for key {0}', $gpgkey->fingerprint), Healthcheck::STATUS_SUCCESS);
        } catch (CustomValidationException $exception) {
            $json = json_encode($exception->getErrors());
            $msg = __('Validation failed for key {0}. {1}', $gpgkey->fingerprint, $json);
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } catch (\Exception $exception) {
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
            $this->checks[self::CHECK_CAN_ENCRYPT]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        } catch (CakeException $exception) {
            $msg = __('Failed to encrypt with key {0}. {1}', $gpgkey->fingerprint, $exception->getMessage());
            $this->checks[self::CHECK_CAN_ENCRYPT]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
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
        } catch (CakeException $exception) {
            // Try to import the key in keyring again
            try {
                $this->gpg->importKeyIntoKeyring($armored);
                $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (CakeException $exception) {
                throw new InternalErrorException('Could not import the user OpenPGP key.', 500, $exception);
            }
        }
    }

    /**
     * @param \App\Model\Entity\Gpgkey $gpgkey Gpgkey to assess
     * @return void
     */
    private function isKeyExpired(Gpgkey $gpgkey): void
    {
        if ($gpgkey->isExpired()) {
            $msg = __('Key expired: {0}.', $gpgkey->fingerprint);
            $this->checks[self::CHECK_IS_NOT_EXPIRED]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $msg = __('Expiration date valid for key {0}.', $gpgkey->fingerprint);
            $this->checks[self::CHECK_IS_NOT_EXPIRED]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        }
    }

    /**
     * Checks that the armored key format is correct
     * - is GopenGPG compliant (no empty line at the end of the armored key)
     *
     * @param \App\Model\Entity\Gpgkey $gpgkey Gpgkey to assess
     * @return void
     */
    private function isArmoredKeyFormatValid(Gpgkey $gpgkey): void
    {
        $isValid = ($this->gopengpgFormatRule)($gpgkey);
        $email = PublicKeyValidationService::getEmailFromUid($gpgkey->uid);

        if ($isValid) {
            $msg = 'Armored key format valid for key ' . $gpgkey->fingerprint . ' ' . $email;
            $this->checks[self::CHECK_IS_KEY_FORMAT_VALID]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        } else {
            $msg = 'Armored key format not valid for key ' . $gpgkey->fingerprint . ' ' . $email;
            $this->checks[self::CHECK_IS_KEY_FORMAT_VALID]->addDetail($msg, Healthcheck::STATUS_ERROR);
        }
    }
}
