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
 * @since         3.1.0
 */
namespace Passbolt\Ee\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Class SubscriptionKeyDtoForm
 *
 * @package Passbolt\Ee\Form
 *
 * This form is used to validate the subscription key data AFTER the signature verification and
 * after the content have been been parsed from its JSON representation.
 *
 * This form is only used to validate the data, it does not actually save the data in the database
 * on execute().
 */
class SubscriptionKeyDtoForm extends Form
{
    /**
     * Subscription key details schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
        return $schema
            ->addField('customer_id', 'string')
            ->addField('subscription_id', 'string')
            ->addField('users', 'string')
            ->addField('email', 'string')
            ->addField('created', 'string')
            ->addField('expiry', 'string');
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
            ->requirePresence('users', 'create', __('A users quantity is required.'))
            ->notEmptyString('users', __('The users quantity should not be empty.'))
            ->add('users', 'is_within_range', [
                'last' => true,
                'rule' => [$this, 'checkUsersLimitIsInRange'],
                'message' => __('The users limit is exceeded.'),
            ])
            ->requirePresence('created', 'create', __('A creation date is required.'))
            ->notEmptyDateTime('created', __('A creation date is required.'))
            ->add('created', 'is_created_in_past', [
                'rule' => [$this, 'checkCreatedInPast'],
                'message' => __('The key should have been created in the past.'),
            ])
            ->requirePresence('expiry', 'create', __('An expiry date is required.'))
            ->notEmptyDate('expiry', __('An expiry date is required.'))
            ->add('expiry', 'is_not_expired', [
                'last' => true,
                'rule' => [$this, 'checkNotExpired'],
                'message' => __('The subscription is expired.'),
            ])
            ->requirePresence('email', 'create', __('An email is required.'))
            ->email('email', false, __('The email should be a valid email address.'))
            ->requirePresence('customer_id', 'create', __('A customer identifier is required.'))
            ->ascii('customer_id', 'The format of the customer_id is not valid.')
            ->requirePresence('subscription_id', 'create', __('A subscription identifier is required.'))
            ->ascii('subscription_id', __('The format of the subscription id is not valid.'));

        return $validator;
    }

    /**
     * Check if a subscription key details is within the range of users allowed.
     *
     * @param string $value The subscription key details
     * @param array|null $context not in use
     * @return bool
     */
    public function checkUsersLimitIsInRange($value, ?array $context = null): bool
    {
        try {
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = TableRegistry::getTableLocator()->get('Users');

            return $Users->findActive()->count() <= $value;
        } catch (\Exception $e) {
            // Return true in case of exception (in this case, it will mainly be a database exception).
            // This can happen when Passbolt is not configured and should not prevent subscription validation.
            return true;
        }
    }

    /**
     * Check that the subscription has been created in the past.
     *
     * @param string $value value
     * @param array|null $context context
     * @return bool
     */
    public function checkCreatedInPast(string $value, ?array $context = null): bool
    {
        $date = strtotime($value);
        if ($date === false) {
            return false;
        }

        return time() >= $date;
    }

    /**
     * Check if the subscription is not expired.
     *
     * @param string $value The subscription
     * @param array|null $context not in use
     * @return bool
     */
    public function checkNotExpired(string $value, ?array $context = null): bool
    {
        $date = strtotime($value);
        if ($date === false) {
            return false;
        }

        return time() <= $date;
    }

    /**
     * Execute implementation.
     *
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
