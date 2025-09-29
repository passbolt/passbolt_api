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

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class MetadataKeysSettingsForm extends Form
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
            ->addField('allow_usage_of_personal_keys', ['type' => 'boolean'])
            ->addField('zero_knowledge_key_share', ['type' => 'boolean']);
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
            ->boolean('allow_usage_of_personal_keys', __('The setting should be a valid boolean.'))
            ->requirePresence('allow_usage_of_personal_keys', true, __('The setting is required.'));

        $validator
            ->boolean('zero_knowledge_key_share', __('The setting should be a valid boolean.'))
            ->requirePresence('zero_knowledge_key_share', true, __('The setting is required.'));

        return $validator;
    }

    /**
     * Default validation rules + validation for metadata private keys
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationWithMetadataPrivateKeys(Validator $validator): Validator
    {
        return $this
            ->validationDefault($validator)
            ->requirePresence('metadata_private_keys', true, __('The metadata private keys is required.')) // phpcs:ignore;
            ->notEmptyArray('metadata_private_keys', __('The metadata private keys should not be empty.')) // phpcs:ignore;
            ->hasAtLeast('metadata_private_keys', 1, __('Need at least one metadata private key.')) // phpcs:ignore;
            // TODO: Accept multiple metadata private keys?
            ->hasAtMost('metadata_private_keys', 1, __('Need at most one metadata private key.')) // phpcs:ignore;
            ->addNestedMany('metadata_private_keys', $this->getServerMetadataPrivateKeysValidator()); // phpcs:ignore;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    private function getServerMetadataPrivateKeysValidator(): Validator
    {
        $validator = new Validator();

        $validator
            ->requirePresence('metadata_key_id', 'create', __('A metadata key identifier is required.'))
            ->uuid('metadata_key_id', __('The metadata key identifier should be a valid UUID.'));

        $validator
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->allowEmptyString('user_id')
            ->add('user_id', 'onlyNullAllowed', [
                'rule' => function ($value) {
                    return $value === null;
                },
                'message' => __('The user identifier should be null.'),
            ]);

        $validator
            ->requirePresence('data', 'create', __('A data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
            ->ascii('data', __('The data should be a valid ASCII string.'));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->sanitizeData($data);

        return parent::execute($data, $options);
    }

    /**
     * @param array $data Data to sanitize
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        return [
            'allow_usage_of_personal_keys' => $data['allow_usage_of_personal_keys'] ?? null,
            'zero_knowledge_key_share' => $data['zero_knowledge_key_share'] ?? null,
            'metadata_private_keys' => $data['metadata_private_keys'] ?? null,
        ];
    }
}
