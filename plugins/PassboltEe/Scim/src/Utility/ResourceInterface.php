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

namespace Passbolt\Scim\Utility;

use Passbolt\Scim\Utility\Object\Operation;

/**
 * Interface that define the common methods for SCIM resource objects
 */
interface ResourceInterface extends ScimObjectInterface
{
    /**
     * Return the resource id
     *
     * @return ?string
     */
    public function getId(): ?string;

    /**
     * Return the resource type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set the resource properties from the request SCIM data array
     *
     * @param array $data Data from SCIM
     * @return $this
     */
    public function setFromScim(array $data): static;

    /**
     * Set the resource properties from the database record
     *
     * @param string|int $internalId
     * @return $this
     * @throws \Passbolt\Scim\Exception\ResourceNotFoundException if the resource is not found
     */
    public function setFromDatabase(string|int $internalId): self;

    /**
     * Create the resource information in the database
     *
     * @return $this
     * @throws \Passbolt\Scim\Exception\ScimException
     */
    public function create(): static;

    /**
     * Apply a single patch operation to the object
     *
     * @param \Passbolt\Scim\Utility\Object\Operation $operation
     * @return $this
     */
    public function applyOperation(Operation $operation): static;

    /**
     * Delete the resource information in the database
     *
     * @return $this
     * @throws \Passbolt\Scim\Exception\ScimException
     */
    public function delete(): static;
}
