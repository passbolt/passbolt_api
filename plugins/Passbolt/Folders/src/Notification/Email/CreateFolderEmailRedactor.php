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

use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersCreateService;

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
            FoldersCreateService::FOLDERS_CREATE_FOLDER_EVENT,
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

        $email = $this->createEmail($folder, $uac);

        return $emailCollection->addEmail($email);
    }

    /**
     * @param Folder $folder Folder entity
     * @param UserAccessControl $uac UserAccessControl
     * @return Email
     */
    private function createEmail(Folder $folder, UserAccessControl $uac)
    {
        $user = $this->usersTable->findFirstForEmail($uac->getId());

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
}
