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
 * @since         2.14.0
 */

namespace App\Notification\Email\Redactor;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use App\Notification\Email\Redactor\Comment\CommentAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupDeleteEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUpdateAdminSummaryEmail;
use App\Notification\Email\Redactor\Group\GroupUpdateEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUpdateMembershipEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserAddRequestEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use App\Notification\Email\Redactor\Recovery\AccountRecoveryEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceDeleteEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor;
use App\Notification\Email\Redactor\Share\ShareEmailRedactor;
use App\Notification\Email\Redactor\User\UserDeleteEmailRedactor;
use App\Notification\Email\Redactor\User\UserRegisterEmailRedactor;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use Cake\Event\EventManager;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class CoreEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * @return SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors()
    {
        return [
            new UserRegisterEmailRedactor(
                EmailNotificationSettings::get('send.user.create')
            ),
            new UserDeleteEmailRedactor(
                EmailNotificationSettings::get('send.group.user.delete')
            ),
            new CommentAddEmailRedactor(
                EmailNotificationSettings::get('send.comment.add'),
                EmailNotificationSettings::get('show.comment')
            ),
            new AccountRecoveryEmailRedactor(
                EmailNotificationSettings::get('send.user.recover')
            ),
            new ShareEmailRedactor(
                EmailNotificationSettings::get('send.password.share'),
                EmailNotificationSettings::get('show.username'),
                EmailNotificationSettings::get('show.uri'),
                EmailNotificationSettings::get('show.description'),
                EmailNotificationSettings::get('show.secret')
            ),
            new ResourceCreateEmailRedactor(
                EmailNotificationSettings::get('send.password.create'),
                EmailNotificationSettings::get('show.username'),
                EmailNotificationSettings::get('show.uri'),
                EmailNotificationSettings::get('show.description'),
                EmailNotificationSettings::get('show.secret')
            ),
            new ResourceUpdateEmailRedactor(
                EmailNotificationSettings::get('send.password.update'),
                EmailNotificationSettings::get('show.username'),
                EmailNotificationSettings::get('show.uri'),
                EmailNotificationSettings::get('show.description'),
                EmailNotificationSettings::get('show.secret')
            ),
            new ResourceDeleteEmailRedactor(
                EmailNotificationSettings::get('send.password.delete'),
                EmailNotificationSettings::get('show.username'),
                EmailNotificationSettings::get('show.uri'),
                EmailNotificationSettings::get('show.description'),
                EmailNotificationSettings::get('show.secret')
            ),
            new GroupUserAddEmailRedactor(
                EmailNotificationSettings::get('send.group.user.add')
            ),
            new GroupUserAddRequestEmailRedactor(
                EmailNotificationSettings::get('send.group.user.add')
            ),
            new GroupDeleteEmailRedactor(
                EmailNotificationSettings::get('send.group.delete')
            ),
            new GroupUpdateEmailRedactor(EventManager::instance()),
            new GroupUpdateMembershipEmailRedactor(
                EmailNotificationSettings::get('send.group.user.update')
            ),
            new GroupUserDeleteEmailRedactor(
                EmailNotificationSettings::get('send.group.user.delete')
            ),
            new GroupUpdateAdminSummaryEmail(
                EmailNotificationSettings::get('send.group.manager.update')
            ),
        ];
    }
}
