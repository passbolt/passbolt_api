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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Form\Upgrade;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Entity\MetadataKey;

class MetadataBatchUpgradeForm extends Form
{
    /**
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('id', ['type' => 'string'])
            ->addField('metadata_key_id', ['type' => 'string'])
            ->addField('metadata_key_type', ['type' => 'string'])
            ->addField('metadata', ['type' => 'string'])
            ->addField('modified', ['type' => 'datetime'])
            ->addField('modified_by', ['type' => 'string']);
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
            ->requirePresence('id', 'create', __('An identifier is required.'))
            ->notEmptyString('id', __('The identifier should not be empty.'))
            ->uuid('id', __('The identifier should be a valid UUID.'));

        $validator
            ->requirePresence('metadata_key_id', 'create', __('A metadata key identifier is required.'))
            ->notEmptyString('metadata_key_id', __('The metadata key identifier should not be empty.'))
            ->uuid('metadata_key_id', __('The metadata key identifier should be a valid UUID.'));

        $validator
            ->requirePresence('metadata_key_type', 'create', __('A metadata key type is required.'))
            ->notEmptyString('metadata_key_type', __('The metadata key type should not be empty.'))
            ->add('metadata_key_type', 'is_not_shared_key', [
                'rule' => function ($value, $context) {
                    return $value === MetadataKey::TYPE_SHARED_KEY;
                },
                'message' => __('The metadata key type should be shared key.'),
            ]);

        $validator
            ->requirePresence('metadata', 'create', __('A metadata is required.'))
            ->notEmptyString('metadata', __('The metadata should not be empty.'));

        $validator
            ->requirePresence('modified', 'create', __('A modified date is required.'))
            ->notEmptyString('modified', __('The modified date should not be empty.'))
            ->dateTime('modified', [Validation::DATETIME_ISO8601], __('The modified date should be a valid ISO 80601 date.')); // phpcs:ignore;

        $validator
            ->requirePresence('modified_by', 'create', __('A modified by is required.'))
            ->notEmptyString('modified_by', __('The modified by should not be empty.'))
            ->uuid('modified_by', __('The modified by should be a valid UUID.'));

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
            'id' => $data['id'] ?? null,
            'metadata_key_id' => $data['metadata_key_id'] ?? null,
            'metadata_key' => $data['metadata_key'] ?? null,
            'metadata_key_type' => $data['metadata_key_type'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'modified' => $data['modified'] ?? null,
            'modified_by' => $data['modified_by'] ?? null,
        ];
    }
}
