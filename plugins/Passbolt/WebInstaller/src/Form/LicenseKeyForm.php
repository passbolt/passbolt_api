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
namespace Passbolt\WebInstaller\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\License\Utility\LicenseKey;

class LicenseKeyForm extends Form
{
    private $_lastError = null;

    /**
     * License key schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('license_key', 'text');
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('license_key', 'create', __('A subscription key is required.'))
            ->notEmpty('license_key', __('A subscription key is required.'))
            ->add('license_key', 'is_valid_license', [
                'last' => true,
                'rule' => [$this, 'checkLicenseIsValid'],
                'message' => 'The license format is not valid.',
            ]);

        return $validator;
    }

    /**
     * Check if the license is valid.
     *
     * @param string $value The license
     * @param array|null $context not in use
     * @return string|bool
     */
    public function checkLicenseIsValid(string $value, array $context = null)
    {
        $license = new LicenseKey($value);
        $valid = $license->validate();
        $this->_lastError = $license->getFirstErrorMessage();

        return $valid;
    }

    /**
     * Get last error details.
     * @return string|null
     */
    public function getLastErrorDetails()
    {
        return $this->_lastError;
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
