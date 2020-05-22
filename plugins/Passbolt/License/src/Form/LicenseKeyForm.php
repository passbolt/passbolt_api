<?php
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
 * @since         2.0.0
 */
namespace Passbolt\License\Form;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class LicenseKeyForm extends Form
{
    /**
     * Gpg object.
     */
    protected $_gpg = null;

    /**
     * LicenseKeyForm constructor.
     *
     * @param EventManager|null $eventManager event manager
     */
    public function __construct(EventManager $eventManager = null)
    {
        $this->_gpg = OpenPGPBackendFactory::get();

        return parent::__construct($eventManager);
    }

    /**
     * License key schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('key_ascii', 'text');
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('key_ascii', 'create', __('A subscription key is required.'))
            ->notEmpty('key_ascii', __('A subscription key is required.'))
            ->add('key_ascii', 'is_valid_license_format', [
                'last' => true,
                'rule' => [$this, 'checkLicenseFormat'],
                'message' => 'The license format is not valid.',
            ])
            ->add('key_ascii', 'is_valid_license', [
                'last' => true,
                'rule' => [$this, 'checkSignature'],
                'message' => 'The license content or signature is not valid.',
            ]);

        return $validator;
    }

    /**
     * Check if a license is in a valid format.
     *
     * @param string $value The license
     * @param array $context not in use
     * @return bool
     */
    public function checkLicenseFormat(string $value, array $context = null)
    {
        try {
            $this->getArmoredSignedLicense($value);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Check if the license is signed properly.
     *
     * @param string $value The license
     * @param array|null $context not in use
     * @return string|bool
     */
    public function checkSignature(string $value, array $context = null)
    {
        try {
            $this->parse($value);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Parse the license.
     *
     * @param string $keyAscii key in ascii.
     *
     * @return array The license info. On failure, this function returns FALSE.
     * @throws \Exception If the license format is not valid
     */
    public function parse(string $keyAscii = null)
    {
        if (empty($keyAscii) && !empty($this->getData('key_ascii'))) {
            $keyAscii = $this->getData('key_ascii');
        }

        $armoredSignedLicense = $this->getArmoredSignedLicense($keyAscii);
        $licenseInfoStr = $this->_verifySignature($armoredSignedLicense);
        $licenseInfo = \json_decode($licenseInfoStr, true);
        if (is_null($licenseInfo)) {
            throw new \Exception(__('The license cannot be verified. Parse error.'));
        }

        return $licenseInfo;
    }

    /**
     * Get the armored license.
     *
     * @param string $keyAscii key in ascii
     * @return string The armored signed license
     * @throws \Exception If the license format is not valid
     */
    public function getArmoredSignedLicense(string $keyAscii)
    {
        $armoredSignedLicense = base64_decode($keyAscii);
        if (!$armoredSignedLicense) {
            throw new \Exception(__('The license format is not valid.'));
        }

        $isSignedMessage = $this->_gpg->isParsableArmoredSignedMessage($armoredSignedLicense);
        if (!$isSignedMessage) {
            throw new \Exception(__('The license format is not valid. Invalid format.'));
        }

        return $armoredSignedLicense;
    }

    /**
     * Verify the license signature
     *
     * @param string $licenseSigned The signed license to verify.
     * @return array The license info.
     * @throws \Exception If the gpg public license key cannot be imported into the keyring
     * @throws \Exception If the license cannot be verified
     */
    protected function _verifySignature($licenseSigned)
    {
        $licenseInfo = null;
        $filePublicKey = Configure::read('passbolt.plugins.license.licenseKey.public');
        if (!file_exists($filePublicKey)) {
            throw new \Exception(__('The license cannot be verified.'));
        }
        $licensePublicKey = file_get_contents($filePublicKey);
        $fingerprint = $this->_gpg->importKeyIntoKeyring($licensePublicKey);
        try {
            $this->_gpg->verify($licenseSigned, $fingerprint, $licenseInfo);
        } catch (\Exception $e) {
            throw new \Exception(__('The license cannot be verified. Invalid signature.'));
        }

        return $licenseInfo;
    }

    /**
     * Execute implementation.
     * @param array $data formdata
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
