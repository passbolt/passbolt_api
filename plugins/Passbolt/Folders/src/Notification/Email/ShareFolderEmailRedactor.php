<?php
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
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersShareService;

class ShareFolderEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     * @see Template/Email/html/LU/folder_share.ctp
     */
    const TEMPLATE = 'Passbolt/Folders.LU/folder_share';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * Email redactor constructor.
     */
    public function __construct()
    {
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            FoldersShareService::FOLDERS_SHARE_FOLDER_EVENT,
        ];
    }

    /**
     * @param Event $event Event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
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

        $userId = $event->getData('userId');
        if (!$userId) {
            throw new InvalidArgumentException('`userId` is missing from event data.');
        }

        $operator = $this->usersTable->findFirstForEmail($uac->userId());
        $recipient = $this->usersTable->findById($userId)->select('username')->extract('username')->first();

        $email = $this->createEmail($recipient, $operator, $folder);
        $emailCollection->addEmail($email);

        return $emailCollection;
    }

    /**
     * @param string $recipient The recipient email
     * @param User $operator The user at the origin of the operation
     * @param Folder $folder The target folder
     * @return Email
     */
    private function createEmail(string $recipient, User $operator, Folder $folder)
    {
        $subject = __("{0} shared the folder {1}", $operator->profile->first_name, $folder->name);
        $data = [
            'body' => [
                'user' => $operator,
                'folder' => $folder,
            ],
            'title' => $subject,
        ];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
