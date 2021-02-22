<?php
declare(strict_types=1);

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

use Cake\Utility\Hash;
use Passbolt\License\Form\LicenseKeyDataForm;
use Passbolt\License\Form\LicenseKeyForm;

class LicenseKey
{
    /**
     * The license form
     *
     * @var string
     */
    protected $_licenseKeyForm = null;

    /**
     * The license form details
     *
     * @var string
     */
    protected $_licenseKeyDataForm = null;

    /**
     * Key ascii.
     *
     * @var null
     */
    protected $_keyAscii = null;

    /**
     * License info.
     *
     * @var array
     */
    protected $_data = null;

    /**
     * License constructor.
     *
     * @param string $licenseKeyAscii The ascii license as issued by passbolt
     */
    public function __construct(string $licenseKeyAscii)
    {
        $this->_keyAscii = $licenseKeyAscii;
        $this->_licenseKeyForm = new LicenseKeyForm();
        $this->_licenseKeyDataForm = new LicenseKeyDataForm();
    }

    /**
     * Check if the license format is valid.
     *
     * @return bool
     */
    public function validateFormat()
    {
        $validateFormat = $this->_licenseKeyForm->execute(['key_ascii' => $this->_keyAscii]);

        return $validateFormat;
    }

    /**
     * Check if the license data are valid (valid number of users, valid expiry date, etc..)
     *
     * @return bool true or false.
     * @throws \Exception $e if the data cannot be retrieved
     */
    public function validateData(): bool
    {
        $keyData = $this->getData();
        $validateDetails = $this->_licenseKeyDataForm->execute($keyData);

        return $validateDetails;
    }

    /**
     * Validate whether a key format and data is valid.
     *
     * @throws \Exception if data does not validate
     * @return bool
     */
    public function validate(): bool
    {
        $validFormat = $this->validateFormat();
        if (!$validFormat) {
            return false;
        }

        $validData = $this->validateData();

        return $validData;
    }

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        if (!empty($this->_licenseKeyForm->getErrors())) {
            return $this->_licenseKeyForm->getErrors();
        }

        if (!empty($this->_licenseKeyDataForm->getErrors())) {
            return $this->_licenseKeyDataForm->getErrors();
        }

        return [];
    }

    /**
     * Get main error message from the validation errors.
     *
     * @return string last error message.
     */
    public function getFirstErrorMessage(): string
    {
        $errors = $this->getErrors();
        if (!empty($errors)) {
            $firstColumnKey = array_keys($errors)[0];
            $secondColumnKey = array_keys($errors[$firstColumnKey])[0];

            return $errors[$firstColumnKey][$secondColumnKey];
        }

        return '';
    }

    /**
     * Extract the license info
     *
     * @param string|null $name name of the data to be retrieved. (if null is provided, then all data will be returned)
     * @return array The license info. On failure, this function returns FALSE.
     * @throws \Exception If the license format is not valid
     * @throws \Exception If the gpg public license key cannot be imported into the keyring
     * @throws \Exception If the license cannot be verified
     */
    public function getData(?string $name = null): array
    {
        if (empty($this->_data)) {
            $this->_data = $this->_licenseKeyForm->parse($this->_keyAscii);
        }

        if ($name !== null) {
            return Hash::get($this->_data, $name);
        }

        return $this->_data;
    }
}
