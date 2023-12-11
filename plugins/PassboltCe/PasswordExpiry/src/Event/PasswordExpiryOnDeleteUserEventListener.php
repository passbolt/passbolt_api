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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiry\Event;

use App\Model\Entity\User;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

class PasswordExpiryOnDeleteUserEventListener extends PasswordExpiryAbstractOnUserEventListener
{
    /**
     * @inheritDoc
     */
    public function expireSharedResources(EventInterface $event, EntityInterface $user, \ArrayObject $options): void
    {
        if (!($user instanceof User)) {
            return;
        }
        $userIsBeingDeleted = $user->isDirty('deleted') && $user->deleted;
        if (!$userIsBeingDeleted) {
            return;
        }

        /** @var string[] $resourcesShared */
        $resourcesShared = $options['resourcesShared'];
        /** @var \App\Model\Table\PermissionsTable $PermissionsTable */
        $this->expireResourcesAccessedByUser($user, $resourcesShared);
    }
}
