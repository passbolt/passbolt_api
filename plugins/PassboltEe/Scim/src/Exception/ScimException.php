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
 * @since         4.1.0
 */
namespace Passbolt\Scim\Exception;

use Cake\Http\Exception\HttpException;
use Throwable;

/**
 * Exception raised when a validation rule is not satisfied in a Form.
 */
class ScimException extends HttpException
{
    public const SCIM_TYPE_UNIQUENESS = 'uniqueness';
    public const SCIM_TYPE_INVALID_VALUE = 'invalidValue';

    public const SCIM_TYPE_INVALID_FILTER = 'invalidFilter';

    /**
     * Scim error type
     *
     * @var string|null
     */
    protected ?string $scimType = null;

    /**
     * Constructor
     *
     * @param string $message Detailed Message
     * @param int|null $code Exception Code
     * @param \Throwable|null $previous Previous error
     * @param string|null $scimType SCIM error type
     */
    public function __construct(
        string $message = '',
        ?int $code = null,
        ?Throwable $previous = null,
        ?string $scimType = null
    ) {
        $this->scimType = $scimType;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Return the scim type
     *
     * @return string|null
     */
    public function getScimType(): ?string
    {
        return $this->scimType;
    }
}
