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
 * @since         5.1.1
 */
namespace Passbolt\UserGpgKeyPolicies\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class UserGpgKeyPoliciesSettingsForm extends Form
{
    /**
     * The rsa key type
     * @type string
     */
    public const KEY_TYPE_RSA = 'rsa';

    /**
     * The eddsa key type
     * @type string
     */
    public const KEY_TYPE_EDDSA = 'EdDSA';

    /**
     * The allowed key types.
     * @type array
     */
    public const ALLOWED_KEY_TYPES = [self::KEY_TYPE_RSA, self::KEY_TYPE_EDDSA];

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('preferred_key_type', 'string');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(
                'preferred_key_type',
                true,
                __('The "preferred_key_type" property is required.')
            )
            ->inList('key_type', self::ALLOWED_KEY_TYPES, __(
                'The "preferred_key_type" property should be one of the following: {0}.',
                implode(', ', self::ALLOWED_KEY_TYPES)
            ));

        return $validator;
    }
}
