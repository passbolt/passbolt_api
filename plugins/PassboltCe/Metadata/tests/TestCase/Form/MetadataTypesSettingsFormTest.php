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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\Metadata\Form\MetadataTypesSettingsForm;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

class MetadataTypesSettingsFormTest extends TestCase
{
    /**
     * @var MetadataTypesSettingsForm $form
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataTypesSettingsForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function getDefaultData(): array
    {
        return MetadataTypesSettingsFactory::getDefaultDataV4();
    }

    public function testMetadataTypesSettingsForm_Success(): void
    {
        $this->assertTrue($this->form->execute($this->getDefaultData()));
    }

    public function testMetadataTypesSettingsForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        foreach (MetadataTypesSettingsDto::PROPS as $prop) {
            $this->assertTrue(isset($errors[$prop]['_empty']));
        }
    }

    public function testMetadataTypesSettingsForm_Error_NotEmptyString(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => '',
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => '',
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => '',
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => '',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]['_empty']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE]['_empty']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]['_empty']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE]['_empty']));
    }

    public function testMetadataTypesSettingsForm_Error_NotString(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => 0,
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => 0,
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => 0,
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => 0,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]['utf8']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE]['utf8']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]['utf8']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE]['utf8']));
    }

    public function testMetadataTypesSettingsForm_Error_NotInList(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => 'v0',
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => 'v0',
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => 'v0',
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => 'v0',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]['inList']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE]['inList']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]['inList']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE]['inList']));
    }

    public function testMetadataTypesSettingsForm_Error_NotBool(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => 'test',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => 'test',
            MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE => 'test',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS]['boolean']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE]['boolean']));
    }

    /**
     * Admin disables everything
     *
     * @return void
     */
    public function testMetadataTypesSettingsForm_Error_AtLeastOneEnabled(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => false,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]['atLeastOne']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS]['atLeastOne']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS]['atLeastOne']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS]['atLeastOne']));
    }

    /**
     * Admin select v4/v5 as default version but does not allow creation of v4/v5
     *
     * @return void
     */
    public function testMetadataTypesSettingsAssertService_Error_DefaultMustBeEnabled(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => 'v5',
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => 'v5',
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => 'v5',
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => 'v5',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => true,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE]['defaultTypeMustBeEnabled']));

        $data = array_merge($this->getDefaultData(), [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => 'v4',
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => 'v4',
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => 'v4',
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => 'v4',
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => false,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE]['defaultTypeMustBeEnabled']));
    }
}
