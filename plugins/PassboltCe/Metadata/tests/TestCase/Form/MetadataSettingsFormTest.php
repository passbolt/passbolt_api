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
use Passbolt\Metadata\Form\MetadataSettingsForm;
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;

class MetadataSettingsFormTest extends TestCase
{
    /**
     * @var MetadataSettingsForm $form
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataSettingsForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function getDefaultData(): array
    {
        return MetadataSettingsFactory::getDefaultData();
    }

    public function testMetadataSettingsForm_Success(): void
    {
        $this->assertTrue($this->form->execute($this->getDefaultData()));
    }

    public function testMetadataSettingsForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        foreach (MetadataSettingsDto::PROPS as $prop) {
            $this->assertTrue(isset($errors[$prop]['_empty']));
        }
    }

    public function testMetadataSettingsForm_Error_NotEmptyString(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => '',
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => '',
            MetadataSettingsDto::DEFAULT_TAG_TYPE => '',
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => '',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES]['_empty']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_FOLDER_TYPE]['_empty']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_TAG_TYPE]['_empty']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_COMMENT_TYPE]['_empty']));
    }

    public function testMetadataSettingsForm_Error_NotString(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => 0,
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => 0,
            MetadataSettingsDto::DEFAULT_TAG_TYPE => 0,
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => 0,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES]['utf8']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_FOLDER_TYPE]['utf8']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_TAG_TYPE]['utf8']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_COMMENT_TYPE]['utf8']));
    }

    public function testMetadataSettingsForm_Error_NotInList(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => 'v0',
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => 'v0',
            MetadataSettingsDto::DEFAULT_TAG_TYPE => 'v0',
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => 'v0',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES]['inList']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_FOLDER_TYPE]['inList']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_TAG_TYPE]['inList']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_COMMENT_TYPE]['inList']));
    }

    public function testMetadataSettingsForm_Error_NotBool(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => 'test',
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => 'test',
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS]['boolean']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS]['boolean']));
    }

    /**
     * Admin disables everything
     *
     * @return void
     */
    public function testMetadataSettingsForm_Error_AtLeastOneEnabled(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => false,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]['atLeastOne']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS]['atLeastOne']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS]['atLeastOne']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS]['atLeastOne']));
    }

    /**
     * Admin select v4/v5 as default version but does not allow creation of v4/v5
     *
     * @return void
     */
    public function testMetadataSettingsAssertService_Error_DefaultMustBeEnabled(): void
    {
        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => 'v5',
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => 'v5',
            MetadataSettingsDto::DEFAULT_TAG_TYPE => 'v5',
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => 'v5',
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => true,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_FOLDER_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_TAG_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_COMMENT_TYPE]['defaultTypeMustBeEnabled']));

        $data = array_merge($this->getDefaultData(), [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => 'v4',
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => 'v4',
            MetadataSettingsDto::DEFAULT_TAG_TYPE => 'v4',
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => 'v4',
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => false,
        ]);
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_FOLDER_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_TAG_TYPE]['defaultTypeMustBeEnabled']));
        $this->assertTrue(isset($errors[MetadataSettingsDto::DEFAULT_COMMENT_TYPE]['defaultTypeMustBeEnabled']));
    }
}
