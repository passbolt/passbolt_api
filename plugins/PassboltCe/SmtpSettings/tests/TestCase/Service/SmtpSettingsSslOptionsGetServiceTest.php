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
 * @since         4.7.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Service;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Passbolt\SmtpSettings\Service\SmtpSettingsSslOptionsGetService;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsSslOptionsGetService
 */
class SmtpSettingsSslOptionsGetServiceTest extends AppTestCase
{
    /**
     * @var SmtpSettingsSslOptionsGetService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SmtpSettingsSslOptionsGetService();
    }

    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    /**
     * No config override, no env set.
     *
     * @return void
     */
    public function testSmtpSettingsSslOptionsGetService()
    {
        $result = $this->service->get();

        $this->assertSame([], $result);
        $this->assertTrue($this->service->isDefault());
    }

    public function testSmtpSettingsSslOptionsGetService_Overridden_DefaultValues()
    {
        Configure::write('passbolt.plugins.smtpSettings.security', [
            // default values, but in different order
            'sslCafile' => null,
            'sslVerifyPeer' => true,
            'sslVerifyPeerName' => true,
            'sslAllowSelfSigned' => false,
        ]);

        $result = $this->service->get();

        $this->assertSame([], $result);
        $this->assertTrue($this->service->isDefault());
    }

    public function testSmtpSettingsSslOptionsGetService_Overridden_SpecificKeys()
    {
        Configure::write('passbolt.plugins.smtpSettings.security', [
            'sslVerifyPeer' => false,
            'sslVerifyPeerName' => false,
            'sslAllowSelfSigned' => false,
        ]);

        $result = $this->service->get();

        $this->assertSame([
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false,
        ], $result);
        $this->assertFalse($this->service->isDefault());
    }

    public function testSmtpSettingsSslOptionsGetService_Overridden_CAFile()
    {
        Configure::write('passbolt.plugins.smtpSettings.security', [
            'sslAllowSelfSigned' => true,
            'sslCafile' => '/path/to/cafile.crt',
        ]);

        $result = $this->service->get();

        $this->assertSame([
            'allow_self_signed' => true,
            'cafile' => '/path/to/cafile.crt',
        ], $result);
        $this->assertFalse($this->service->isDefault());
    }

    public function testSmtpSettingsSslOptionsGetService_Overridden_AllValues()
    {
        Configure::write('passbolt.plugins.smtpSettings.security', [
            'sslVerifyPeer' => false,
            'sslVerifyPeerName' => false,
            'sslAllowSelfSigned' => true,
            'sslCafile' => '/etc/ssl/certs/mailpit/cert.pem',
        ]);

        $result = $this->service->get();

        $this->assertSame([
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            'cafile' => '/etc/ssl/certs/mailpit/cert.pem',
        ], $result);
        $this->assertFalse($this->service->isDefault());
    }

    public function invalidConfigValuesProvider()
    {
        return [
            [
                'data' => [
                    'sslVerifyPeer' => 'foo',
                    'sslVerifyPeerName' => 123,
                    'sslAllowSelfSigned' => [],
                    'sslCafile' => true,
                ],
                'expectedErrorMessages' => [
                    'The sslVerifyPeer configuration should be a boolean.',
                    'The sslVerifyPeerName configuration should be a boolean.',
                    'The sslAllowSelfSigned configuration should be a boolean.',
                    'The sslCafile configuration should be NULL or string.',
                ],
            ],
            [
                'data' => [
                    'sslVerifyPeer' => true,
                    'sslVerifyPeerName' => true,
                    'sslAllowSelfSigned' => false,
                    'sslCafile' => [['foo' => 'bar']],
                ],
                'expectedErrorMessages' => [
                    'The sslCafile configuration should be NULL or string.',
                ],
            ],
            [
                'data' => [
                    'sslVerifyPeer' => 90.82,
                    'sslVerifyPeerName' => 'admin\'--\' AND 1=1;',
                    'sslAllowSelfSigned' => true,
                    'sslCafile' => '/path/to/cafile.crt',
                ],
                'expectedErrorMessages' => [
                    'The sslVerifyPeer configuration should be a boolean.',
                    'The sslVerifyPeerName configuration should be a boolean.',
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidConfigValuesProvider
     */
    public function testSmtpSettingsSslOptionsGetService_BadConfigValues(array $badConfigValues, array $expectedErrorMessages)
    {
        Configure::write('passbolt.plugins.smtpSettings.security', $badConfigValues);

        $this->expectException(BadRequestException::class);
        $defaultExceptionMsg = 'Invalid `passbolt.plugins.smtpSettings.security` configuration values.' . ' ';
        $defaultExceptionMsg .= 'Errors: ' . implode('; ', $expectedErrorMessages);
        $this->expectExceptionMessage($defaultExceptionMsg);

        $this->service->get();
    }

    public function testSmtpSettingsSslOptionsGetService_IsDefault_True()
    {
        $result = $this->service->isDefault();

        $this->assertTrue($result);
    }

    public function testSmtpSettingsSslOptionsGetService_IsDefault_False()
    {
        Configure::write('passbolt.plugins.smtpSettings.security', ['sslVerifyPeer' => false]);

        $result = $this->service->isDefault();

        $this->assertFalse($result);
    }
}
