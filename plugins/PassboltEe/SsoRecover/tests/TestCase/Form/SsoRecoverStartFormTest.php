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
 * @since         3.11.0
 */
namespace Passbolt\SsoRecover\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Passbolt\SsoRecover\Form\SsoRecoverStartForm;

/**
 * @covers \Passbolt\SsoRecover\Form\SsoRecoverStartForm
 */
class SsoRecoverStartFormTest extends AppTestCase
{
    /**
     * @var \Passbolt\SsoRecover\Form\SsoRecoverStartForm
     */
    private $form;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new SsoRecoverStartForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    public function testSsoRecoverStartForm_RequirePresence(): void
    {
        $result = $this->form->execute([]);

        $this->assertFalse($result);
        $formErrors = $this->form->getErrors();
        $this->assertArrayHasAttributes(['case', 'token'], $formErrors);
        $this->assertEquals('The case field is required.', $formErrors['case']['_required']);
        $this->assertEquals('The token field is required.', $formErrors['token']['_required']);
    }

    public function testSsoRecoverStartForm_InvalidCase(): void
    {
        $result = $this->form->execute(['token' => UuidFactory::uuid(), 'case' => 'foo']);

        $this->assertFalse($result);
        $formErrors = $this->form->getErrors();
        $this->assertArrayHasAttributes(['case'], $formErrors);
        $this->assertEquals(
            'The case is not supported. Only "default" case is supported.',
            $formErrors['case']['invalidCase']
        );
    }
}
