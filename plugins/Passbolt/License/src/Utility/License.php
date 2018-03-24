<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\License\Utility;

use App\Utility\Gpg;
use Cake\Core\Configure;

class License
{
    /**
     * The license
     * @var string
     */
    protected $_license = null;

    /**
     * The license information
     * @var array
     */
    protected $_info = null;

    /**
     * Gpg object.
     */
    protected $_gpg = null;

    /**
     * License constructor.
     * @param string $license The license as issued by passbolt
     */
    public function __construct($license)
    {
        $this->_license = $license;
        $this->_gpg = new Gpg();
    }

    /**
     * Check if the license is valid.
     * @return bool
     * @throws \Exception If the license format is not valid
     * @throws \Exception If the gpg public license key cannot be imported into the keyring
     * @throws \Exception If the license cannot be verified
     * @throws \Exception If the license is expired
     */
    public function validate()
    {
        $licenseInfo = $this->getInfo();

        $expiryDate = $licenseInfo['expiry'];
        if (date("Y-m-d") > $expiryDate) {
            throw new \Exception(__('The license is expired.'));
        }

        return true;
    }

    /**
     * Extract the license info
     * @return array The license info. On failure, this function returns FALSE.
     * @throws \Exception If the license format is not valid
     * @throws \Exception If the gpg public license key cannot be imported into the keyring
     * @throws \Exception If the license cannot be verified
     */
    public function getInfo()
    {
        if (is_null($this->_info)) {
            $this->_info = $this->_parse();
        }

        return $this->_info;
    }

    /**
     * Parse the license.
     *
     * @return array The license info. On failure, this function returns FALSE.
     * @throws \Exception If the license format is not valid
     */
    protected function _parse()
    {
        $armoredSignedLicense = $this->getArmoredSignedLicense();
        $licenseInfoStr = $this->_verifySignature($armoredSignedLicense);
        $licenseInfo = \json_decode($licenseInfoStr, true);
        if (is_null($licenseInfo)) {
            throw new \Exception(__('The license cannot be verified.'));
        }

        return $licenseInfo;
    }

    /**
     * Get the armored license.
     *
     * @return string The armored signed license
     * @throws \Exception If the license format is not valid
     */
    public function getArmoredSignedLicense()
    {
        $armoredSignedLicense = base64_decode($this->_license);
        if (!$armoredSignedLicense) {
            throw new \Exception(__('The license format is not valid.'));
        }

        $isSignedMessage = $this->_gpg->isParsableArmoredSignedMessageRule($armoredSignedLicense);
        if (!$isSignedMessage) {
            throw new \Exception(__('The license format is not valid.'));
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
            throw new \Exception(__('The license cannot be verified.'));
        }

        return $licenseInfo;
    }
}
