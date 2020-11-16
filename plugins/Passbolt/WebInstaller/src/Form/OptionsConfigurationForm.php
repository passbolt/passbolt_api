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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class OptionsConfigurationForm extends Form
{
    /**
     * Options configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('full_base_url', 'string')
            ->addField('public_registration', ['type' => 'string'])
            ->addField('force_ssl', ['type' => 'string']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('full_base_url', 'create', __('A full base url is required.'))
            ->notEmptyString('full_base_url', __('A full base url is required.'))
            ->utf8('full_base_url', __('The full base url is not a valid utf8 string.'));

        $validator
            ->requirePresence('public_registration', 'create', __('A public registration value is required.'))
            ->boolean('public_registration');

        $validator
            ->requirePresence('force_ssl', 'create', __('A force ssl value is required.'))
            ->boolean('force_ssl');

        return $validator;
    }

    /**
     * Execute implementation.
     *
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
