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
namespace Passbolt\MfaPolicies\Model\Entity;

use App\Model\Entity\OrganizationSetting;

/**
 * MfaPoliciesSetting Entity
 *
 * @property array<string, string> $value Settings value.
 * @inheritDoc
 */
class MfaPoliciesSetting extends OrganizationSetting
{
    /**
     * @var string
     */
    public const PROPERTY_NAME = 'mfaPolicies';

    /**
     * List of allowed policy values.
     *
     * @var string[]
     */
    public const ALLOWED_POLICIES = [
        self::POLICY_OPT_IN,
        self::POLICY_MANDATORY,
    ];

    /**
     * @var string
     */
    public const POLICY_OPT_IN = 'opt-in';

    /**
     * @var string
     */
    public const POLICY_MANDATORY = 'mandatory';
}
