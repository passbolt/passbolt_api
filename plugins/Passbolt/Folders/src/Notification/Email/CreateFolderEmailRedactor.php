<?php
namespace Passbolt\Folders\Notification\Email;

use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersCreateService;

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
class CreateFolderEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     * @see Template/Email/html/LU/folder_create.ctp
     */
    const TEMPLATE = 'Passbolt/Folders.LU/folder_create';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable $usersTable The users table
     */
    public function __construct(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            FoldersCreateService::FOLDERS_CREATE_FOLDER_EVENT,
        ];
    }

    /**
     * @param Folder $folder Folder entity
     * @param UserAccessControl $userAccessControl UserAccessControl
     * @return Email
     */
    private function createEmail(Folder $folder, UserAccessControl $userAccessControl)
    {
        $user = $this->usersTable->findFirstForEmail($userAccessControl->userId());

        $subject = __("You added the folder {0}", $folder->name);

        return new Email(
            $user->username,
            $subject,
            [
                'body' => [
                    'user' => $user,
                    'folder' => $folder,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }

    /**
     * @param Event $event Event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        if (!$event->getData('folder')) {
            throw new InvalidArgumentException('`folder` is missing from event data.');
        }

        if (!$event->getData('uac')) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        $email = $this->createEmail($event->getData('folder'), $event->getData('uac'));

        return $emailCollection->addEmail($email);
    }
}
