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
namespace Passbolt\JwtAuthentication\Test\Utility;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;

abstract class JwtAuthenticationIntegrationTestCase extends AppIntegrationTestCase
{
    use JwtAuthTestTrait;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        (new JwtKeyPairService())->createKeyPair();
        $this->enableFeaturePlugin('JwtAuthentication');
        $this->disableCsrfToken();
    }

    /**
     * Tear down
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('JwtAuthentication');
    }
}
