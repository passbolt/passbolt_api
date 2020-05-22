<?php
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
 * @since         2.0.0
 */
namespace Passbolt\License\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class LicenseKeyDataForm extends Form
{
    /**
     * License key details schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('customer_id', 'string')
            ->addField('plan_id', 'string')
            ->addField('users', 'string')
            ->addField('created', 'string')
            ->addField('expiry', 'string');
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('users', 'create', __('A users quantity is required.'))
            ->notEmpty('users', __('A users quantity is required.'))
            ->add('users', 'is_within_range', [
                'last' => true,
                'rule' => [$this, 'checkUsersLimitIsInRange'],
                'message' => 'The users limit is exceeded.',
            ])
            ->requirePresence('created', 'create', __('A creation date is required.'))
            ->notEmpty('created', __('A creation date is required.'))
            ->add('created', 'is_created_in_past', [
                'rule' => [$this, 'checkCreatedInPast'],
                'message' => 'The key should have been created in the past.',
            ])
            ->requirePresence('expiry', 'create', __('An expiry date is required.'))
            ->notEmpty('expiry', __('An expiry date is required.'))
            ->add('expiry', 'is_not_expired', [
                'last' => true,
                'rule' => [$this, 'checkNotExpired'],
                'message' => 'The license is expired.',
            ]);

        return $validator;
    }

    /**
     * Check if a license key details is within the range of users allowed.
     *
     * @param string $value The license key details
     * @param array $context not in use
     * @return bool
     */
    public function checkUsersLimitIsInRange(string $value, array $context = null)
    {
        try {
            $users = TableRegistry::getTableLocator()->get('Users');

            $usersQty = $users->findActive()->count();
            if ($usersQty > $value) {
                return false;
            }
        } catch (\Exception $e) {
            // Return true in case of exception (in this case, it will mainly be a database exception).
            // This can happen when Passbolt is not configured and should not prevent licence validation.
            return true;
        }

        return true;
    }

    /**
     * Check that the license has been created in the past.
     *
     * @param string $value value
     * @param array $context context
     *
     * @return bool
     */
    public function checkCreatedInPast(string $value, array $context)
    {
        if (time() < strtotime($value)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the license is not expired.
     *
     * @param string $value The license
     * @param array|null $context not in use
     *
     * @return bool
     */
    public function checkNotExpired(string $value, array $context = null)
    {
        if (time() > strtotime($value)) {
            return false;
        }

        return true;
    }

    /**
     * Execute implementation.
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
