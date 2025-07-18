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

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\Scim\Service\ScimBaseSettingsService;
use Passbolt\Scim\Service\ScimSetSettingsService;

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
            ->notEmptyString('secret_token', __('The secret token should not be empty.'))
            ->add('secret_token', 'correctFormat', [
                'rule' => function ($value, array $context) {
                    if (is_string($value) && str_starts_with($value, ScimSetSettingsService::SCIM_SECRET_TOKEN_PREFIX) && strlen($value) >= 39) {
                        return true;
                    }

                    return __('The secret token format is incorrect.');
                }
            ]);

        $validator
            ->uuid('scim_user_id', __('The identifier of the default user should be a valid UUID.'))
            ->add('scim_user_id', 'activeAndEnabled', [
                'rule' => function ($value, array $context) {
                    $user = TableRegistry::getTableLocator()->get('Users')
                        ->find('active')
                        ->find('notDisabled')
                        ->where(['Users.id' => $value])
                        ->first();

                    if ($user) {
                        return true;
                    }

                    return __('The user is not active, disabled or does not exist.');
                }
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

        $validator
            ->add('setting_id', 'ensureEmpty', [
                'rule' => function ($value, array $context) {
                    return __('The Setting ID cannot be passed on update.');
                }
            ]);

        return $validator;
    }

    /**
     * Extended validation to include checking for setting_id duplicates on creation
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationExtended(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

        $validator
            ->notEmptyString('setting_id', __('The ID for the SCIM settings should not be empty.'))
            ->uuid('setting_id', __('The ID for the SCIM settings should be a valid UUID.'))
            ->add('setting_id', 'notInUse', [
                'rule' => function ($value, array $context) {
                    $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
                    $query = $OrganizationSettings->find();
                    $where = [
                        $OrganizationSettings->aliasField('property') => ScimBaseSettingsService::SCIM_SETTINGS_PROPERTY_NAME,
                        $query->newExpr()->like($OrganizationSettings->aliasField('value'), "%$value%")
                    ];
                    $result = $query->where($where)->all();

                    if ($result->count() === 0) {
                        return true;
                    }

                    return __('The ID for the SCIM settings is already in use.');
                }
            ]);

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
