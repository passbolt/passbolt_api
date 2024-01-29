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

use App\Model\Entity\Resource;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;

class PasswordExpiryResourceMarkedAsExpiredEventListener implements EventListenerInterface
{
    use EventDispatcherTrait;

    public const EVENT_RESOURCE_MARKED_AS_EXPIRED = 'event_resource_marked_as_expired';

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.beforeSave' => 'notifyOwnersOnSetAsExpired',
        ];
    }

    /**
     * When setting a resource as expired, notify owners that the resource is now expired
     * and a rotation is required.
     *
     * @param \Cake\Event\EventInterface $event before saving a resource, set a hidden flag if the resource is set as expiring
     * @param \Cake\ORM\Entity $resource resource being saved
     * @return void
     */
    public function notifyOwnersOnSetAsExpired(EventInterface $event, Entity $resource): void
    {
        if (!($resource instanceof Resource)) {
            return;
        }
        if (!$resource->isDirty(PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE)) {
            return;
        }
        /** @var \Cake\I18n\FrozenTime|null $originalExpiryDate */
        $originalExpiryDate = $resource->getOriginal(PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE);
        $resourceWasExpired = !is_null($originalExpiryDate) && $originalExpiryDate->isPast();

        if ($resource->isExpired() && !$resourceWasExpired) {
            $this->dispatchEvent(
                self::EVENT_RESOURCE_MARKED_AS_EXPIRED,
                compact('resource'),
                $this
            );
        }
    }
}
