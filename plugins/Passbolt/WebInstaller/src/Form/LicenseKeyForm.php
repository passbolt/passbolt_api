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
            ->requirePresence('license_key', 'create', __('A license key is required.'))
            ->notEmpty('license_key', __('A license key is required.'))
            ->ascii('license_key', __('The license_key is a valid ascii text.'));

        // TODO add key validation.

        return $validator;
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
