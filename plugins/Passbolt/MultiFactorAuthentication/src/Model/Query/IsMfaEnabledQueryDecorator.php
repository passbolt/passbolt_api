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
 * @since         2.0.0
 */

namespace Passbolt\MultiFactorAuthentication\Model\Query;

use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use Cake\Collection\CollectionInterface;
use Cake\ORM\Query;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use RuntimeException;
use Throwable;

class IsMfaEnabledQueryDecorator
{
    const MFA_SETTINGS_PROPERTY = 'mfa_settings';
    const IS_MFA_ENABLED_PROPERTY_NAME = 'is_mfa_enabled';
    const IS_MFA_ENABLED_FILTER_NAME = 'is-mfa-enabled';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable $usersTable Users table
     */
    public function __construct(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
    }

    /**
     * @param Query $query Query
     * @param FindIndexOptions $options Options
     * @return Query
     */
    public function apply(Query $query, FindIndexOptions $options)
    {
        $this->addMfaSettingsAssociationToUsersTable();
        $this->addIsMfaEnabledPropertyToUsers($query, $options);
        $this->applyIsMfaEnabledFilterToResults($query, $options);

        return $query;
    }

    /**
     * @return void
     */
    private function addMfaSettingsAssociationToUsersTable()
    {
        $this->usersTable->hasOne('AccountSettings', [
            'className' => 'AccountSettings',
            'foreignKey' => 'user_id',
            'propertyName' => self::MFA_SETTINGS_PROPERTY,
            'conditions' => [
                'property' => MfaSettings::MFA
            ]
        ]);
    }

    /**
     * @param Query $query Query
     * @param FindIndexOptions $options Options
     * @return Query
     */
    private function addIsMfaEnabledPropertyToUsers(Query $query, FindIndexOptions $options)
    {
        if (!array_key_exists(static::IS_MFA_ENABLED_PROPERTY_NAME, $options->getContain())) {
            return $query;
        }

        $query
            ->contain(['AccountSettings'])
            ->formatResults(function (CollectionInterface $results) {
                return $results->map(function (User $user) {
                    $user->setHidden([self::MFA_SETTINGS_PROPERTY], true);
                    $user->setVirtual([self::IS_MFA_ENABLED_PROPERTY_NAME], true);
                    $user->set(self::IS_MFA_ENABLED_PROPERTY_NAME, $this->getIsMfaEnabled($user));

                    return $user;
                });
            });

        return $query;
    }

    /**
     * @param Query $query Query
     * @param FindIndexOptions $options Options
     * @return Query
     */
    private function applyIsMfaEnabledFilterToResults(Query $query, FindIndexOptions $options)
    {
        $isMfaEnabledFilter = $options->getFilter()[self::IS_MFA_ENABLED_FILTER_NAME] ?? null;

        if ($isMfaEnabledFilter !== null) {
            $query->formatResults(function (CollectionInterface $results) use ($isMfaEnabledFilter) {
                return $results->filter(function (User $user) use ($isMfaEnabledFilter) {
                    return $user->get(self::IS_MFA_ENABLED_PROPERTY_NAME) === $isMfaEnabledFilter;
                });
            });
        }

        return $query;
    }

    /**
     * @param User $user User
     * @return bool
     */
    private function getIsMfaEnabled(User $user)
    {
        $isMfaEnabled = false;

        /** @var AccountSetting $mfaSettings */
        $mfaSettings = $user->get(self::MFA_SETTINGS_PROPERTY) ?? false;
        if ($mfaSettings) {
            try {
                $mfaAccountSettings = new MfaAccountSettings(
                    new UserAccessControl($user->role->name, $user->id),
                    json_decode($mfaSettings->value, true)
                );

                if ($mfaAccountSettings === false) {
                    throw new RuntimeException('Failed to parse JSON with MFA account settings.');
                }

                $isMfaEnabled = count($mfaAccountSettings->getEnabledProviders()) > 0;
            } catch (Throwable $t) {
                // MFA JSON settings can't be parsed or no provider found
            }
        }

        return $isMfaEnabled;
    }
}
