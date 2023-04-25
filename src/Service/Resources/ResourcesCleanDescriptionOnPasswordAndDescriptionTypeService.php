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
 * @since         3.12.2
 */

namespace App\Service\Resources;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

/**
 * Class ResourcesCleanDescriptionOnPasswordAndDescriptionTypeService.
 *
 * Service used in a migration to clean the description field for
 * resources of type password and description
 */
class ResourcesCleanDescriptionOnPasswordAndDescriptionTypeService
{
    /**
     * Clean the description for resources of type password and description
     *
     * @return int
     */
    public function clean(): int
    {
        return TableRegistry::getTableLocator()->get('Resources')->updateAll(
            ['description' => null],
            ['resource_type_id' => UuidFactory::uuid('resource-types.id.password-and-description')]
        );
    }
}
