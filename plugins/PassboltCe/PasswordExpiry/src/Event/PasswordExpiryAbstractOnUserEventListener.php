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
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;

abstract class PasswordExpiryAbstractOnUserEventListener implements EventListenerInterface
{
    use EventDispatcherTrait;

    public const PASSWORD_EXPIRY_ON_USER_DISABLED_OR_DELETED = 'PasswordExpiryOnUserEventListener.expire_resources';

    protected PasswordExpiryValidationServiceInterface $passwordExpiryValidationService;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Application.buildContainer' => 'setPasswordExpiryValidationServiceOnBuildContainerEvent',
            'Model.beforeSave' => 'expireSharedResources',
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
     * @param \Cake\Datasource\EntityInterface $user user being saved
     * @param \ArrayObject $options passed when saving the user
     * @return void
     */
    abstract public function expireSharedResources(
        EventInterface $event,
        EntityInterface $user,
        \ArrayObject $options
    ): void;

    /**
     * When a user is deleted or disabled, the resources he had access to
     * and that were shared with other users need to be expired. At least if
     * the user ever decrypted them.
     *
     * @param \App\Model\Entity\User $user user deleted or disabled
     * @param string[] $resourcesShared resources that the user had access to and that were share with other users
     * @return void
     */
    protected function expireResourcesAccessedByUserAndNotifyOtherOwners(
        User $user,
        array $resourcesShared
    ): void {
        if (empty($resourcesShared)) {
            return;
        }
        if (!$this->passwordExpiryValidationService->isExpiryAutomatic()) {
            return;
        }
        $resourceIds = (new PasswordExpiryExpireResourcesService())->expireResources(
            $resourcesShared,
            $user->id
        );

        if (empty($resourceIds)) {
            return;
        }

        // Skip the user deleted/disabled, as it should not be notified that they have expired passwords.
        // Since we are in beforeSave, the user is not deleted/disabled in DB yet
        $userIdsToSkip = [$user->id];
        $this->dispatchEvent(
            self::PASSWORD_EXPIRY_ON_USER_DISABLED_OR_DELETED,
            compact('resourceIds', 'userIdsToSkip'),
            $this
        );
    }
}
