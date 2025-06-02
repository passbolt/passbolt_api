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
 * @since         5.2.0
 */

namespace Passbolt\PasswordExpiry\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\AvatarsTable;
use Cake\Console\Exception\StopException;
use Cake\Event\EventDispatcherTrait;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface;

/**
 * Class PasswordExpiryGetOwnersOfExpiredResourcesService.
 *
 * Notify users about the expiry of their resources
 */
class PasswordExpiryGetOwnersOfExpiredResourcesService
{
    use EventDispatcherTrait;

    protected PasswordExpiryGetSettingsServiceInterface $settingsService;

    public const NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME = 'GetOwnersOfExpiredResourcesService.execute.success';

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
    public function notifyResourceOwners(): Query
    {
        $settings = $this->settingsService->get();

        if (!$settings->isPasswordExpiryFeatureEnabled()) {
            throw new StopException(__('Password expiry is not activated.'));
        }

        $owners = $this->getOwnersToNotify();
        $owners
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()]);

        $this->dispatchEvent(
            self::NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME,
            [
                'users' => $owners,
            ],
            $this
        );

        return $owners;
    }

    /**
     * Query on UsersTable to retrieve all owners
     *
     * @return \Cake\ORM\Query
     */
    public function getOwnersToNotify(): Query
    {
        $expiringResourceIds = $this->findResourcesExpiringToday()->select('id');

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $usersToNotify = $UsersTable
            ->find('notDisabled')
            ->find('activeNotDeleted');

        return $UsersTable->filterQueryByResourcesAccess($usersToNotify, $expiringResourceIds, [Permission::OWNER]);
    }

    /**
     * Query all resources expired or expiring in N days
     *
     * @return \Cake\ORM\Query
     */
    public function findResourcesExpiringToday(): Query
    {
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $expiredResources = $ResourcesTable->find();
        $today = DateTime::today();
        $tomorrow = DateTime::tomorrow();
        $expiredCondition = [
            'expired >=' => $today,
            'expired <' => $tomorrow,
        ];

        return $expiredResources->where($expiredCondition);
    }
}
