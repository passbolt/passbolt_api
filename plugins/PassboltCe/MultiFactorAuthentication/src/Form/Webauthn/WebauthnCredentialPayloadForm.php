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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Form\Webauthn;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class WebauthnCredentialPayloadForm extends Form
{
    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('id', ['type' => 'string'])
            ->addField('rawId', ['type' => 'string'])
            ->addField('type', ['type' => 'string'])
            ->addField('response', ['type' => 'array']);
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->requirePresence('id', true, __('A credential id is required.'))
            ->notEmptyString('id', __('The credential id should not be empty.'))
            ->requirePresence('rawId', true, __('A credential raw id is required.'))
            ->notEmptyString('rawId', __('The credential raw id should not be empty.'))
            ->requirePresence('type', true, __('A credential type is required.'))
            ->inList('type', ['public-key'], __('The credential type should be "public-key".'))
            ->requirePresence('response', true, __('A credential response is required.'))
            ->notEmptyArray('response', __('The credential response should not be empty.'));
    }
}
