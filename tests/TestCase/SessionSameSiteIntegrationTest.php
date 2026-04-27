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
 * @since         5.12.0
 */
namespace App\Test\TestCase;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;

/**
 * Regression test for cakephp/cakephp#19407.
 *
 * Cake's Session::_defaultConfig() only writes the SameSite=Lax default into the
 * 'php' preset, so cache/database/cake silently lose it. config/bootstrap.php
 * sets the key on Session.ini.session.cookie_samesite explicitly so every preset
 * picks it up. This test guards against either fix being removed.
 *
 * Mechanism: IntegrationTestTrait::_buildRequest() constructs a fresh Session
 * from the live Configure value on every $this->get(...). Session::__construct()
 * calls ini_set() on each Session.ini entry, so ini_get() is the runtime witness.
 */
class SessionSameSiteIntegrationTest extends AppIntegrationTestCase
{
    private string $iniBaseline;

    public function setUp(): void
    {
        parent::setUp();
        $this->iniBaseline = (string)ini_get('session.cookie_samesite');
        // Clear so the assertion proves Cake (not php.ini residue from a prior
        // test) set Lax for the preset under test. Configure mutations made by
        // the test methods are auto-restored by Cake\TestSuite\TestCase.
        ini_set('session.cookie_samesite', '');
    }

    public function tearDown(): void
    {
        ini_set('session.cookie_samesite', $this->iniBaseline);
        parent::tearDown();
    }

    public static function sessionPresetProvider(): array
    {
        return [
            'php preset' => ['php'],
            'cache preset' => ['cache'],
            'database preset' => ['database'],
        ];
    }

    /**
     * @dataProvider sessionPresetProvider
     */
    public function testSessionAppliesSameSiteLaxForPreset(string $preset): void
    {
        Configure::write('Session.defaults', $preset);

        $this->get('/healthcheck/status.json');

        $this->assertResponseOk();
        $this->assertSame(
            'Lax',
            ini_get('session.cookie_samesite'),
            sprintf(
                "Session preset '%s' must apply SameSite=Lax (cakephp/cakephp#19407 workaround).",
                $preset,
            ),
        );
    }
}
