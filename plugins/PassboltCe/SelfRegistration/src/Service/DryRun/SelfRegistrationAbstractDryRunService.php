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
     * @param string $email Value to check
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the user is already registered
     */
    protected function checkEmailNotPreviouslyRegistered(string $email): void
    {
        // @todo [NICE TO HAVE] move it into UsersFinderTrait with other similar finders.
        $nonDeletedUserExists = TableRegistry::getTableLocator()
                ->get('Users')
                ->find()
                ->where([
                    'Users.username' => $email,
                    'deleted' => false,
                ])
                ->count() > 0;
        if ($nonDeletedUserExists) {
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
