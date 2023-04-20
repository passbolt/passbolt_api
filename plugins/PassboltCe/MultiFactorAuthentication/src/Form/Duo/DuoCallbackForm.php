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
 * @since         3.11.0
 */
namespace Passbolt\MultiFactorAuthentication\Form\Duo;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class DuoCallbackForm extends Form
{
    /**
     * Callback configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('state', 'string')
            ->addField('duo_code', ['type' => 'string'])
            ->addField('error', ['type' => 'string'])
            ->addField('error_description', ['type' => 'string']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('state', true, __('A state is required.'))
            ->utf8('state', __('The state should be a valid BMP-UTF8 string.'))
            ->notEmptyString('state', __('The state should not be empty.'));

        $validator
            ->requirePresence('duo_code', true, __('A duo code is required.'))
            ->utf8('state', __('The duo code state should be a valid BMP-UTF8 string.'))
            ->notEmptyString('state', __('The duo code should not be empty.'));

        $validator
            ->utf8('error', __('The error description should be a valid BMP-UTF8 string.'))
            ->allowEmptyString('error');

        $validator
            ->utf8('error_description', __('The error description state should be a valid BMP-UTF8 string.'))
            ->allowEmptyString('error_description');

        return $validator;
    }
}
