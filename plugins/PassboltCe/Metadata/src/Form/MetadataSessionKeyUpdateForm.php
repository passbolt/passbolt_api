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

use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

class MetadataSessionKeyUpdateForm extends Form
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
            ->addField('modified', ['type' => 'datetime'])
            ->addField('data', ['type' => 'string']);
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
            ->requirePresence('data', 'create', __('The data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
            ->ascii('data', __('The data should be a valid ASCII string.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        $validator
            ->dateTime('modified', [Validation::DATETIME_ISO8601], __('The modified date should be a valid ISO 80601 date.'))
            ->requirePresence('modified', 'create', __('A modified date is required.'))
            ->notEmptyDateTime('modified', __('The modified date should not be empty.'));

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
            'data' => $data['data'] ?? null,
            'modified' => $data['modified'] ?? null,
        ];
    }
}
