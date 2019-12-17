<?php
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
 * @since         2.14.0
 */

namespace App\Model\Dto;

use App\Utility\Permissions\AcoInterface;
use App\Utility\Permissions\AroEntityInterface;

class PermissionDto
{
    /**
     * @var AroEntityInterface
     */
    private $aroObject;

    /**
     * @var AcoInterface
     */
    private $acoObject;

    /**
     * @var int
     */
    private $type;

    /**
     * @param AcoInterface $accessControlObject ACO
     * @return $this
     */
    public function setAcoObject(AcoInterface $accessControlObject)
    {
        $this->acoObject = $accessControlObject;

        return $this;
    }

    /**
     * @param AroEntityInterface $accessRequestObject ARO
     * @return $this
     */
    public function setAroObject(AroEntityInterface $accessRequestObject)
    {
        $this->aroObject = $accessRequestObject;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'aro_foreign_key' => $this->aroObject->getAroForeignKey(),
            'aco' => $this->acoObject->getAcoType(),
            'aro' => $this->aroObject->getAroType(),
            'type' => $this->type,
        ];
    }

    /**
     * @param int $permissionType
     * @return $this
     */
    public function setType(int $permissionType)
    {
        $this->type = $permissionType;

        return $this;
    }
}
