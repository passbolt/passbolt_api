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

namespace Passbolt\SelfRegistration\Test\TestCase\Form\Settings;

use App\Model\Validation\EmailValidationRule;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\SelfRegistration\Form\Settings\SelfRegistrationBaseSettingsForm;
use Passbolt\SelfRegistration\Form\Settings\SelfRegistrationEmailDomainsSettingsForm;

class SelfRegistrationEmailDomainsSettingsFormTest extends TestCase
{
    /**
     * @var \Passbolt\SelfRegistration\Form\Settings\SelfRegistrationEmailDomainsSettingsForm
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new SelfRegistrationEmailDomainsSettingsForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Valid_Provider_And_Data_Valid_Should_Succeed()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $this->assertTrue($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'passbolt.com',
                'blog.passbolt.com',
            ]],
        ]));
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_Sanitize_Data()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $this->assertTrue($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'foo' => 'bar',
            'data' => ['allowed_domains' => [
                'some key to be sanitized' => 'passbolt.com',
            ], 'foo' => 'baz'],
        ]));
        $this->assertSame([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'passbolt.com',
            ],],
        ], $this->form->getData());
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Valid_Provider_And_Empty_Domains_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => ''],
        ]));
        $this->assertSame(
            'The list of allowed domains should not be empty.',
            $this->form->getErrors()['data']['allowed_domains']['_empty']
        );
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Valid_Provider_And_Non_String_Domains_Should_Fail()
    {
        $this->assertFalse($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => 'foo'],
        ]));
        $this->assertSame(
            [
                'data' => [
                    'allowed_domains' => [
                        'areEmailDomainsValid' => 'The list of allowed domains should be an array of strings.',
                    ],
                ],
            ],
            $this->form->getErrors()
        );
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Invalid_Provider_And_Data_Valid_Should_Fail()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        $domain = 'passbolt-' . rand(999, 9999) . '.com';
        $this->assertFalse($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'passbolt.com',
                $domain,
            ]],
        ]));

        $this->assertSame(
            'The domain #1 should be a valid domain.',
            $this->form->getErrors()['data']['allowed_domains']['areEmailDomainsValid']
        );
    }

    public function testSelfRegistrationEmailDomainsSettingsForm_With_Invalid_Provider_And_Data_Valid_And_MX_Check_Off_Should_Not_Fail()
    {
        Configure::write(EmailValidationRule::MX_CHECK_KEY, false);
        $domain = 'passbolt-' . rand(999, 9999) . '.com';
        $this->assertTrue($this->form->execute([
            'provider' => SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS,
            'data' => ['allowed_domains' => [
                'passbolt.com',
                $domain,
            ]],
        ]));
    }
}
