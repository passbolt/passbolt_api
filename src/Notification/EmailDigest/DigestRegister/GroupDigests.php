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
use Passbolt\EmailDigest\Utility\Digest\AbstractDigestRegister;
use Passbolt\EmailDigest\Utility\Digest\Digest;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\Locale\Event\LocaleEmailQueueListener;
use Passbolt\Locale\Service\LocaleService;

class GroupDigests extends AbstractDigestRegister
{
    public const GROUP_USERS_CHANGES_TEMPLATE = 'LU/group_users_change';
    public const GROUPS_DELETE_TEMPLATE = 'LU/groups_delete';

    /**
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestsPool $digestsPool Instance of the digest pool
     * @return void
     */
    public function addDigestsPool(DigestsPool $digestsPool): void
    {
        $digestsPool->addDigest($this->createGroupMembershipDigest());
        $digestsPool->addDigest($this->createGroupDeleteDigest());
    }

    /**
     * @return \Passbolt\EmailDigest\Utility\Digest\Digest
     */
    private function createGroupMembershipDigest(): Digest
    {
        return new Digest(
            __('{0} updated your memberships in several groups', '{0}'),
            [
                GroupUserAddEmailRedactor::TEMPLATE,
                GroupUserUpdateEmailRedactor::TEMPLATE,
                GroupUserDeleteEmailRedactor::TEMPLATE,
            ],
            'admin',
            /**
             * @param \Cake\ORM\Entity[] $emailQueueEntities
             */
            function (array $emailQueueEntities, int $emailCount) {
                $emailData = $emailQueueEntities[0];

                $locale = $emailData->get('template_vars')['locale'];
                $subject = (new LocaleService())->translateString(
                    $locale,
                    function () {
                        return __('Your membership in several groups changed in passbolt');
                    }
                );

                return (new EmailDigest())
                    ->setSubject($subject)
                    ->addLayoutVar(LocaleEmailQueueListener::VIEW_VAR_KEY, $locale)
                    ->setTemplate(static::GROUP_USERS_CHANGES_TEMPLATE)
                    ->setEmailRecipient($emailData->get('email'))
                    ->addTemplateVar('user', $emailData->get('template_vars')['body']['user'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);
            }
        );
    }

    /**
     * @return \Passbolt\EmailDigest\Utility\Digest\Digest
     */
    private function createGroupDeleteDigest(): Digest
    {
        return new Digest(
            __('{0} deleted several groups', '{0}'),
            [
                GroupUserDeleteEmailRedactor::TEMPLATE,
            ],
            'admin',
            /**
             * @param \Cake\ORM\Entity[] $emailQueueEntities
             */
            function (array $emailQueueEntities, int $emailCount) {
                $emailData = $emailQueueEntities[0];

                $locale = $emailData->get('template_vars')['locale'];
                $subject = (new LocaleService())->translateString(
                    $locale,
                    function () {
                        return __('Several groups were deleted in passbolt');
                    }
                );

                return (new EmailDigest())
                    ->setSubject($subject)
                    ->addLayoutVar(LocaleEmailQueueListener::VIEW_VAR_KEY, $locale)
                    ->setTemplate(static::GROUPS_DELETE_TEMPLATE)
                    ->setEmailRecipient($emailData->get('email'))
                    ->addTemplateVar('user', $emailData->get('template_vars')['body']['user'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);
            }
        );
    }
}
