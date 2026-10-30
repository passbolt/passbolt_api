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

namespace App\Test\TestCase\Service\Healthcheck\Gpg;

use App\Service\Healthcheck\Gpg\FingerprintMatchGpgHealthcheck;
use App\Test\Lib\Model\GpgkeysModelTrait;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use RuntimeException;

#[CoversClass(FingerprintMatchGpgHealthcheck::class)]
class FingerprintMatchGpgHealthcheckTest extends TestCase
{
    use GpgkeysModelTrait;

    public function testFingerprintMatchGpgHealthcheck_Success(): void
    {
        $healthcheck = (new FingerprintMatchGpgHealthcheck())->check();

        $this->assertTrue($healthcheck->isPassed());
    }

    public function testFingerprintMatchGpgHealthcheck_Error_MultipleArmorBlocks(): void
    {
        $serverPublicKey = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
        $keyContent = $serverPublicKey . "\n" . self::readGpgkeyFixture('ada_public.key');
        // Write public key to a tmp file
        $tmpFile = tempnam(sys_get_temp_dir(), 'passbolt_server_key_');
        if ($tmpFile === false) {
            throw new RuntimeException('Could not create a temporary file.');
        }
        file_put_contents($tmpFile, $keyContent);
        // Set it as server key
        Configure::write('passbolt.gpg.serverKey.public', $tmpFile);

        $healthcheck = (new FingerprintMatchGpgHealthcheck())->check();

        $this->assertFalse($healthcheck->isPassed());

        // clean up
        unlink($tmpFile);
    }
}
