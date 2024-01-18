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

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiryPolicies\Form\PasswordExpiryPoliciesSettingsForm;

class PasswordExpiryPoliciesSettingsFormTest extends TestCase
{
    private PasswordExpiryPoliciesSettingsForm $form;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new PasswordExpiryPoliciesSettingsForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    public function data(): array
    {
        return [
            [[], false],
            [[
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
            ], false],
            [[
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
            ], false],
            [[
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
            ], false],
            [[
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
            ], false],
            ['expiry notification negative' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => -1,
            ], false],
            ['default expiry period equal to zero' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => 0,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => null,
            ], false],
            ['expiry notification equal to zero' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => null,
            ], true],
            ['expiry notification equal to zero' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => 0,
            ], false],
            ['expiry notification null' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => true,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => null,
            ], true],
            ['expiry notification natural integer' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => true,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => 1,
            ], true],
            ['expiry period empty string' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => true,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => '',
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => 1,
            ], true],
            ['expiry notification empty string' => [
                PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
                PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
                PasswordExpirySettingsDto::POLICY_OVERRIDE => true,
                PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
                PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => '',
            ], true],
        ];
    }

    /**
     * @dataProvider data
     */
    public function testPasswordExpiryPoliciesSettingsForm(array $data, bool $expectedResult)
    {
        $this->assertSame($expectedResult, $this->form->validate($data), json_encode($this->form->getErrors()));
    }
}
