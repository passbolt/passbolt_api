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
 * Class SubscriptionSignatureException
 *
 * @package Passbolt\Ee\Error\Exception\Subscriptions
 *
 * Used to throw an error related to the verification of the OpenPGP signature
 * associated with the subscription key data
 */
class SubscriptionSignatureException extends SubscriptionException
{
    public const MESSAGE = 'The subscription content or signature is not valid.';

    /**
     * SubscriptionSignatureException constructor.
     *
     * @param string $keyString key with the issue
     * @param string|null $msg message
     */
    public function __construct(string $keyString, ?string $msg = null)
    {
        $msg = $msg ?? self::MESSAGE;
        parent::__construct($msg, $keyString);
    }
}
