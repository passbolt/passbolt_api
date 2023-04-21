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

namespace Passbolt\Scim\Resources\ResourceType;

use Cake\Datasource\EntityInterface;

class GroupResourceType extends BaseResourceType
{
    /**
     * @inheritDoc
     */
    public function setFromScim(array $data): self
    {
        // TODO: Implement setFromScim() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFromDatabase(EntityInterface $entity): self
    {
        // TODO: Implement setFromDatabase() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function add(): self
    {
        // TODO: Implement add() method.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        // TODO: Implement toSCIM() method.
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getResource(string $resourceId): self
    {
        // TODO: Implement getResource() method.
        return $this;
    }
}
