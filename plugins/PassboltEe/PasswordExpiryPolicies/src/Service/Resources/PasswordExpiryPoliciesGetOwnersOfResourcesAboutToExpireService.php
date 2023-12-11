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
use Cake\Console\Exception\StopException;
use Cake\Event\EventDispatcherTrait;
use Cake\I18n\FrozenDate;
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

    protected int $expiringInDays;

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

        if (!$settings->isSettingsEnabled()) {
            throw new StopException(__('Password expiry is not activated.'));
        }

        $expiryNotificationInDays = $settings->getExpiryNotification();

        // Notify resource owners that some resources are about to expire
        $notifyIfAboutToExpire =
            EmailNotificationSettings::get('send.password.aboutToExpire')
            && $expiryNotificationInDays;

        // Notify resource owners that resources are expiring today
        $notifyIfExpiresToday = (bool)EmailNotificationSettings::get('send.password.expire');

        $users = $this->getUsersToNotify($expiryNotificationInDays, $notifyIfAboutToExpire, $notifyIfExpiresToday);

        $this->dispatchEvent(
            self::NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME,
            compact(
                'users',
                'notifyIfAboutToExpire',
                'notifyIfExpiresToday',
                'expiryNotificationInDays'
            ),
            $this
        );

        return $users;
    }

    /**
     * Query on UsersTable to retrieve all owners
     *
     * @param null|int $expiryNotificationInDays send notification N days prior to expiry
     * @param bool $notifyIfAboutToExpire email notification setting
     * @param bool $notifyIfExpiresToday email notification setting
     * @return \Cake\ORM\Query|null
     */
    public function getUsersToNotify(
        ?int $expiryNotificationInDays,
        bool $notifyIfAboutToExpire = true,
        bool $notifyIfExpiresToday = true
    ): ?Query {
        $expiringResourceIds = $this->findResourcesExpiringTodayOrInNDays(
            $expiryNotificationInDays,
            $notifyIfAboutToExpire,
            $notifyIfExpiresToday
        )->select('id');

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $usersToNotify = $UsersTable
            ->find('notDisabled')
            ->find('active')
            ->order([], true); // Remove any order as it is not relevant here and breaks in MySQL

        return $UsersTable->filterQueryByResourcesAccess($usersToNotify, $expiringResourceIds, [Permission::OWNER]);
    }

    /**
     * Query all resources expired or expiring in N days
     *
     * @param int|null $expiresInDays expires in exactly this number of days
     * @param bool $notifyIfAboutToExpire notify owners that their passwords is about to expire in N days
     * @param bool $notifyIfExpiresToday notify owners that their passwords are expiring today
     * @return \Cake\ORM\Query
     */
    public function findResourcesExpiringTodayOrInNDays(
        ?int $expiresInDays,
        bool $notifyIfAboutToExpire = true,
        bool $notifyIfExpiresToday = true
    ): Query {
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $expiredResources = $ResourcesTable->find();
        if ($notifyIfAboutToExpire && $notifyIfExpiresToday) {
            $condition = [
                'OR' => [
                    [
                        'expired >=' => FrozenDate::today(),
                        'expired <' => FrozenDate::tomorrow(),
                    ],
                    [
                        'expired >=' => FrozenDate::today()->addDays($expiresInDays),
                        'expired <' => FrozenDate::tomorrow()->addDays($expiresInDays),
                    ],
                ],
            ];
        } elseif ($notifyIfAboutToExpire && !$notifyIfExpiresToday) {
            $condition = [
                'expired >=' => FrozenDate::today()->addDays($expiresInDays),
                'expired <' => FrozenDate::tomorrow()->addDays($expiresInDays),
            ];
        } elseif (!$notifyIfAboutToExpire && $notifyIfExpiresToday) {
            $condition = [
                'expired >=' => FrozenDate::today(),
                'expired <' => FrozenDate::tomorrow(),
            ];
        } else {
            throw new StopException(__('Password expiry email notifications are disabled.'));
        }

        return $expiredResources->where($condition);
    }
}
