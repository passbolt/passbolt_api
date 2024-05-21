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

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use App\Notification\Email\Redactor\Comment\CommentAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupDeleteEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUpdateAdminSummaryEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserAddRequestEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserUpdateEmailRedactor;
use App\Notification\Email\Redactor\Recovery\AccountRecoveryCompleteAdminEmailRedactor;
use App\Notification\Email\Redactor\Recovery\AccountRecoveryCompleteUserEmailRedactor;
use App\Notification\Email\Redactor\Recovery\AccountRecoveryEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceDeleteEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor;
use App\Notification\Email\Redactor\Setup\SetupRecoverAbortAdminEmailRedactor;
use App\Notification\Email\Redactor\Share\ShareEmailRedactor;
use App\Notification\Email\Redactor\User\AdminDeleteEmailRedactor;
use App\Notification\Email\Redactor\User\AdminDisableEmailRedactor;
use App\Notification\Email\Redactor\User\UserAdminRoleRevokedEmailRedactor;
use App\Notification\Email\Redactor\User\UserDeleteEmailRedactor;
use App\Notification\Email\Redactor\User\UserDisableEmailRedactor;
use App\Notification\Email\Redactor\User\UserRegisterEmailRedactor;
use Cake\Core\Configure;
use Passbolt\SelfRegistration\Notification\Email\Redactor\User\SelfRegistrationUserEmailRedactor;

class CoreEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors(): array
    {
        $redactors[] = new UserRegisterEmailRedactor();
        $redactors[] = new SelfRegistrationUserEmailRedactor();
        $redactors[] = new CommentAddEmailRedactor();
        $redactors[] = new AccountRecoveryEmailRedactor();
        $redactors[] = new AccountRecoveryCompleteUserEmailRedactor();
        $redactors[] = new SetupRecoverAbortAdminEmailRedactor();
        $redactors[] = new AccountRecoveryCompleteAdminEmailRedactor();
        $redactors[] = new UserDisableEmailRedactor();
        $redactors[] = new AdminDisableEmailRedactor();
        $redactors[] = new ShareEmailRedactor();
        $redactors[] = new ResourceCreateEmailRedactor();
        $redactors[] = new ResourceUpdateEmailRedactor();
        $redactors[] = new ResourceDeleteEmailRedactor();
        $redactors[] = new GroupUserAddEmailRedactor();
        $redactors[] = new GroupDeleteEmailRedactor();
        $redactors[] = new GroupUserUpdateEmailRedactor();
        $redactors[] = new UserDeleteEmailRedactor();
        $redactors[] = new GroupUserDeleteEmailRedactor();
        $redactors[] = new GroupUpdateAdminSummaryEmailRedactor();
        $redactors[] = new GroupUserAddRequestEmailRedactor();
        if (Configure::read('passbolt.plugins.log.enabled')) {
            $redactors[] = new AdminUserSetupCompleteEmailRedactor();
        }
        if (Configure::read(UserAdminRoleRevokedEmailRedactor::CONFIG_KEY_EMAIL_ENABLED)) {
            $redactors[] = new UserAdminRoleRevokedEmailRedactor();
        }
        if (Configure::read(AdminDeleteEmailRedactor::CONFIG_KEY_EMAIL_ENABLED)) {
            $redactors[] = new AdminDeleteEmailRedactor();
        }

        return $redactors;
    }
}
