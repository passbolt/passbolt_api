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

use App\Model\Entity\Group;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;

class PasswordExpiryOnDeleteGroupEventListener implements EventListenerInterface
{
    use EventDispatcherTrait;

    public const PASSWORD_EXPIRY_ON_GROUP_DELETE = 'PasswordExpiryOnDeleteGroupEventListener.expire_resources';

    protected PasswordExpiryValidationServiceInterface $passwordExpiryValidationService;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Application.buildContainer' => 'setPasswordExpiryValidationServiceOnBuildContainerEvent',
            'Model.beforeSave' => 'expireResourcesOnDeletedGroup',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event event sent on container resolution
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function setPasswordExpiryValidationServiceOnBuildContainerEvent(EventInterface $event): void
    {
        /** @var \Cake\Core\ContainerInterface $container */
        $container = $event->getData('container');
        /** @var \App\Service\Resources\PasswordExpiryValidationServiceInterface $passwordExpiryValidationService */
        $passwordExpiryValidationService = $container->get(PasswordExpiryValidationServiceInterface::class);
        $this->passwordExpiryValidationService = $passwordExpiryValidationService;
    }

    /**
     * @param \Cake\Event\EventInterface $event before save event
     * @param \Cake\Datasource\EntityInterface $group entity being saved
     * @param \ArrayObject $options passed when saving the entity
     * @return void
     */
    public function expireResourcesOnDeletedGroup(
        EventInterface $event,
        EntityInterface $group,
        \ArrayObject $options
    ): void {
        $groupIsBeingDeleted = ($group instanceof Group) && $group->isDirty('deleted') && $group->deleted;
        if (!$groupIsBeingDeleted) {
            return;
        }
        /** @var string[] $resourcesSharedOutsideTheGroup */
        $resourcesSharedOutsideTheGroup = $options['resourcesSharedOutsideTheGroup'];
        if (count($resourcesSharedOutsideTheGroup) === 0) {
            return;
        }
        /** @var string[] $usersInGroup */
        $usersInGroup = $options['usersInGroup'];
        if (count($usersInGroup) === 0) {
            return;
        }
        if (!$this->passwordExpiryValidationService->isExpiryAutomatic()) {
            return;
        }

        $expireResourceService = new PasswordExpiryExpireResourcesService();
        $resourcesExpiring = [];
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        foreach ($resourcesSharedOutsideTheGroup as $resourceId) {
            $userQuery = $UsersTable->find();
            $usersWithPermissionOnResource = $UsersTable
                ->filterQueryByResourceAccess($userQuery, $resourceId)
                ->where(['Users.id IN ' => $usersInGroup]);
            $userIdsLosingPermission = array_diff(
                $usersInGroup,
                $usersWithPermissionOnResource->all()->extract('id')->toArray()
            );

            $result = $expireResourceService->expireOneResource($resourceId, $userIdsLosingPermission);

            if (!is_null($result)) {
                $resourcesExpiring[] = $resourceId;
            }
        }
        if (!empty($resourcesExpiring)) {
            $this->dispatchEvent(
                self::PASSWORD_EXPIRY_ON_GROUP_DELETE,
                ['resourceIds' => $resourcesExpiring],
                $this
            );
        }
    }
}
