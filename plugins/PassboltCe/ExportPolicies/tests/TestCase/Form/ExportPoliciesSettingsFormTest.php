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
 * @since         5.10.0
 */

namespace Passbolt\ExportPolicies\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;
use Passbolt\ExportPolicies\Form\ExportPoliciesSettingsForm;
use Passbolt\ExportPolicies\Model\Dto\ExportPoliciesSettingsDto;
use stdClass;

/**
 * @covers \Passbolt\ExportPolicies\Form\ExportPoliciesSettingsForm
 */
class ExportPoliciesSettingsFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    private ?ExportPoliciesSettingsForm $form = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new ExportPoliciesSettingsForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    /**
     * Return dummy data to be used in the tests.
     *
     * @param array $data Data to override.
     * @return array
     */
    private function getDummyExportPoliciesSettings(array $data = []): array
    {
        return ExportPoliciesSettingsDto::createFromDefault($data)->toArray();
    }

    public function testExportPoliciesSettingsForm_Validate_AllowCsvFormat(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            ExportPoliciesSettingsForm::class,
            'allow_csv_format',
            $this->getDummyExportPoliciesSettings(),
            $testCases
        );
    }

    public static function validCombinationProvider(): array
    {
        return [
            [['allow_csv_format' => true]],
            [['allow_csv_format' => false]],
            [['allow_csv_format' => 'true']],
        ];
    }

    /**
     * @dataProvider validCombinationProvider
     * @param array $inputData Valid input data.
     * @return void
     */
    public function testExportPoliciesSettingsForm_Validate_ValidCombinations(array $inputData): void
    {
        $data = $this->getDummyExportPoliciesSettings($inputData);
        $result = $this->form->validate($data);
        $this->assertTrue($result);
    }

    public static function invalidCombinationProvider(): array
    {
        return [
            [['allow_csv_format' => 'invalid']],
            [['allow_csv_format' => '😎😎😎']],
            [['allow_csv_format' => new stdClass()]],
            [['allow_csv_format' => []]],
        ];
    }

    /**
     * @dataProvider invalidCombinationProvider
     * @param array $invalidData Invalid data.
     * @return void
     */
    public function testExportPoliciesSettingsForm_Validate_InvalidAllowCsvFormat(array $invalidData): void
    {
        $data = $this->getDummyExportPoliciesSettings();
        $data['allow_csv_format'] = $invalidData['allow_csv_format'];
        $result = $this->form->validate($data);
        $this->assertFalse($result);
        $this->assertTrue(Hash::check($this->form->getErrors(), 'allow_csv_format.boolean'));
    }
}
