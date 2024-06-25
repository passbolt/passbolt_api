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
 * @since         3.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\Query;

use App\Utility\UserAccessControl;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class IsMfaEnabledQueryService
{
    public const IS_MFA_ENABLED_FILTER_NAME = 'is-mfa-enabled';
    public const IS_MFA_ENABLED_PROPERTY = 'is_mfa_enabled';
    public const MFA_SETTINGS_PROPERTY = 'mfa_settings';

    /**
     * @param \Cake\ORM\Query $query Query
     * @param \App\Utility\UserAccessControl $uac User Access Control
     * @param array $options Query Options
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if a non-admin requests the is_mfa_enabled contain
     * @throws \Cake\Http\Exception\BadRequestException if the filter is applied and is_mfa_enabled not contained
     */
    public function decorateAndFilterForIndex(Query $query, UserAccessControl $uac, array $options): void
    {
        $queryContainsIsMfaEnabled = $this->queryContainsIsMfaEnabled($options);

        if ($queryContainsIsMfaEnabled && !$uac->isAdmin()) {
            throw new BadRequestException(
                __('The property {0} is visible by administrators only.', self::IS_MFA_ENABLED_PROPERTY)
            );
        }

        $isMfaEnabledFilter = $options['filter'][self::IS_MFA_ENABLED_FILTER_NAME] ?? null;
        if (!is_null($isMfaEnabledFilter)) {
            $this->filterByMfaEnabled($query, $isMfaEnabledFilter, $queryContainsIsMfaEnabled);
        } elseif ($queryContainsIsMfaEnabled) {
            $this->addIsMfaEnabledPropertyToQuery($query);
        }
    }

    /**
     * Contain the "is_mfa_enabled" property if the request is made
     *  - by an admin
     *  - or the user being viewed
     *
     * @param \Cake\ORM\Query $query Query
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param string $userId ID of the user viewed
     * @return void
     */
    public function decorateForView(Query $query, UserAccessControl $uac, string $userId): void
    {
        if ($uac->isAdmin() || $uac->getId() === $userId) {
            $this->addIsMfaEnabledPropertyToQuery($query);
        }
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @param bool $isMfaEnabledFilter filter users by mfa enable if true, and mfa disabled if false
     * @param bool $containIsMfaEnabledProperty if true, append the is_mfa_enabled field to the query result
     * @return void
     */
    private function filterByMfaEnabled(Query $query, bool $isMfaEnabledFilter, bool $containIsMfaEnabledProperty): void
    {
        $query->leftJoinWith('MfaSettings');

        if ($containIsMfaEnabledProperty) {
            $query->selectAlso([self::IS_MFA_ENABLED_PROPERTY => (int)$isMfaEnabledFilter]);
        }

        $mfaOrgSettings = MfaOrgSettings::get();
        if ($isMfaEnabledFilter) {
            $or = [];
            foreach ($mfaOrgSettings->getEnabledProviders() as $provider) {
                $or[] = $query->newExpr()->like('MfaSettings.value', '%"' . $provider . '"%"' . $provider . '"%');
            }
            $query->where(['OR' => $or]);
        } else {
            $notLike = [];
            foreach ($mfaOrgSettings->getEnabledProviders() as $provider) {
                $notLike[] =
                    $query->newExpr()->notLike('MfaSettings.value', '%"' . $provider . '"%"' . $provider . '"%');
            }
            $query->where(['OR' => [
                $notLike,
                $query->newExpr()->isNull('MfaSettings.id'),
            ]]);
        }

        $selectTypeMap = $query->getSelectTypeMap();
        $selectTypeMap->addDefaults([self::IS_MFA_ENABLED_PROPERTY => 'boolean']);
    }

    /**
     * @param array $options Query Options
     * @return bool
     */
    private function queryContainsIsMfaEnabled(array $options): bool
    {
        $containIsMfaEnabled = $options['contain'][self::IS_MFA_ENABLED_PROPERTY] ?? false;

        return $containIsMfaEnabled === true;
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @return void
     */
    private function addIsMfaEnabledPropertyToQuery(Query $query): void
    {
        $mfaOrgSettings = MfaOrgSettings::get();
        if ($mfaOrgSettings->isEnabled()) {
            $isMfaEnabledSubQuery = TableRegistry::getTableLocator()
                ->get('Passbolt/AccountSettings.AccountSettings')
                ->subquery();
            $or = [];
            foreach ($mfaOrgSettings->getEnabledProviders() as $provider) {
                $or[] = $query->newExpr()->like('AccountSettings.value', '%"' . $provider . '"%"' . $provider . '"%');
            }
            $isMfaEnabledSubQuery
                ->select('count(*)')
                ->where([
                    'AccountSettings.property' => MfaSettings::MFA,
                    'AccountSettings.user_id' => new IdentifierExpression('Users.id'),
                    'OR' => $or,
                ]);
        } else {
            $isMfaEnabledSubQuery = 0;
        }

        $query->selectAlso([self::IS_MFA_ENABLED_PROPERTY => $isMfaEnabledSubQuery]);
        $selectTypeMap = $query->getSelectTypeMap();
        $selectTypeMap->addDefaults([self::IS_MFA_ENABLED_PROPERTY => 'boolean']);
    }
}
