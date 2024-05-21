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
 * @since         2.13.0
 */

namespace App\Notification\Email\Redactor\Resource;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Resources\ResourcesAddService;
use Cake\Event\Event;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

class ResourceCreateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/resource_create';

    /**
     * @param array|null $config Configuration for the redactor
     */
    public function __construct(?array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            ResourcesAddService::ADD_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.password.create';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $event->getData('resource');
        $user = $event->getData('user');

        $emailCollection->addEmail($this->createResourceCreateEmail($resource, $user));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\Resource $resource Resource created.
     * @param \App\Model\Entity\User $user User creating the resource.
     * @return \App\Notification\Email\Email
     */
    private function createResourceCreateEmail(Resource $resource, User $user): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($resource) {
                return __('You added the password {0}', $resource->name);
            }
        );

        $data = [
            'body' => [
                'user' => $user,
                'resource' => $resource,
                'showUsername' => $this->getConfig('show.username'),
                'showUri' => $this->getConfig('show.uri'),
                'showDescription' => $this->getConfig('show.description'),
                'showSecret' => $this->getConfig('show.secret'),
            ], 'title' => $subject,
        ];

        return new Email($user, $subject, $data, self::TEMPLATE);
    }
}
