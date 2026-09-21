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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;

class OfflineSettingsDefaultsForm extends Form implements OfflineSettingsFormInterface
{
    /**
     * Defaults values per field.
     */
    private const DEFAULTS = [
        'max_session_duration' => OfflineSettingsDto::DEFAULT_MAX_SESSION_DURATION,
        'data_retention_period' => OfflineSettingsDto::DEFAULT_DATA_RETENTION_PERIOD,
        'max_items' => OfflineSettingsDto::DEFAULT_MAX_ITEMS,
    ];

    /**
     * @param \Cake\Form\Schema $schema Schema.
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        foreach (array_keys(self::DEFAULTS) as $field) {
            $schema->addField($field, ['type' => 'integer']);
        }

        return $schema;
    }

    /**
     * @param \Cake\Validation\Validator $validator Validator.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        foreach (self::DEFAULTS as $field => $default) {
            $validator
                ->requirePresence($field, true, __('The setting is required.'))
                ->integer($field, __('The setting should be a valid integer.'))
                ->add($field, 'default_only', [
                    'rule' => fn(mixed $value): bool => $value === $default,
                    'message' => __(
                        'This setting is not customizable on this edition, it should be {0}.',
                        $default,
                    ),
                ]);
        }

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        return parent::execute($this->sanitizeData($data), $options);
    }

    /**
     * @inheritDoc
     */
    public function getSettings(): array
    {
        return [
            'max_session_duration' => (int)$this->getData('max_session_duration'),
            'data_retention_period' => (int)$this->getData('data_retention_period'),
            'max_items' => (int)$this->getData('max_items'),
        ];
    }

    /**
     * @param array $data Data to sanitize.
     * @return array<string, mixed>
     */
    protected function sanitizeData(array $data): array
    {
        $sanitized = [];
        foreach (array_keys(self::DEFAULTS) as $field) {
            $value = $data[$field] ?? null;
            if (is_string($value)) {
                $value = filter_var($value, FILTER_VALIDATE_INT);
            }
            $sanitized[$field] = $value;
        }

        return $sanitized;
    }
}
