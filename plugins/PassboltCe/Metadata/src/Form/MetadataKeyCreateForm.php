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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Form;

use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Model\Validation\IsNullOnCreateRule;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class MetadataKeyCreateForm extends Form
{
    /**
     * Email configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('fingerprint', 'string')
            ->addField('armored_key', ['type' => 'string']);
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
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required.'))
            ->maxLength('fingerprint', 51, __('A fingerprint should not be greater than 51 characters.'))
            ->notEmptyString('fingerprint', __('A fingerprint should not be empty.'))
            ->alphaNumeric('fingerprint', __('The fingerprint should be a valid alphanumeric string.'))
            ->add('fingerprint', 'custom', new IsValidFingerprintValidationRule());

        $validator
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'));

        $validator
            ->requirePresence('metadata_private_keys', 'create', __('Private keys are required.'))
            ->array('metadata_private_keys')
            ->hasAtLeast('metadata_private_keys', 1, __('Need at least one metadata private key.'))
            ->addNestedMany('metadata_private_keys', $this->getMetadataPrivateKeysValidator());

        $validator
            ->allowEmptyDateTime('expired')
            ->add('expired', 'isNullOnCreate', new IsNullOnCreateRule());

        $validator
            ->allowEmptyDateTime('deleted')
            ->add('deleted', 'isNullOnCreate', new IsNullOnCreateRule());

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function getMetadataPrivateKeysValidator(): Validator
    {
        $validator = new Validator();

        $validator
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->allowEmptyString('user_id');

        $validator
            ->requirePresence('data', 'create', __('A data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
            ->ascii('data', __('The data should be a valid ASCII string.'));

        return $validator;
    }
}
