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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Scenario\Duo;

use App\Test\Factory\UserFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAccountSettingFactory;

/**
 * MfaDuoScenario
 */
class MfaDuoScenario implements FixtureScenarioInterface
{
    use ScenarioAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl|null $user
     * @param bool $isSupported
     * @param null $verified
     * @param ...$args
     * @return array
     */
    public function load($user = null, $isSupported = true, $hostName = null, $verified = null, ...$args): array
    {
        if (is_null($user)) {
            $user = UserFactory::make()->user()->persist();
        }

        /** $@var \App\Utility\UserAccessControl $user */
        [$orgSetting] = $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class, $isSupported, $hostName);
        $accountSetting = MfaAccountSettingFactory::make()
            ->setField('user_id', $user->id)
            ->duo($verified)
            ->persist();

        return [$orgSetting, $accountSetting];
    }
}
