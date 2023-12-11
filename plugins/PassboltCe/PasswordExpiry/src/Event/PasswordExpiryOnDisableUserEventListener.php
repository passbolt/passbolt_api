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
use App\Model\Table\PermissionsTable;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class PasswordExpiryOnDisableUserEventListener extends PasswordExpiryAbstractOnUserEventListener
{
    /**
     * @inheritDoc
     */
    public function expireSharedResources(EventInterface $event, EntityInterface $user, \ArrayObject $options): void
    {
        if (!($user instanceof User)) {
            return;
        }
        $userIsBeingDisabled = $user->isDirty('disabled') && $user->disabled;
        if (!$userIsBeingDisabled) {
            return;
        }

        /** @var \App\Model\Table\PermissionsTable $PermissionsTable */
        $PermissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $resourceIdsTheUserHadAccessTo = $PermissionsTable
            ->findAllByAro(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true,])
            ->select(['Permissions.aco_foreign_key'])
            ->distinct()
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();

        $this->expireResourcesAccessedByUser($user, $resourceIdsTheUserHadAccessTo);
    }
}
