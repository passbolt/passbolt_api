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
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersShareService;
use Passbolt\Locale\Service\LocaleService;

class ShareFolderEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     * @see templates/email/html/LU/folder_delete.php
     */
    public const TEMPLATE = 'Passbolt/Folders.LU/folder_share';

    public const TEMPLATE_V5 = 'Passbolt/Metadata.LU/folder_share_v5';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private UsersTable $usersTable;

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
    public function getSubscribedEvents(): array
    {
        return [
            FoldersShareService::FOLDERS_SHARE_FOLDER_EVENT,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.folder.share';
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

        $userId = $event->getData('userId');
        if (!$userId) {
            throw new InvalidArgumentException('`userId` is missing from event data.');
        }

        $isV5 = $event->getData('isV5');
        if (is_null($isV5)) {
            $isV5 = false;
        }

        $operator = $this->usersTable->findFirstForEmail($uac->getId());

        /** @var \App\Model\Entity\User $recipient */
        $recipient = $this->usersTable->findById($userId)
            ->find('locale')
            ->find('notDisabled')
            ->first();

        if (isset($recipient)) {
            $email = $this->createEmail($recipient, $operator, $folder, $isV5);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient
     * @param \App\Model\Entity\User $operator The user at the origin of the operation
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param bool $isV5 Folder is V5 or not.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, User $operator, Folder $folder, bool $isV5): Email
    {
        $userFirstName = $operator->profile->first_name;
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($userFirstName, $folder, $isV5) {
                $subject = __('{0} shared the folder {1}', $userFirstName, $folder->name);
                if ($isV5) {
                    $subject = __('{0} shared a folder', $userFirstName);
                }

                return $subject;
            }
        );

        $data = [
            'body' => [
                'user' => $operator,
                'folder' => $folder,
            ],
            'title' => $subject,
        ];

        $template = $isV5 ? self::TEMPLATE_V5 : self::TEMPLATE;

        return new Email($recipient, $subject, $data, $template);
    }
}
