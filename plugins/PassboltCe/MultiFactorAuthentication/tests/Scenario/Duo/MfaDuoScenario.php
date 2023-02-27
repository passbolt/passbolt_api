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
     * @param array $args
     * @return array
     */
    public function load(...$args): array
    {
        /** @var \App\Utility\UserAccessControl|null $user */
        $user = $args[0] ?? null;
        /** @var bool $isSupported */
        $isSupported = $args[1] ?? true;
        /** @var string|null $apiHostName */
        $apiHostName = $args[2] ?? null;
        /** @var null $verified */
        $verified = $args[3] ?? null;

        if (is_null($user)) {
            $user = UserFactory::make()->user()->persist();
        }

        /** $@var \App\Utility\UserAccessControl $user */
        [$orgSetting] = $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class, $isSupported, $apiHostName);
        $accountSetting = MfaAccountSettingFactory::make()
            ->setField('user_id', $user->get('id'))
            ->duo($verified)
            ->persist();

        return [$orgSetting, $accountSetting];
    }
}
