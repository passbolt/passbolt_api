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
 * @since         2.11.0
 */
namespace Passbolt\Tags\Controller\Tags;

use Passbolt\Tags\Model\Entity\Tag;

trait TagAccessTrait
{
    /**
     * Determines if a $tag is accessible to the current user
     *
     * @param Tag $tag The Tag to check
     * @return bool True if Tag is accessible and false otherwise.
     */
    protected function isPersonalTagAccessible(Tag $tag)
    {
        $isAccessible = false;

        foreach ($tag->get('resources_tags') as $resourcesTag) {
            if ($this->User->id() === $resourcesTag->get('user_id')) {
                $isAccessible = true;
                break;
            }
        }

        return $isAccessible;
    }
}
