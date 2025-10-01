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
 * @since         5.4.0
 */

namespace Passbolt\ResourceTypes\Test\TestCase\Model\Entity;

use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Passbolt\ResourceTypes\Model\Entity\ResourceType
 */
class ResourceTypeTest extends TestCase
{
    public function testResourceTypeSlugs(): void
    {
        // ACHTUNG! These constants should be immutable, if you have to adapt the existing assertions it means trouble.
        $this->assertEquals(ResourceType::SLUG_PASSWORD_STRING, 'password-string');
        $this->assertEquals(ResourceType::SLUG_PASSWORD_AND_DESCRIPTION, 'password-and-description');
        $this->assertEquals(ResourceType::SLUG_STANDALONE_TOTP, 'totp');
        $this->assertEquals(ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP, 'password-description-totp');
        $this->assertEquals(ResourceType::SLUG_V5_PASSWORD_STRING, 'v5-password-string');
        $this->assertEquals(ResourceType::SLUG_V5_DEFAULT, 'v5-default');
        $this->assertEquals(ResourceType::SLUG_V5_TOTP_STANDALONE, 'v5-totp-standalone');
        $this->assertEquals(ResourceType::SLUG_V5_DEFAULT_WITH_TOTP, 'v5-default-with-totp');
        $this->assertEquals(ResourceType::SLUG_V5_CUSTOM_FIELD_STANDALONE, 'v5-custom-fields');
        $this->assertEquals(ResourceType::SLUG_V5_NOTE, 'v5-note');
    }
}
