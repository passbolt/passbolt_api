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
 * @since         4.9.0
 */
namespace Passbolt\SmtpSettings\Test\TestCase\Form;

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\TestSuite\TestCase;
use Passbolt\SmtpSettings\Form\CustomSslOptionsForm;

/**
 * @covers \Passbolt\SmtpSettings\Form\CustomSslOptionsForm
 */
class CustomSslOptionsFormTest extends TestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    private function getDummyCustomSslOptions(): array
    {
        return [
            'sslVerifyPeer' => true,
            'sslVerifyPeerName' => true,
            'sslAllowSelfSigned' => false,
            'sslCafile' => null,
        ];
    }

    public function testCustomSslOptionsForm_Validate_SslVerifyPeer(): void
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            CustomSslOptionsForm::class,
            'sslVerifyPeer',
            $this->getDummyCustomSslOptions(),
            $testCases
        );
    }

    public function testCustomSslOptionsForm_Validate_SslVerifyPeerName(): void
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            CustomSslOptionsForm::class,
            'sslVerifyPeerName',
            $this->getDummyCustomSslOptions(),
            $testCases
        );
    }

    public function testCustomSslOptionsForm_Validate_SslAllowSelfSigned(): void
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            CustomSslOptionsForm::class,
            'sslAllowSelfSigned',
            $this->getDummyCustomSslOptions(),
            $testCases
        );
    }

    public function testCustomSslOptionsForm_Validate_SslCafile(): void
    {
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            CustomSslOptionsForm::class,
            'sslCafile',
            $this->getDummyCustomSslOptions(),
            $testCases
        );
    }

    /**
     * @dataProvider sslCaFileValidateTypeProvider
     */
    public function testCustomSslOptionsForm_Validate_SslCafile_NullAndStringTypeAllowed($value, bool $expected): void
    {
        $form = new CustomSslOptionsForm();

        $options = $this->getDummyCustomSslOptions();
        $options['sslCafile'] = $value;
        $result = $form->validate($options);

        $this->assertSame($expected, $result);
    }

    public function sslCaFileValidateTypeProvider(): array
    {
        return [
            [
                'input' => null,
                'expected' => true,
            ],
            [
                'input' => 'im a string',
                'expected' => true,
            ],
            [
                'input' => true,
                'expected' => false,
            ],
            [
                'input' => ['foo' => 'bar'],
                'expected' => false,
            ],
            [
                'input' => new \stdClass(),
                'expected' => false,
            ],
        ];
    }
}
