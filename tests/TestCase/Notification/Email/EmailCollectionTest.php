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
 * @since         5.17.0
 */

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;

/**
 * @covers \App\Notification\Email\EmailCollection
 */
class EmailCollectionTest extends AppTestCase
{
    public function testEmailCollection_AddEmail_Success(): void
    {
        $email = $this->makeEmail(false);
        $collection = (new EmailCollection())->addEmail($email);
        $this->assertSame([$email], $collection->getEmails());
    }

    public function testEmailCollection_AddEmail_SkipsDisabledRecipient(): void
    {
        $collection = (new EmailCollection())->addEmail($this->makeEmail(true));
        $this->assertEmpty($collection->getEmails());
    }

    public function testEmailCollection_AddEmailToDisabledRecipient_KeepsDisabledRecipient(): void
    {
        $email = $this->makeEmail(true);
        $collection = (new EmailCollection())->addEmailToDisabledRecipient($email);
        $this->assertSame([$email], $collection->getEmails());
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * @param bool $isDisabled Whether the recipient is a disabled user.
     * @return \App\Notification\Email\Email
     */
    private function makeEmail(bool $isDisabled): Email
    {
        $factory = UserFactory::make()->user();
        if ($isDisabled) {
            $factory = $factory->disabled();
        }

        return new Email($factory->getEntity(), 'Test subject', [], 'LU/test');
    }
}
