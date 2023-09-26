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
 * @since         4.3.0
 */
namespace Passbolt\UserPassphrasePolicies\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Passbolt\UserPassphrasePolicies\Form\UserPassphrasePoliciesSettingsForm;

/**
 * @covers \Passbolt\UserPassphrasePolicies\Form\UserPassphrasePoliciesSettingsForm
 */
class UserPassphrasePoliciesSettingsFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\UserPassphrasePolicies\Form\UserPassphrasePoliciesSettingsForm
     */
    private $form;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new UserPassphrasePoliciesSettingsForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    public function testUserPassphrasePoliciesSettingsForm_Validate_EntropyMinimum(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'range' => self::getRangeTestCases(
                UserPassphrasePoliciesSettingsForm::ENTROPY_MINIMUM_VALUE_MIN,
                UserPassphrasePoliciesSettingsForm::ENTROPY_MINIMUM_VALUE_MAX
            ),
        ];

        $this->assertFormFieldFormatValidation(
            UserPassphrasePoliciesSettingsForm::class,
            'entropy_minimum',
            $this->getDummyUserPassphrasePoliciesSettings(),
            $testCases
        );
    }

    public function testUserPassphrasePoliciesSettingsForm_Validate_ExternalDictionaryCheck(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            UserPassphrasePoliciesSettingsForm::class,
            'external_dictionary_check',
            $this->getDummyUserPassphrasePoliciesSettings(),
            $testCases
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Helper methods
    |---------------------------------------------------------------------------
    */

    private function getDummyUserPassphrasePoliciesSettings(): array
    {
        return [
            'entropy_minimum' => 50,
            'external_dictionary_check' => true,
        ];
    }
}
