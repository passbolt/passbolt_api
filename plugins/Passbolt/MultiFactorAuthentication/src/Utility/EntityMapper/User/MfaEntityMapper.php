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
 * @since         2.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User;

use App\Model\Entity\User;
use Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService;

/**
 * Class IsMfaEnabledPropertyMapper is responsible for mapping `is_mfa_enabled` property to the user
 *
 * @package Passbolt\MultiFactorAuthentication\Model\Mapper
 */
class MfaEntityMapper
{
    public const MFA_SETTINGS_PROPERTY = 'mfa_settings';
    public const IS_MFA_ENABLED_PROPERTY = 'is_mfa_enabled';

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService
     */
    private $isMfaEnabledService;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService $isMfaEnabledService service
     */
    public function __construct(IsMfaEnabledService $isMfaEnabledService)
    {
        $this->isMfaEnabledService = $isMfaEnabledService;
    }

    /**
     * @param \App\Model\Entity\User $user User to which map is_mfa_enabled property
     * @return \App\Model\Entity\User
     */
    public function __invoke(User $user)
    {
        $user->setHidden([self::MFA_SETTINGS_PROPERTY], true);
        $user->setVirtual([self::IS_MFA_ENABLED_PROPERTY], true);
        $user->set(self::IS_MFA_ENABLED_PROPERTY, $this->isMfaEnabledService->isEnabledForUser($user));

        return $user;
    }
}
