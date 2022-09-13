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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\Lib;

use App\Utility\Filesystem\DirectoryUtility;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Mailer\TransportFactory;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsSetService
 */
trait SmtpSettingsTestTrait
{
    /**
     * @var string
     */
    protected $dummyPassboltFile = TMP . 'tests' . DS . 'passbolt.php';

    private function getSmtpSettingsData(?string $field = null, $value = null): array
    {
        $validData = [
            'sender_name' => 'John Doe',
            'sender_email' => 'johndoe@passbolt.test',
            'host' => 'some host',
            'tls' => true,
            'port' => '25',
            'username' => 'user',
            'password' => 'secret',
        ];

        if (isset($field)) {
            $validData[$field] = $value;
        }

        return $validData;
    }

    private function setTransportConfig(?string $field = null, $value = null): void
    {
        $validConfig = [
            'host' => 'some test host',
            'tls' => true,
            'port' => '25',
            'username' => 'user',
            'password' => 'secret',
        ];

        if (isset($field)) {
            $validConfig[$field] = $value;
        }

        TransportFactory::get('default')->setConfig($validConfig);
    }

    private function makeDummyPassboltFile(array $data)
    {
        $phpConfig = new PhpConfig(TMP . 'tests' . DS);
        $phpConfig->dump('passbolt', $data);
    }

    private function deletePassboltDummyFile(): void
    {
        DirectoryUtility::removeRecursively($this->dummyPassboltFile);
    }
}
