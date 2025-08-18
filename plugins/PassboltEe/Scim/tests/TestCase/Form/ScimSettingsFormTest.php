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
 * @since         5.5.0
 */

namespace Form;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;

/**
 * ScimSettingsFormTest class
 */
class ScimSettingsFormTest extends AppTestCase
{
    /**
     * @var \Passbolt\Scim\Form\Settings\ScimSettingsForm
     */
    protected ScimSettingsForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new ScimSettingsForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function testExecuteDefault_EmptyData()
    {
        $this->assertFalse($this->form->execute([]));
        $this->assertSame([
            'secret_token' => [
                '_empty' => 'This field cannot be left empty',
            ],
            'scim_user_id' => [
                '_empty' => 'This field cannot be left empty',
            ],
        ], $this->form->getErrors());
    }

    public function testExecuteDefault_InvalidData()
    {
        $this->assertFalse($this->form->execute([
            'scim_user_id' => 'b31c8ff3-805d-4f26-a690-15f5216ef9fc',
            'secret_token' => 'B4fncWvw5ha2brpcCh7NVSTMPa1AnEoufFonPMSy9fN',
        ]));
        $this->assertSame(
            [
            'secret_token' => [
                'correctFormat' => 'The secret token format is incorrect.',
            ],
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
            ],
            $this->form->getErrors()
        );
    }

    public function testExecuteDefault_Valid()
    {
        $user = UserFactory::make()->active()->notDisabled()->admin()->persist();
        $this->assertTrue($this->form->execute([
            'scim_user_id' => $user->id,
            'setting_id' => '3a0132f2-99ec-477c-8696-e951bd6ae521',
            'secret_token' => 'pb_B4fncWvw5ha2brpcCh7NVSTMPa1AnEoufFonPMSy9fN',
        ]));
        $this->assertSame([], $this->form->getErrors());
    }

    public function testExecuteUpdate_EmptyData()
    {
        $this->assertFalse($this->form->execute([], ['validate' => 'update']));
        $this->assertSame([
            'scim_user_id' => [
                '_empty' => 'This field cannot be left empty',
            ],
        ], $this->form->getErrors());
    }

    public function testExecuteUpdate_InvalidData()
    {
        $this->assertFalse($this->form->execute([
            'scim_user_id' => 'b31c8ff3-805d-4f26-a690-15f5216ef9fc',
            'setting_id' => '3a0132f2-99ec-477c-8696-e951bd6ae521',
            'secret_token' => 'B4fncWvw5ha2brpcCh7NVSTMPa1AnEoufFonPMSy9fN',
        ], ['validate' => 'update']));
        $this->assertSame([
            'secret_token' => [
                'correctFormat' => 'The secret token format is incorrect.',
            ],
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
            'setting_id' => [
                'ensureEmpty' => 'The Setting ID cannot be passed on update.',
            ],
        ], $this->form->getErrors());
    }

    public function testExecuteUpdate_Valid()
    {
        $user = UserFactory::make()->active()->notDisabled()->admin()->persist();
        $this->assertTrue($this->form->execute([
            'scim_user_id' => $user->id,
            'secret_token' => 'pb_B4fncWvw5ha2brpcCh7NVSTMPa1AnEoufFonPMSy9fN',
        ], ['validate' => 'update']));
        $this->assertSame([], $this->form->getErrors());
    }
}
