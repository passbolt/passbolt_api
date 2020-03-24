<?php declare(strict_types=1);
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
namespace App\Notification\Email\Redactor;

use App\Controller\Setup\SetupCompleteController;
use App\Model\Entity\Profile;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\EntityHistory;

/**
 * Send an email to the admins when an user completes the setup
 */
class AdminUserSetupCompleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/user_setup_complete';

    /**
     * @var UsersTable
     */
    private $usersTable;

    public function __construct(UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            SetupCompleteController::EVENT_NAME,
        ];
    }

    /**
     * @param Event $event
     *
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();
        $data = $event->getData();

        /** @var User $user */
        $user = $data['user'];

        // Load additional associations needed for the email
        $this->usersTable->loadInto(
            $user,
            [
                'Profiles',
                'EntitiesHistory' => function (Query $q) {
                    // Filter on the created action (can happen only once)
                    return $q->where(['crud' => EntityHistory::CRUD_CREATE]);
                },
                'EntitiesHistory.ActionLogs',
                'EntitiesHistory.ActionLogs.Users',
                'EntitiesHistory.ActionLogs.Users.Profiles',
            ]
        );

        /** @var EntityHistory $createdHistory */
        $createdHistory = $user->entities_history[0];

        $invitedBy = $createdHistory->action_log->user;
        $invitedWhen = $createdHistory->action_log->created;

        /** @var User[] $admins */
        $admins = $this->usersTable->findAdmins();
        // Create an email for every admin
        foreach ($admins as $admin) {
            $emailCollection->addEmail($this->createEmail($admin, $user, $invitedBy, $invitedWhen));
        }

        return $emailCollection;
    }

    /**
     * @param User       $admin       An admin user to notify
     * @param User       $user        User who completed setup
     * @param User       $invitedBy   User who invited the user
     * @param FrozenDate $invitedWhen When user was invited
     *
     * @return Email
     */
    private function createEmail(User $admin, User $user, User $invitedBy, FrozenTime $invitedWhen)
    {
        /** @var Profile $profile */
        $profile = $user->profile;

        $subject = __('{0} has just activated their account on', $profile->first_name);

        return new Email(
            $admin->username,
            $subject,
            [
                'title' => $subject,
                'body'  => [
                    'user'        => $user,
                    'admin'       => $admin,
                    'invitedBy'   => $invitedBy->id === $admin->id ? __('you') : $invitedBy->profile->first_name,
                    'invitedWhen' => $invitedWhen->timeAgoInWords(
                        [
                            'accuracy' => 'day',
                        ]
                    ),
                ],
            ],
            self::TEMPLATE
        );
    }
}
