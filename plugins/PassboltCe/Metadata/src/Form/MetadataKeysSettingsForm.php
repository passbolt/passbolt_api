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
        ];
    }
}
