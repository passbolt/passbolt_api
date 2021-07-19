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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\Ee\Service\SubscriptionKeyValidateService;

class SubscriptionForm extends Form
{
    /**
     * @var string
     */
    private $_lastError;

    /**
     * License key schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
        return $schema
            ->addField('subscription_key', 'text');
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
            ->requirePresence('subscription_key', 'create', __('A subscription key is required.'))
            ->notEmptyString('subscription_key', __('The subscription key should not be empty.'))
            ->add('subscription_key', 'is_valid_subscription', [
                'last' => true,
                'rule' => [$this, 'checkSubscriptionIsValid'],
                'message' => __('The subscription format is not valid.'),
            ]);

        return $validator;
    }

    /**
     * Check if the subscription is valid.
     *
     * @param string $value The license
     * @param array|null $context not in use
     * @return string|bool
     */
    public function checkSubscriptionIsValid(string $value, ?array $context = null)
    {
        try {
            $service = new SubscriptionKeyValidateService();
            $service->validate($value);
        } catch (\Exception $e) {
            $this->_lastError = $e->getMessage();

            return false;
        }

        return true;
    }

    /**
     * Get last error details.
     *
     * @return string|null
     */
    public function getLastErrorDetails()
    {
        return $this->_lastError;
    }

    /**
     * Execute implementation.
     *
     * @param array $data formdata
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
