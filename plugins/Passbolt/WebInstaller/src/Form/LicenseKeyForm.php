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
namespace Passbolt\WebInstaller\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\License\Utility\License;

class LicenseKeyForm extends Form
{
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
            ->add('license_key', ['custom' => [
                'rule' => [$this, 'isValidLicenseFormat'],
                'message' => __('The license format is not valid.')
            ]]);

        return $validator;
    }

    /**
     * Check if a license is in a valid format.
     *
     * @param string $value The license
     * @param array $context not in use
     * @return bool
     */
    public function isValidLicenseFormat(string $value, array $context = null)
    {
        $license = new License($value);
        try {
            $license->getArmoredSignedLicense();
        } catch (\Exception $e) {
            return false;
        }

        return true;
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
