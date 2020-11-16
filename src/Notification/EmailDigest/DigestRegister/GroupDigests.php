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

namespace App\Notification\EmailDigest\DigestRegister;

use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserUpdateEmailRedactor;
use Cake\Core\Configure;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Digest\Digest;
use Passbolt\EmailDigest\Utility\Digest\DigestRegisterTrait;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;

class GroupDigests implements EventListenerInterface
{
    use DigestRegisterTrait;

    public const GROUP_USERS_CHANGES_TEMPLATE = 'LU/group_users_change';
    public const GROUPS_DELETE_TEMPLATE = 'LU/groups_delete';

    /**
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestsPool $digestsPool Instance of the digest pool
     * @return void
     */
    public function addDigestsPool(DigestsPool $digestsPool)
    {
        $digestsPool->addDigest($this->createGroupMembershipDigest());
        $digestsPool->addDigest($this->createGroupDeleteDigest());
    }

    /**
     * @return \Passbolt\EmailDigest\Utility\Digest\Digest
     */
    private function createGroupMembershipDigest()
    {
        return new Digest(
            __('{0} updated your memberships in several groups', '{0}'),
            [
                GroupUserAddEmailRedactor::TEMPLATE,
                GroupUserUpdateEmailRedactor::TEMPLATE,
                GroupUserDeleteEmailRedactor::TEMPLATE,
            ],
            'admin',
            function (Entity $emailData, int $emailCount) {
                $digest = (new EmailDigest())
                    ->setSubject(__('Your membership in several groups changed in passbolt'))
                    ->setTemplate(static::GROUP_USERS_CHANGES_TEMPLATE)
                    ->setEmailRecipient($emailData->email)
                    ->addTemplateVar('user', $emailData->template_vars['body']['user'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);

                return $digest;
            }
        );
    }

    /**
     * @return \Passbolt\EmailDigest\Utility\Digest\Digest
     */
    private function createGroupDeleteDigest()
    {
        return new Digest(
            __('{0} deleted several groups', '{0}'),
            [
                GroupUserDeleteEmailRedactor::TEMPLATE,
            ],
            'admin',
            function (Entity $emailData, int $emailCount) {
                $digest = (new EmailDigest())
                    ->setSubject(__('Several groups were deleted in passbolt'))
                    ->setTemplate(static::GROUPS_DELETE_TEMPLATE)
                    ->setEmailRecipient($emailData->email)
                    ->addTemplateVar('user', $emailData->template_vars['body']['user'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);

                return $digest;
            }
        );
    }
}
