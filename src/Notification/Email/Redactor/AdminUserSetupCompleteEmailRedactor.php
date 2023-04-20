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

namespace App\Notification\Email\Redactor;

use App\Controller\Setup\SetupCompleteController;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Log\Model\Entity\EntityHistory;
use RuntimeException;

/**
 * Send an email to the admins when an user completes the setup
 */
class AdminUserSetupCompleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/user_setup_complete';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @param \App\Model\Table\UsersTable|null $usersTable Users Table instance
     */
    public function __construct(?UsersTable $usersTable = null)
    {
        /** @phpstan-ignore-next-line */
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        if (!Configure::read('passbolt.plugins.log.enabled')) {
            // Check if plugin log is enabled because this redactor uses on ActionLog tables
            throw new RuntimeException(sprintf('%s requires Passbolt/Log plugin', self::class));
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event Instance
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        return $this->createEmailCollection($event->getData()['user']);
    }

    /**
     * @param \App\Model\Entity\User $userWhoCompletedSetup User who completed the setup
     * @return \App\Notification\Email\EmailCollection
     */
    private function createEmailCollection(User $userWhoCompletedSetup)
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\User $userWhoCompletedSetup */
        $userWhoCompletedSetup = $this->usersTable->loadInto(
        // Load additional associations needed for the email
            $userWhoCompletedSetup,
            [
                'Profiles',
                'EntitiesHistory' => function (Query $q) {
                    // Filter on the created action (this action can happen only once)
                    return $q->where(['crud' => EntityHistory::CRUD_CREATE]);
                },
                'EntitiesHistory.ActionLogs',
                'EntitiesHistory.ActionLogs.Users',
                'EntitiesHistory.ActionLogs.Users.Profiles',
            ]
        );

        if (!isset($userWhoCompletedSetup->entities_history) || !isset($userWhoCompletedSetup->entities_history[0])) {
            // If there is no created history
            $invitedWhen = FrozenTime::now();
            $invitedBy = $userWhoCompletedSetup;
        } else {
            $createdHistory = $userWhoCompletedSetup->entities_history[0];
            $invitedBy = $createdHistory->action_log->user;
            $invitedWhen = $createdHistory->action_log->created;
            if (!isset($invitedBy)) {
                // If nobody invited the user it means open registration is on
                $invitedBy = $userWhoCompletedSetup;
            }
        }

        /** @var \App\Model\Entity\User[] $admins */
        $admins = $this->usersTable->findAdmins()->find('locale');
        // Create an email for every admin
        foreach ($admins as $admin) {
            $emailCollection->addEmail(
                $this->createEmail($admin, $userWhoCompletedSetup, $invitedBy, $invitedWhen)
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $admin An admin user to notify
     * @param \App\Model\Entity\User $userCompletedSetup User who completed setup
     * @param \App\Model\Entity\User $invitedBy User who invited the user
     * @param \Cake\I18n\FrozenTime $invitedWhen When user was invited
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $admin, User $userCompletedSetup, User $invitedBy, FrozenTime $invitedWhen)
    {
        /** @var \App\Model\Entity\Profile $profile */
        $profile = $userCompletedSetup->profile;

        $subject = (new LocaleService())->translateString(
            $admin->locale,
            function () use ($profile) {
                return __('{0} just activated their account on passbolt', $profile->first_name);
            }
        );

        return new Email(
            $admin->username,
            $subject,
            [
                'title' => $subject,
                'body' => [
                    'user' => $userCompletedSetup,
                    'admin' => $admin,
                    'invitedBy' => $invitedBy,
                    'invitedWhen' => $invitedWhen->timeAgoInWords(['accuracy' => 'day']),
                    'invitedByYou' => $invitedBy->id === $admin->id,
                ],
            ],
            self::TEMPLATE
        );
    }
}
