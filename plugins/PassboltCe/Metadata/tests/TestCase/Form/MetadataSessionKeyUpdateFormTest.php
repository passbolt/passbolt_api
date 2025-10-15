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
use Passbolt\Metadata\Form\MetadataSessionKeyUpdateForm;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;

class MetadataSessionKeyUpdateFormTest extends TestCase
{
    /**
     * @var MetadataSessionKeyUpdateForm $form
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataSessionKeyUpdateForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function getDefaultData(): array
    {
        return MetadataSessionKeyFactory::getDefaultData();
    }

    public function testMetadataSessionKeyUpdateForm_Success(): void
    {
        $this->assertTrue($this->form->execute($this->getDefaultData()));
    }

    public function testMetadataSessionKeyUpdateForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['modified']['_empty']));
        $this->assertTrue(isset($errors['data']['_empty']));
    }

    public static function metadataSessionKeyUpdateFormInvalidModifiedDateTimeProvider()
    {
        return [
            ['🔥'],
            ['20140619'],
            ['2014-05-19'],
        ];
    }

    /**
     * @dataProvider metadataSessionKeyUpdateFormInvalidModifiedDateTimeProvider
     */
    public function testMetadataSessionKeyUpdateForm_Error_DataNotValidDateTime(string $modified): void
    {
        $data = $this->getDefaultData();
        $data['modified'] = $modified;
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['modified']['dateTime']));
    }

    public function testMetadataSessionKeyUpdateForm_Error_DataNotValidOpenPGPMessage(): void
    {
        $data = $this->getDefaultData();
        $data['data'] = '🔥';
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['data']['ascii']));
        $this->assertTrue(isset($errors['data']['isValidOpenPGPMessage']));

        $data['data'] = ['🔥'];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['data']['ascii']));
        $this->assertTrue(isset($errors['data']['isValidOpenPGPMessage']));

        $data['data'] = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key');
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['data']['isValidOpenPGPMessage']));
    }
}
