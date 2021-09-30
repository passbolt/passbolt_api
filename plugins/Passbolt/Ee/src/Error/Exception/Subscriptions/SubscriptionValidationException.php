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

use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;

/**
 * Class SubscriptionValidationException
 *
 * @package Passbolt\Ee\Error\Exception\Subscriptions
 *
 * Used to throw exceptions related to the content of the subscription key
 * for example, the number of user, the duration, etc.
 */
class SubscriptionValidationException extends SubscriptionException
{
    /**
     * @var \Passbolt\Ee\Model\Dto\SubscriptionKeyDto $keyDto
     */
    protected $keyDto;

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param \Passbolt\Ee\Model\Dto\SubscriptionKeyDto|null $dto The failing subscription key data.
     * @param int|null $code The code of the error, is also the HTTP status code for the error.
     * @param \Exception|null $previous the previous exception.
     */
    public function __construct(
        string $message,
        ?SubscriptionKeyDto $dto = null,
        ?int $code = null,
        ?\Exception $previous = null
    ) {
        $code = $code ?? 402;
        $data = isset($dto) ? $dto->toArray() : '';
        parent::__construct($message, $data, $code, $previous);
        $this->keyDto = $dto;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        if (!is_object($this->keyDto)) {
            return null;
        }

        return $this->keyDto->toArray();
    }

    /**
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto|null
     */
    public function getDto()
    {
        return $this->keyDto;
    }
}
