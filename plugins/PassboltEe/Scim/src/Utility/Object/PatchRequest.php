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

namespace Passbolt\Scim\Utility\Object;

use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\ScimObjectInterface;

/**
 * PatchRequest class
 */
class PatchRequest implements ScimObjectInterface
{
    /**
     * Error exception
     *
     * @var array<\Passbolt\Scim\Utility\Object\Operation>
     */
    protected array $operations = [];

    /**
     * Set the data from the SCIM request data array
     *
     * @param array $data Data from SCIM
     */
    public function setFromScim(array $data): static
    {
        $this->operations = [];
        $operations = $data['Operations'] ?? [];
        foreach ((array)$operations as $operationData) {
            $this->operations[] = (new Operation())->setFromScim($operationData);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        $data = [
            'schemas' => [SchemaIdentifier::API_PATCH_OPERATION],
            'Operations' => [],
        ];
        foreach ($this->operations as $operation) {
            $data['Operations'][] = $operation->toSCIM();
        }

        return $data;
    }

    /**
     * Return the list of operations
     *
     * @return array<\Passbolt\Scim\Utility\Object\Operation>
     */
    public function getOperations(): array
    {
        return $this->operations;
    }
}
