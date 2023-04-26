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

namespace Passbolt\AccountSettings\Test\TestCase\Controller\Themes;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * @uses \Passbolt\AccountSettings\Controller\Themes\ThemesIndexController
 */
class ThemesIndexControllerTest extends AppIntegrationTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    protected $AccountSettings;

    public $fixtures = [
        'plugin.Passbolt/AccountSettings.AccountSettings',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->AccountSettings = $this->fetchTable('AccountSettings');
    }

    public function testThemesIndexSuccess()
    {
        // Authenticate as ada and list the themes
        $this->logInAsUser();
        $this->get('/account/settings/themes.json?api-version=v2');
        $this->assertResponseOk();
    }

    public function testThemesIndexErrorNotAuthenticated()
    {
        $this->getJson('/account/settings/themes.json?api-version=v2');
        $this->assertAuthenticationError();
    }
}
