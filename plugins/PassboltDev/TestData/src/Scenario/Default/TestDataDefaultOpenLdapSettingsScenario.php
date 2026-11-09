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
 * @since         5.18.0
 */
namespace Passbolt\TestData\Scenario\Default;

use App\Test\Factory\OrganizationSettingFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\LdapSettingsDataCommand;

class TestDataDefaultOpenLdapSettingsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $command = new LdapSettingsDataCommand();
        $command->beforeDataExecute();
        $data = $command->getData();

        /** @var \App\Model\Entity\OrganizationSetting $setting */
        $setting = OrganizationSettingFactory::make($data)->persist();

        return [$setting];
    }
}
