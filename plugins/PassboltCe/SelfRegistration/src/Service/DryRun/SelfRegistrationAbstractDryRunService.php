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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Service\DryRun;

use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Passbolt\SelfRegistration\Service\SelfRegistrationGetSettingsService;

abstract class SelfRegistrationAbstractDryRunService implements SelfRegistrationDryRunServiceInterface
{
    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Read settings in the DB. Avoid multiple DB queries.
     *
     * @return array
     * @throws \Cake\Http\Exception\InternalErrorException if the data in the DB is invalid.
     */
    protected function getSelfRegistrationSettingsInDB(): array
    {
        if (empty($this->settings)) {
            // Fetch settings in DB
            $this->settings = (new SelfRegistrationGetSettingsService())->getSettings();
        }

        return $this->settings;
    }

    /**
     * Check that the email is not assigned to a registered user.
     *
     * @param string $username Value to check
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the user is already registered
     */
    protected function checkEmailNotPreviouslyRegistered(string $username): void
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $UsersTable->buildEntity(compact('username'));
        $isUnique = $UsersTable->isUniqueUsername($user);

        if (!$isUnique) {
            throw new ForbiddenException(__('The email is already registered.'));
        }
    }

    /**
     * @inheritDoc
     */
    public function isSelfRegistrationOpen(): bool
    {
        $settings = $this->getSelfRegistrationSettingsInDB();

        return isset($settings['provider']);
    }
}
