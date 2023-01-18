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

namespace Passbolt\SelfRegistration\Test\TestCase\Form\DryRun;

use Cake\TestSuite\TestCase;
use Passbolt\SelfRegistration\Form\DryRun\SelfRegistrationEmailDomainsDryRunForm;

class SelfRegistrationEmailDomainsDryRunFormTest extends TestCase
{
    /**
     * @var \Passbolt\SelfRegistration\Form\DryRun\SelfRegistrationEmailDomainsDryRunForm
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new SelfRegistrationEmailDomainsDryRunForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function testSelfRegistrationEmailDomainsDryRunFormTest_Valid()
    {
        $this->assertTrue($this->form->execute([
            'email' => 'john@passbolt.com',
        ]));
    }

    public function testSelfRegistrationEmailDomainsDryRunFormTest_Invalid_Email()
    {
        $this->assertFalse($this->form->execute([
            'email' => 'john@passbolt',
        ]));
    }

    public function testSelfRegistrationEmailDomainsDryRunFormTest_Invalid_Type()
    {
        $this->assertFalse($this->form->execute([
            'email' => [],
        ]));
    }
}
