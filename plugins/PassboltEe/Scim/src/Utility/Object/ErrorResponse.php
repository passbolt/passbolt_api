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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility\Object;

use Exception;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\ScimObjectInterface;

/**
 * ErrorResponse class
 */
class ErrorResponse implements ScimObjectInterface
{
    /**
     * Error exception
     *
     * @var \Exception
     */
    protected Exception $exception;

    /**
     * Constructor
     *
     * @param \Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        $data = [
            'schemas' => [SchemaIdentifier::API_ERROR],
            'status' => $this->exception->getCode(),
            'detail' => $this->exception->getMessage(),
        ];
        if ($this->exception instanceof ScimException && $this->exception->getScimType()) {
            $data['scimType'] = $this->exception->getScimType();
        }

        return $data;
    }
}
