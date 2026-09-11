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
 * @since         5.16.0
 */
namespace App\Test\TestCase\Http\Session;

use App\Test\Factory\SessionFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Http\Session\DatabaseSession;
use Cake\Utility\Hash;

class SessionDataTest extends AppIntegrationTestCase
{
    private string $maliciousFile;

    public function setUp(): void
    {
        parent::setUp();
        $this->maliciousFile = TMP . 'tests' . DS . 'maliciousFile_' . uniqid() . '.txt';
    }

    public function tearDown(): void
    {
        if (file_exists($this->maliciousFile)) {
            unlink($this->maliciousFile);
        }
        unset($this->maliciousFile);
        parent::tearDown();
    }

    public function testSessionData_DoesNotExecuteInjectedCode(): void
    {
        $payload = '<?php file_put_contents("' . $this->maliciousFile . '", "x"); ?>';
        $session = SessionFactory::make(['data' => 'injected|' . serialize($payload)])->persist();

        $blob = (new DatabaseSession())->read($session->id);
        [, $value] = explode('|', $blob, 2);
        $result = unserialize($value);

        $this->assertSame($payload, $result);
        $this->assertFileDoesNotExist($this->maliciousFile);
    }

    public function testSessionData_ContainsOnlyWhitelistedKeys(): void
    {
        $this->logInAsUser();

        $this->getJson('/resources.json');
        $this->getJson('/users/me.json');
        $this->getJson('/settings.json');
        $this->getJson('/auth/is-authenticated.json');

        $session = $this->getSession()->read() ?? [];

        $allowed = ['Config.time', 'Auth.user.id', 'SessionPreventExtensionMiddleware.time'];

        $paths = array_keys(Hash::flatten($session));
        $extras = array_diff($paths, $allowed);

        $this->assertSame([], $extras, 'Unexpected paths: ' . implode(', ', $extras));
    }
}
