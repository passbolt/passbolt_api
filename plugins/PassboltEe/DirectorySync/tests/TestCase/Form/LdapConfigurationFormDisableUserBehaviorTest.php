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
 * @since         4.9.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Test\Utility\LdapConfigurationTestUtility;
use Passbolt\DirectorySync\Utility\Alias;

class LdapConfigurationFormDisableUserBehaviorTest extends TestCase
{
    public function testLdapConfigurationFormDisableUserBehavior_Setting_Unset()
    {
        $ldapSettings = LdapConfigurationTestUtility::getDummyFormData();
        unset($ldapSettings[Alias::DELETE_USER_BEHAVIOR_PROPERTY]);

        $form = new LdapConfigurationForm();
        $this->assertTrue($form->validate($ldapSettings));
    }

    public function testLdapConfigurationFormDisableUserBehavior_Setting_Empty()
    {
        $ldapSettings = LdapConfigurationTestUtility::getDummyFormData();
        $ldapSettings[Alias::DELETE_USER_BEHAVIOR_PROPERTY] = '';

        $form = new LdapConfigurationForm();
        $this->assertFalse($form->validate($ldapSettings));
    }

    public function testLdapConfigurationFormDisableUserBehavior_Setting_Delete()
    {
        $ldapSettings = LdapConfigurationTestUtility::getDummyFormData();
        $ldapSettings[Alias::DELETE_USER_BEHAVIOR_PROPERTY] = Alias::DELETE_USER_BEHAVIOR_DELETE;

        $form = new LdapConfigurationForm();
        $this->assertTrue($form->validate($ldapSettings));
    }

    public function testLdapConfigurationFormDisableUserBehavior_Setting_Disable()
    {
        $ldapSettings = LdapConfigurationTestUtility::getDummyFormData();
        $ldapSettings[Alias::DELETE_USER_BEHAVIOR_PROPERTY] = Alias::DELETE_USER_BEHAVIOR_DISABLE;

        $form = new LdapConfigurationForm();
        $this->assertTrue($form->validate($ldapSettings));
    }

    public function testLdapConfigurationFormDisableUserBehavior_Setting_Foo()
    {
        $ldapSettings = LdapConfigurationTestUtility::getDummyFormData();
        $ldapSettings[Alias::DELETE_USER_BEHAVIOR_PROPERTY] = 'foo';

        $form = new LdapConfigurationForm();
        $this->assertFalse($form->validate($ldapSettings));
    }

    public function testLdapConfigurationFormDisableUserBehavior_Setting_Not_A_String()
    {
        $ldapSettings = LdapConfigurationTestUtility::getDummyFormData();
        $ldapSettings[Alias::DELETE_USER_BEHAVIOR_PROPERTY] = true;

        $form = new LdapConfigurationForm();
        $this->assertFalse($form->validate($ldapSettings));
    }
}
