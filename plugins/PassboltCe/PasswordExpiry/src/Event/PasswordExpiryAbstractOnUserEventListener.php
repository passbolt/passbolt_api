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
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;

abstract class PasswordExpiryAbstractOnUserEventListener implements EventListenerInterface
{
    use EventDispatcherTrait;

    public const PASSWORD_EXPIRY_ON_USER_DISABLED_OR_DELETED = 'PasswordExpiryOnUserEventListener.expire_resources';

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.beforeSave' => 'expireSharedResources',
        ];
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
     * @param \App\Model\Entity\User $user user deleted or disabled
     * @param string[] $resourcesShared user deleted or disabled
     * @return void
     */
    protected function expireResourcesAccessedByUser(
        User $user,
        array $resourcesShared
    ): void {
        if (empty($resourcesShared)) {
            return;
        }
        $resourceIds = (new PasswordExpiryExpireResourcesService())->expireResources(
            $resourcesShared,
            $user->id
        );

        if (!empty($resourceIds)) {
            $userIdsToSkip = [$user->id];
            $this->dispatchEvent(
                self::PASSWORD_EXPIRY_ON_USER_DISABLED_OR_DELETED,
                compact('resourceIds', 'userIdsToSkip'),
                $this
            );
        }
    }
}
