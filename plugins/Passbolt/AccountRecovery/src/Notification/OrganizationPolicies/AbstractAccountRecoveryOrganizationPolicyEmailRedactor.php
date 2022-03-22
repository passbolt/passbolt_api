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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Notification\OrganizationPolicies;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
abstract class AbstractAccountRecoveryOrganizationPolicyEmailRedactor implements SubscribedEmailRedactorInterface
{
    use ModelAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @param \App\Model\Entity\User $admin Admin receiving the mail
     * @param \App\Model\Entity\User $user User making the action
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy Account recovery request initiated by the user
     * @return \App\Notification\Email\Email
     */
    abstract protected function makeAdminEmail(
        User $admin,
        User $user,
        AccountRecoveryOrganizationPolicy $policy
    ): Email;

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();
        $this->loadModel('Users');
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = $event->getData('policy');
        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $event->getData('uac');

        $admins = $this->Users
            ->findAdmins()
            ->contain(['Profiles' => AvatarsTable::addContainAvatar(),])
            ->all();

        $user = $admins->firstMatch(['id' => $uac->getId()]);

        foreach ($admins as $admin) {
            $emailCollection->addEmail($this->makeAdminEmail($admin, $user, $policy));
        }

        return $emailCollection;
    }
}
