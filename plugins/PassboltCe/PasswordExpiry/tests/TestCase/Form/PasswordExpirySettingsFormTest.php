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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;

/**
 * @see \Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService
 */
class PasswordExpirySettingsFormTest extends TestCase
{
    private PasswordExpirySettingsForm $form;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new PasswordExpirySettingsForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    public function passwordExpirySettingsFormDataProvider(): array
    {
        return [
            [
                'inputData' => [],
                'expectedResult' => false,
            ],
            [
                'inputData' => [
                    PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                    PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
                ],
                'expectedResult' => false,
            ],
            [
                'inputData' => [
                    PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                    PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
                ],
                'expectedResult' => false,
            ],
            [
                'inputData' => [
                    PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                    PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
                ],
                'expectedResult' => false,
            ],
            [
                'inputData' => [
                    PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                    PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
                ],
                'expectedResult' => true,
            ],
        ];
    }

    /**
     * @dataProvider passwordExpirySettingsFormDataProvider
     */
    public function testPasswordExpirySettingsForm(array $data, bool $expectedResult)
    {
        $this->assertSame($expectedResult, $this->form->validate($data));
    }
}
