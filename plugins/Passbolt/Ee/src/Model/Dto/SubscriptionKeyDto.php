<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.1.0
 */
namespace Passbolt\Ee\Model\Dto;

use Cake\I18n\FrozenDate;

class SubscriptionKeyDto
{
    /**
     * @var string $customerId customer uuid
     */
    public $customerId;

    /**
     * @var string $subscriptionId subscription uuid
     */
    public $subscriptionId;

    /**
     * @var int $users number of user the subscription is valid for
     */
    public $users;

    /**
     * @var string $email the subscription contact email
     */
    public $email;

    /**
     * @var \Cake\I18n\FrozenDate $expiry the subscription expiry date
     */
    public $expiry;

    /**
     * @var \Cake\I18n\FrozenDate $created the subscription creation date
     */
    public $created;

    /**
     * @var string $data Base64 encoded subscription, the original subscription key
     */
    public $data;

    /**
     * SubscriptionKeyDto constructor.
     *
     * @param string $data key string as provided by sales
     * @param string $customerId customer id as provided on invoice
     * @param string $subscriptionId subscription id as provided on invoice
     * @param int $users number of users
     * @param string $email email linked to the subscription in the billing system
     * @param \Cake\I18n\FrozenDate $expiry expiry date
     * @param \Cake\I18n\FrozenDate $created creation date
     */
    final public function __construct(
        string $data,
        string $customerId,
        string $subscriptionId,
        int $users,
        string $email,
        FrozenDate $expiry,
        FrozenDate $created
    ) {
        $this->data = $data;
        $this->customerId = $customerId;
        $this->subscriptionId = $subscriptionId;
        $this->users = $users;
        $this->email = $email;
        $this->expiry = $expiry;
        $this->created = $created;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'customer_id' => $this->customerId,
            'subscription_id' => $this->subscriptionId,
            'users' => $this->users,
            'email' => $this->email,
            'expiry' => $this->expiry->i18nFormat('yyyy-MM-dd'),
            'created' => $this->created->i18nFormat('yyyy-MM-dd'),
            'data' => $this->data,
        ];
    }

    /**
     * @param array $key subscription key data
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     */
    public static function createFromArray(array $key)
    {
        return new static(
            $key['data'] ?? '',
            $key['customer_id'] ?? '',
            $key['subscription_id'] ?? '',
            $key['users'] ?? 0,
            $key['email'] ?? '',
            isset($key['expiry']) ? new FrozenDate($key['expiry']) : FrozenDate::now(),
            isset($key['created']) ? new FrozenDate($key['created']) : FrozenDate::now(),
        );
    }
}
