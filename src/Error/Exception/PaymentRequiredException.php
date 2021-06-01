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
 * @since         3.0.0
 */
namespace App\Error\Exception;

use Cake\Http\Exception\HttpException;

/**
 * Represents an HTTP 402 error.
 */
class PaymentRequiredException extends HttpException implements ExceptionWithErrorsDetailInterface
{
    /**
     * @var mixed $data
     */
    protected $data = '';

    /**
     * Constructor
     *
     * @param string|null $message If no message is given 'Forbidden' will be the message
     * @param mixed|null $data data to return to the end user as information
     * @param int|null $code status code, defaults to 402
     * @param \Exception|null $previous The previous exception.
     */
    public function __construct($message = null, $data = null, ?int $code = null, $previous = null)
    {
        if (empty($message)) {
            $message = 'Payment Required';
        }
        $code = $code ?? 402;
        parent::__construct($message, $code, $previous);
        $this->data = $data ?? '';
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->data;
    }
}
