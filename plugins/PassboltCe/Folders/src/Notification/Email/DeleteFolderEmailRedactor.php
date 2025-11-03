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

namespace Passbolt\Folders\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersDeleteService;
use Passbolt\Locale\Service\LocaleService;

class DeleteFolderEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     * @see templates/email/html/LU/folder_delete.php
     */
    public const TEMPLATE = 'Passbolt/Folders.LU/folder_delete';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private UsersTable $usersTable;

    /**
     * Email redactor constructor
     */
    public function __construct()
    {
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            FoldersDeleteService::FOLDERS_DELETE_FOLDER_EVENT,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.folder.delete';
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $folder = $event->getData('folder');
        if (!$folder) {
            throw new InvalidArgumentException('`folder` is missing from event data.');
        }

        $uac = $event->getData('uac');
        if (!$uac) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        $users = $event->getData('users');
        if (!$users) {
            throw new InvalidArgumentException('`users` is missing from event data.');
        }

        $operator = $this->usersTable->findFirstForEmail($uac->getId());
        /** @var array<\App\Model\Entity\User> $recipients */
        $recipients = $this->findUsersUsernameToSendEmailTo($users);
        foreach ($recipients as $recipient) {
            $email = $this->createEmail($recipient, $operator, $folder);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * Find the users the email has to be sent to.
     *
     * @param array $usersIds The list of users id to send the email to.
     * @return \Cake\ORM\Query\SelectQuery The list of users username
     */
    private function findUsersUsernameToSendEmailTo(array $usersIds): SelectQuery
    {
        return $this->usersTable->find('locale')
            ->find('notDisabled')
            ->where(['Users.id IN' => $usersIds]);
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient
     * @param \App\Model\Entity\User $operator The user at the origin of the operation
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, User $operator, Folder $folder): Email
    {
        $isOperator = $recipient->id === $operator->id;
        $userFirstName = $operator->profile->first_name;
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($userFirstName, $isOperator, $folder) {
                if ($isOperator) {
                    return __('You deleted the folder {0}', $folder->name);
                }

                return __('{0} deleted the folder {1}', $userFirstName, $folder->name);
            }
        );

        $data = [
            'body' => [
                'isOperator' => $isOperator,
                'userFirstName' => $userFirstName,
                'user' => $operator,
                'folder' => $folder,
            ],
            'title' => $subject,
        ];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
