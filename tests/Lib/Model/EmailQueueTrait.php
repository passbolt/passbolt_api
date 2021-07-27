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

use Cake\ORM\TableRegistry;

trait EmailQueueTrait
{
    /**
     * Asserts that an email with given properties is in the email queue.
     */
    protected function assertEmailIsInQueue(array $properties)
    {
        $EmailQueues = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');

        $isFound = $EmailQueues->find()->where($properties)->count() > 0;
        $this->assertTrue($isFound, 'The email is not in the email queue.');
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
        $EmailQueues = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $this->assertSame($n, $EmailQueues->find()->count());
    }

    /**
     * Asserts that no email is in the email queue.
     */
    protected function assertEmailQueueIsEmpty()
    {
        $this->assertEmailQueueCount(0);
    }
}
