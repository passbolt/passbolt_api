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
namespace Passbolt\Scim\Form\Settings;

use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\Scim\Model\Validation\ScimTokenFormatRule;

class ScimSettingsForm extends Form
{
    /**
     * Database configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('id', 'string')
            ->addField('secret_token', 'string')
            ->addField('scim_user_id', 'string')
            ->addField('setting_id', 'string');
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
            ->add('secret_token', 'correctFormat', new ScimTokenFormatRule());

        $validator
            ->uuid('scim_user_id', __('The identifier of the default user should be a valid UUID.'))
            ->add('scim_user_id', 'activeAndEnabled', [
                'rule' => function (string $userId) {
                    if (!Validation::uuid($userId)) {
                        // Return true as the uuid validator will fail.
                        // Skip the rest of the present rule as this would throw errors on Postgres
                        return true;
                    }
                    $user = TableRegistry::getTableLocator()->get('Users')
                        ->find('active')
                        ->find('notDisabled')
                        ->where(['Users.id' => $userId])
                        ->first();

                    if ($user) {
                        return true;
                    }

                    return __('The user is not active, disabled or does not exist.');
                },
            ]);

        return $validator;
    }

    /**
     * Update validation to ensure setting_id is not passed
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationUpdate(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

        $validator->allowEmptyString('secret_token');
        $validator
            ->allowEmptyString('setting_id')
            ->add('setting_id', 'ensureEmpty', [
                'rule' => function ($value, array $context) {
                    return __('The Setting ID cannot be passed on update.');
                },
            ]);

        return $validator;
    }

    /**
     * Extended validation for setting id
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationExtended(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

        $validator->notEmptyString('secret_token', __('The secret token should not be empty.'));

        $validator
            ->notEmptyString('setting_id', __('The ID for the SCIM settings should not be empty.'))
            ->uuid('setting_id', __('The ID for the SCIM settings should be a valid UUID.'));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $sanitizedData = $this->sanitizeData($data);

        return parent::execute($sanitizedData, $options);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        if (isset($data['secret_token'])) {
            /** @var int $cost */
            $cost = Configure::read('passbolt.plugins.scim.security.secretToken.cost', 12);
            $this->_data['secret_token'] = password_hash(
                $data['secret_token'],
                PASSWORD_BCRYPT,
                ['cost' => $cost]
            );
        }

        return true;
    }

    /**
     * @param array $data Data to sanitize
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        return [
            'setting_id' => $data['setting_id'] ?? null,
            'secret_token' => $data['secret_token'] ?? null,
            'scim_user_id' => $data['scim_user_id'] ?? null,
        ];
    }
}
