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
 * @since         3.3.0
 */
namespace App\Test\Lib\Model;

use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

trait EmailQueueTrait
{
    /**
     * Asserts that an email with given properties is in the email queue.
     */
    protected function assertEmailIsInQueue(array $properties)
    {
        $this->assertTrue(EmailQueueFactory::count() > 0, 'The email is not in the email queue.');
    }

    /**
     * Asserts that an email with given recipient is in the email queue.
     */
    protected function assertEmailWithRecipientIsInQueue(string $email)
    {
        $this->assertEmailIsInQueue(compact('email'));
    }

    /**
     * Asserts that n emails are in the email queue.
     */
    protected function assertEmailQueueCount(int $n)
    {
        $this->assertSame($n, EmailQueueFactory::find()->count());
    }

    /**
     * Asserts that no email is in the email queue.
     */
    protected function assertEmailQueueIsEmpty()
    {
        $this->assertEmailQueueCount(0);
    }

    /**
     * Asserts that all emails of a given recipient have a locale set and equal to the expectation.
     *
     * @param string $email Recipient
     * @param string $expectedLocale Expected locale
     */
    protected function assetEmailLocale(string $email, string $expectedLocale)
    {
        $emails = EmailQueueFactory::find()->where(compact('email'));
        $this->assertTrue($emails->count() > 0);
        foreach ($emails as $email) {
            $this->assertTextEquals($expectedLocale, $email->get('template_vars')['locale']);
        }
    }
}
