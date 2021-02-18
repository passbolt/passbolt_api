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
 * @since         2.0.0
 */

namespace Passbolt\MultiFactorAuthentication\Model\Query;

use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Table\UsersTable;
use Cake\Collection\CollectionInterface;
use Cake\ORM\Query;
use Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class IsMfaEnabledQueryDecorator
{
    public const IS_MFA_ENABLED_FILTER_NAME = 'is-mfa-enabled';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper
     */
    private $userMfaMapper;

    /**
     * @param \App\Model\Table\UsersTable $usersTable Users table
     * @param \Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper $userMfaMapper Property mapper
     */
    public function __construct(UsersTable $usersTable, MfaEntityMapper $userMfaMapper)
    {
        $this->usersTable = $usersTable;
        $this->userMfaMapper = $userMfaMapper;
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @param \App\Model\Table\Dto\FindIndexOptions $options Options
     * @return \Cake\ORM\Query
     */
    public function apply(Query $query, FindIndexOptions $options)
    {
        $this->addMfaSettingsAssociationToUsersTable();
        $this->addIsMfaEnabledPropertyToUsers($query);
        $this->applyIsMfaEnabledFilterToResults($query, $options);

        return $query;
    }

    /**
     * @return void
     */
    private function addMfaSettingsAssociationToUsersTable()
    {
        $this->usersTable->hasOne('AccountSettings')
            ->setClassName('Passbolt/AccountSettings.AccountSettings')
            ->setForeignKey('user_id')
            ->setProperty(MfaEntityMapper::MFA_SETTINGS_PROPERTY)
            ->setConditions(['property' => MfaSettings::MFA]);
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @return \Cake\ORM\Query
     */
    private function addIsMfaEnabledPropertyToUsers(Query $query)
    {
        $query
            ->contain(['AccountSettings'])
            ->formatResults(function (CollectionInterface $results) {
                return $results->map($this->userMfaMapper);
            });

        return $query;
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @param \App\Model\Table\Dto\FindIndexOptions $options Options
     * @return \Cake\ORM\Query
     */
    private function applyIsMfaEnabledFilterToResults(Query $query, FindIndexOptions $options)
    {
        $isMfaEnabledFilter = $options->getFilter()[self::IS_MFA_ENABLED_FILTER_NAME] ?? null;

        if ($isMfaEnabledFilter !== null) {
            $query->formatResults(function (CollectionInterface $results) use ($isMfaEnabledFilter) {
                return $results->filter(function (User $user) use ($isMfaEnabledFilter) {
                    return $user->get(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY) === $isMfaEnabledFilter;
                });
            });
        }

        return $query;
    }
}
