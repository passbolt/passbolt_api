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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPolicies\Test\Lib\Controller;

trait PasswordPoliciesModelTrait
{
    /**
     * Asserts that an object has all the attributes a password policies should have.
     *
     * @param object $responseBody
     * @param bool $isSourceDatabase
     * @return void
     */
    protected function assertPasswordPoliciesAttributes(object $responseBody, bool $isSourceDatabase = false)
    {
        $attributesToHave = [
            'default_generator',
            'external_dictionary_check',
            'password_generator_settings',
            'passphrase_generator_settings',
            'source',
        ];
        $attributesNotToHave = [
            'id',
            'created_by',
            'modified_by',
            'created',
            'modified',
        ];

        $this->assertObjectHasAttributes($attributesToHave, $responseBody);
        if (!$isSourceDatabase) {
            $this->assertObjectNotHasAttributes($attributesNotToHave, $responseBody);
        }
    }
}
