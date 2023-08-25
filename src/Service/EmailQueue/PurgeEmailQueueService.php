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
namespace App\Service\EmailQueue;

use Cake\ORM\TableRegistry;

class PurgeEmailQueueService
{
    /**
     * Purge the email queue
     * Remove the emails that were sent or email that will to be sent because of too many
     * failed attempts.
     *
     * @return int the number of deleted emails
     */
    public function purge(): int
    {
        /** @var \EmailQueue\Model\Table\EmailQueueTable $EmailQueueTable */
        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');

        return $EmailQueueTable->deleteAll(['OR' => [
            'sent' => true,
            'send_tries >=' => 3,
        ]]);
    }
}
