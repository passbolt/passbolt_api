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
namespace Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey;

use App\Test\Factory\UserFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAccountSettingFactory;

/**
 * MfaYubikeyScenario
 */
class MfaYubikeyScenario implements FixtureScenarioInterface
{
    use ScenarioAwareTrait;

    public function load(...$args): array
    {
        $user = $args[0] ?? null;
        $isSupported = $args[1] ?? true;
        $yubikeyId = $args[2] ?? null;

        if (is_null($user)) {
            $user = UserFactory::make()->user()->persist();
        }

        [$orgSetting] = $this->loadFixtureScenario(MfaYubikeyOrganizationOnlyScenario::class, $isSupported);
        $accountSetting = MfaAccountSettingFactory::make()
            ->setField('user_id', $user->id)
            ->yubikey($yubikeyId)
            ->persist();

        return [$orgSetting, $accountSetting];
    }
}
