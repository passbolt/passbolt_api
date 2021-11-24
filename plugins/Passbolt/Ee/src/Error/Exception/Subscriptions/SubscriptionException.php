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

use App\Error\Exception\ExceptionWithErrorsDetailInterface;
use Cake\Core\Exception\CakeException;

class SubscriptionException extends CakeException implements ExceptionWithErrorsDetailInterface
{
    /**
     * @var mixed $data
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param mixed $data The failing subscription key data.
     * @param int|null $code The code of the error, is also the HTTP status code for the error.
     * @param \Exception|null $previous the previous exception.
     */
    public function __construct(
        string $message,
        $data = null,
        ?int $code = null,
        ?\Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        if (!isset($this->data)) {
            return null;
        }

        return ['data' => $this->data];
    }
}
