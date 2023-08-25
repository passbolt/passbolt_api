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

namespace App\Test\TestCase\Service\EmailQueue;

use App\Service\EmailQueue\PurgeEmailQueueService;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PurgeEmailQueueServiceTest extends TestCase
{
    public function testPurgeEmailQueueService_SuccessEmptyQueue(): void
    {
        $this->assertEquals(0, (new PurgeEmailQueueService())->purge());
    }

    public function testPurgeEmailQueueService_SuccessSent(): void
    {
        /** @var EmailQueueTable $EmailQueueTable */
        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $this->assertTrue($EmailQueueTable->enqueue('test@passbolt.com', ['test email']));

        // Keep unsent
        $this->assertEquals(0, (new PurgeEmailQueueService())->purge());

        // Delete sent
        $email = $EmailQueueTable->find()->firstOrFail();
        $EmailQueueTable->patchEntity($email, ['sent' => 1]);
        $EmailQueueTable->saveOrFail($email);

        $this->assertEquals(1, (new PurgeEmailQueueService())->purge());
    }

    public function testPurgeEmailQueueService_SuccessSendTries(): void
    {
        /** @var EmailQueueTable $EmailQueueTable */
        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $this->assertTrue($EmailQueueTable->enqueue('test@passbolt.com', ['test email']));

        // Keep retry < 3
        $email = $EmailQueueTable->find()->firstOrFail();
        $EmailQueueTable->patchEntity($email, ['send_tries' => 1]);
        $EmailQueueTable->saveOrFail($email);
        $this->assertEquals(0, (new PurgeEmailQueueService())->purge());

        // Delete retry >= 3
        $email = $EmailQueueTable->find()->firstOrFail();
        $EmailQueueTable->patchEntity($email, ['send_tries' => 3]);
        $EmailQueueTable->saveOrFail($email);

        $this->assertEquals(1, (new PurgeEmailQueueService())->purge());
    }
}
