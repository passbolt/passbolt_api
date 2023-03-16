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

use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Collection\CollectionInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;

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
        if ($queryContainsIsMfaEnabled) {
            $this->addIsMfaEnabledPropertyToQuery($query);
        }

        $this->applyIsMfaEnabledFilterToResults($query, $options);
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
     * @param array $options Query Options
     * @return bool
     */
    private function queryContainsIsMfaEnabled(array $options): bool
    {
        $containIsMfaEnabled = $options['contain'][self::IS_MFA_ENABLED_PROPERTY] ?? false;

        return $containIsMfaEnabled === true;
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings $mfaOrgSettings MfaOrgSettings
     * @return bool
     * @throws \Exception
     */
    private function isEnabledForUser(User $user, MfaOrgSettings $mfaOrgSettings): bool
    {
        $userMfaSetting = $this->getSettingsForUser($user);
        if (is_null($userMfaSetting)) {
            return false;
        }

        $providersEnabledForOrgAndUser = array_intersect(
            $mfaOrgSettings->getEnabledProviders(),
            $this->getSettingsForUser($user)->getEnabledProviders()
        );

        return count($providersEnabledForOrgAndUser) > 0;
    }

    /**
     * @param \App\Model\Entity\User $user User to get MfaSettings
     * @return \Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings|null
     * @throws \Exception
     */
    private function getSettingsForUser(User $user): ?MfaAccountSettings
    {
        /** @var \Passbolt\AccountSettings\Model\Entity\AccountSetting $mfaSettings */
        $mfaSettings = $user->get(self::MFA_SETTINGS_PROPERTY) ?? null;
        if (empty($mfaSettings)) {
            return null;
        }

        return new MfaAccountSettings(
            new UserAccessControl($user->role->name, $user->id),
            json_decode($mfaSettings->value, true)
        );
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @return void
     */
    private function addIsMfaEnabledPropertyToQuery(Query $query): void
    {
        $mfaOrgSettings = MfaOrgSettings::get();
        if ($mfaOrgSettings->isEnabled()) {
            $query->contain('MfaSettings');
        }

        $query->formatResults(function (CollectionInterface $results) use ($mfaOrgSettings) {
            return $results->map(function (User $user) use ($mfaOrgSettings) {
                $user->setHidden([self::MFA_SETTINGS_PROPERTY], true);
                $user->setVirtual([self::IS_MFA_ENABLED_PROPERTY], true);
                $user->set(self::IS_MFA_ENABLED_PROPERTY, $this->isEnabledForUser($user, $mfaOrgSettings));

                return $user;
            });
        });
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @param array $options Query Options
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the filter is applied and is_mfa_enabled not contained
     */
    private function applyIsMfaEnabledFilterToResults(Query $query, array $options): void
    {
        $isMfaEnabledFilter = $options['filter'][self::IS_MFA_ENABLED_FILTER_NAME] ?? null;

        if ($isMfaEnabledFilter === null) {
            return;
        }

        if (!$this->queryContainsIsMfaEnabled($options)) {
            throw new BadRequestException(__(
                'The property {0} should be contained in order to filter by {1}.',
                self::IS_MFA_ENABLED_PROPERTY,
                self::IS_MFA_ENABLED_FILTER_NAME
            ));
        }

        $query->formatResults(function (CollectionInterface $results) use ($isMfaEnabledFilter) {
            return $results->filter(function (User $user) use ($isMfaEnabledFilter) {
                return $user->get(self::IS_MFA_ENABLED_PROPERTY) === $isMfaEnabledFilter;
            });
        });
    }
}
