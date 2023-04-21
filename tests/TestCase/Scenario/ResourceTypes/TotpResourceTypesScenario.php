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
 * @since         4.0.0
 */
namespace App\Test\TestCase\Scenario\ResourceTypes;

use App\Model\Entity\ResourceType;
use App\Test\Factory\ResourceTypeFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;

class TotpResourceTypesScenario implements FixtureScenarioInterface
{
    /**
     * @inheritDoc
     */
    public function load(...$args)
    {
        return ResourceTypeFactory::make([
            ['slug' => ResourceType::SLUG_STANDALONE_TOTP],
            ['slug' => ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP],
        ])->persist();
    }
}
