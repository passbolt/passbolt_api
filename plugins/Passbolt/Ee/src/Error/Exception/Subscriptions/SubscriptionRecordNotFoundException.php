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
namespace Passbolt\Ee\Error\Exception\Subscriptions;

/**
 * Class SubscriptionRecordNotFoundException
 *
 * @package Passbolt\Ee\Error\Exception\Subscriptions
 *
 * Used to throw an error if the subscription key details are not found in the database
 */
class SubscriptionRecordNotFoundException extends SubscriptionException
{
    public const MESSAGE = 'Subscription key could not be found.';

    /**
     * SubscriptionRecordNotFoundException constructor.
     *
     * @param string|null $msg message
     */
    public function __construct(?string $msg = null)
    {
        $msg = $msg ?? __('Subscription key could not be found.');
        parent::__construct($msg);
    }
}
