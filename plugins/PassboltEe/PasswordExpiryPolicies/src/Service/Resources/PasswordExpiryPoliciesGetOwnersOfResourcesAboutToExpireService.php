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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiryPolicies\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\AvatarsTable;
use Cake\Console\Exception\StopException;
use Cake\Event\EventDispatcherTrait;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface;

/**
 * Class PasswordExpiryPoliciesNotifyAboutExpiredResourcesService.
 *
 * Notify users about the expiry of their resources
 */
class PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService
{
    use EventDispatcherTrait;

    protected PasswordExpiryGetSettingsServiceInterface $settingsService;

    public const NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME = 'GetOwnersOfResourcesAboutToExpireService.execute.success';

    /**
     * @param \Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface $settingsService Get password expiry service
     */
    public function __construct(PasswordExpiryGetSettingsServiceInterface $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Notify the users about their passwords expiring today or in N days
     *
     * @return \Cake\ORM\Query
     * @throws \Cake\Console\Exception\StopException if the settings are not enabled
     */
    public function notifyUsers(): Query
    {
        $settings = $this->settingsService->get();

        if (!$settings->isPasswordExpiryFeatureEnabled()) {
            throw new StopException(__('Password expiry is not activated.'));
        }

        // Notify resource owners that some resources are about to expire
        $expiryNotificationInDays = EmailNotificationSettings::get('send.password.aboutToExpire') ?
            $settings->getExpiryNotification() : null;

        // Notify resource owners that resources are expiring today
        $notifyIfExpiresToday = (bool)EmailNotificationSettings::get('send.password.expire');

        $users = $this->getUsersToNotify($expiryNotificationInDays, $notifyIfExpiresToday);
        $users
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()]);

        $this->dispatchEvent(
            self::NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME,
            [
                'users' => $users->all()->toArray(),
                'notifyIfExpiresToday' => $notifyIfExpiresToday,
                'expiryNotificationInDays' => $expiryNotificationInDays,
            ],
            $this
        );

        return $users;
    }

    /**
     * Query on UsersTable to retrieve all owners
     *
     * @param null|int $expiryNotificationInDays send notification N days prior to expiry
     * @param bool $notifyIfExpiresToday email notification setting
     * @return \Cake\ORM\Query
     */
    public function getUsersToNotify(
        ?int $expiryNotificationInDays,
        bool $notifyIfExpiresToday = true
    ): Query {
        $expiringResourceIds = $this->findResourcesExpiringTodayOrInNDays(
            $expiryNotificationInDays,
            $notifyIfExpiresToday
        )->select('id');

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $usersToNotify = $UsersTable
            ->find('notDisabled')
            ->find('activeNotDeleted')
            ->order([], true); // Remove any order as it is not relevant here and breaks in MySQL

        return $UsersTable->filterQueryByResourcesAccess($usersToNotify, $expiringResourceIds, [Permission::OWNER]);
    }

    /**
     * Query all resources expired or expiring in N days
     *
     * @param int|null $expiresInDays expires in exactly this number of days
     * @param bool $notifyIfExpiresToday notify owners that their passwords are expiring today
     * @return \Cake\ORM\Query
     */
    public function findResourcesExpiringTodayOrInNDays(
        ?int $expiresInDays,
        bool $notifyIfExpiresToday = true
    ): Query {
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $expiredResources = $ResourcesTable->find();
        $today = FrozenTime::today();
        $tomorrow = FrozenTime::tomorrow();
        $aboutToExpireCondition = [
            'expired >=' => $today->addDays($expiresInDays ?? 0),
            'expired <' => $tomorrow->addDays($expiresInDays ?? 0),
        ];
        $expiredCondition = [
            'expired >=' => $today,
            'expired <' => $tomorrow,
        ];
        if ($expiresInDays && $notifyIfExpiresToday) {
            $condition = [
                'OR' => [$aboutToExpireCondition, $expiredCondition],
            ];
        } elseif ($expiresInDays) {
            $condition = $aboutToExpireCondition;
        } elseif ($notifyIfExpiresToday) {
            $condition = $expiredCondition;
        } else {
            throw new StopException(__('Password expiry email notifications are disabled.'));
        }

        return $expiredResources->where($condition);
    }
}
