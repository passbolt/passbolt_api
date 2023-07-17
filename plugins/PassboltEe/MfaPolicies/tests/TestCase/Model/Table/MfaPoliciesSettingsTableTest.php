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
 * @since         3.10.0
 */

namespace Passbolt\MfaPolicies\Test\TestCase\Model\Table;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;

/**
 * @see \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable
 */
class MfaPoliciesSettingsTableTest extends AppTestCase
{
    use LocatorAwareTrait;

    /**
     * Test subject
     *
     * @var \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable
     */
    protected $MfaPoliciesSettings;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->MfaPoliciesSettings = $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->MfaPoliciesSettings);

        parent::tearDown();
    }

    /**
     * Make sure before find hook adds filter to only fetch MFA policies property.
     *
     * @uses \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable::beforeFind()
     */
    public function testBeforeFind_AddsFilterForMfaPoliciesProperty(): void
    {
        MfaPoliciesSettingFactory::make(
            ['property' => 'Bar', 'property_id' => UuidFactory::uuid('Bar')]
        )->persist();
        MfaPoliciesSettingFactory::make(
            ['property' => 'Foo', 'property_id' => UuidFactory::uuid('Foo')]
        )->persist();
        MfaPoliciesSettingFactory::make()
            ->setPolicy(MfaPoliciesSetting::POLICY_MANDATORY)
            ->setRememberMeForAMonth(false)
            ->persist();

        $mfaPoliciesSetting = $this->MfaPoliciesSettings->find()->toArray();

        $this->assertCount(1, $mfaPoliciesSetting);
    }

    /**
     * @uses \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable::beforeFind()
     */
    public function testBeforeFind_NoPropertyInDbReturnsEmpty(): void
    {
        MfaPoliciesSettingFactory::make(
            ['property' => 'Bar', 'property_id' => UuidFactory::uuid('Bar')]
        )->persist();
        MfaPoliciesSettingFactory::make(
            ['property' => 'Foo', 'property_id' => UuidFactory::uuid('Foo')]
        )->persist();

        $mfaPoliciesSetting = $this->MfaPoliciesSettings->find()->toArray();

        $this->assertCount(0, $mfaPoliciesSetting);
    }

    /**
     * @uses \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable::validationDefault()
     */
    public function testValidationDefault_Empty(): void
    {
        $mfaPoliciesSetting = $this->MfaPoliciesSettings->newEntity([
            'property' => $this->MfaPoliciesSettings->getProperty(),
            'property_id' => $this->MfaPoliciesSettings->getPropertyId(),
            'value' => '',
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
        ]);

        $validationErrors = $mfaPoliciesSetting->getErrors();

        $this->assertNotEmpty($validationErrors);
        $this->assertArrayHasKey('value', $validationErrors);
        $this->assertStringContainsString('value should not be empty', $validationErrors['value']['_empty']);
    }

    /**
     * @uses \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable::validationDefault()
     */
    public function testValidationDefault_InvalidType(): void
    {
        $mfaPoliciesSetting = $this->MfaPoliciesSettings->newEntity([
            'property' => $this->MfaPoliciesSettings->getProperty(),
            'property_id' => $this->MfaPoliciesSettings->getPropertyId(),
            'value' => 'string', // Invalid: we accept array only
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
        ]);

        $validationErrors = $mfaPoliciesSetting->getErrors();

        $this->assertNotEmpty($validationErrors);
        $this->assertArrayHasKey('value', $validationErrors);
        $this->assertStringContainsString('should be an array', $validationErrors['value']['isArray']);
    }

    public function testFind_Error_InvalidJsonValue(): void
    {
        $invalidJson = '{"policy":"opt-in","remember_me_for_a_month';
        MfaPoliciesSettingFactory::make(['value' => $invalidJson])->persist();

        /** @var \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting $mfaPoliciesSetting */
        $mfaPoliciesSetting = $this->MfaPoliciesSettings->find()->firstOrFail();

        $this->assertSame($invalidJson, $mfaPoliciesSetting->value);
    }
}
