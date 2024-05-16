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
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Locale\Service\LocaleService;

class UpdateFolderEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     * @see templates/email/html/LU/folder_delete.php
     */
    public const TEMPLATE = 'Passbolt/Folders.LU/folder_update';

    /**
     * @var \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
     */
    private $getUsersIdsHavingAccessToService;

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * Email redactor constructor.
     */
    public function __construct()
    {
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
        $this->getUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            FoldersUpdateService::FOLDERS_UPDATE_FOLDER_EVENT,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.folder.update';
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

        $operator = $this->usersTable->findFirstForEmail($uac->getId());
        $recipients = $this->findUsersUsernameToSendEmailTo($folder);
        foreach ($recipients as $recipient) {
            $email = $this->createEmail($recipient, $operator, $folder);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * Find the users the email has to be sent to.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The updated folder
     * @return \Cake\ORM\Query The list of users username
     */
    private function findUsersUsernameToSendEmailTo(Folder $folder): Query
    {
        $usersIds = $this->getUsersIdsHavingAccessToService->getUsersIdsHavingAccessTo($folder->id);

        return $this->usersTable->find('locale')->find('notDisabled')->where(['Users.id IN' => $usersIds]);
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient
     * @param \App\Model\Entity\User $operator The user at the origin of the operation
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, User $operator, Folder $folder)
    {
        $isOperator = $recipient->id === $operator->id;
        $userFirstName = Purifier::clean($operator->profile->first_name);
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($isOperator, $userFirstName, $folder) {
                if ($isOperator) {
                    return __('You edited the folder {0}', Purifier::clean($folder->name));
                }

                return __('{0} edited the folder {1}', $userFirstName, Purifier::clean($folder->name));
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
