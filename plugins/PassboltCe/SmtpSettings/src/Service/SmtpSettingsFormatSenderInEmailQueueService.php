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
 * @since         3.8.1
 */
namespace Passbolt\SmtpSettings\Service;

use Cake\Collection\CollectionInterface;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\Mailer\AbstractTransport;
use Cake\Mailer\TransportFactory;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use EmailQueue\Model\Table\EmailQueueTable;
use Passbolt\SmtpSettings\Mailer\Transport\SmtpTransport;

class SmtpSettingsFormatSenderInEmailQueueService
{
    /**
     * Attaches a beforeFind event to the email_queue table
     * If the transportation uses the DB settings, the from_email
     * and from_name fields are set to the ones defined in the
     * database settings.
     * Otherwise, the sender is defined by the file/env configuration
     *
     * @param ?\Cake\Mailer\AbstractTransport $transport Transport
     * @return void
     */
    public function formatSender(?AbstractTransport $transport = null): void
    {
        $this->getEmailQueueTable()
            ->getEventManager()
            ->on('Model.beforeFind', function (EventInterface $event, Query $query) use ($transport) {
                if (is_null($transport)) {
                    $transport = TransportFactory::get('default');
                }

                return $this->formatSenderOnBeforeFind($query, $transport);
            });
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @param \Cake\Mailer\AbstractTransport $transport Transport
     * @return \Cake\ORM\Query
     */
    protected function formatSenderOnBeforeFind(Query $query, AbstractTransport $transport): Query
    {
        // Check that the transport uses settings from the DB
        // If not, do not format the query results
        if (!$this->isSmtpSettingSourceDb($transport)) {
            return $query;
        }

        $fromEmail = $transport->getConfig('sender_email');
        $fromName = $transport->getConfig('sender_name');
        if (!$this->isSenderConfigValid($fromEmail, $fromName)) {
            return $query;
        }

        $this->formatEmailSender($query, $fromEmail, $fromName);

        return $query;
    }

    /**
     * Ensure that we target the same table as the one in the EmailQueue vendor.
     *
     * @return \EmailQueue\Model\Table\EmailQueueTable
     */
    public function getEmailQueueTable(): EmailQueueTable
    {
        /** @var \EmailQueue\Model\Table\EmailQueueTable $table */
        $table = TableRegistry::getTableLocator()->get('EmailQueue', ['className' => EmailQueueTable::class]);

        return $table;
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @param string $fromEmail sender email in the DB settings
     * @param string $fromName sender name in the DB settings
     * @return void
     */
    protected function formatEmailSender(Query $query, string $fromEmail, string $fromName): void
    {
        $query->formatResults(function (CollectionInterface $results) use ($fromEmail, $fromName) {
            return $results->map(function ($row) use ($fromEmail, $fromName) {
                $row['from_email'] = $fromEmail;
                $row['from_name'] = $fromName;

                return $row;
            });
        });
    }

    /**
     * Detects if the transport reads it SMTP settings in DB
     *
     * @param \Cake\Mailer\AbstractTransport $transport Transport
     * @return bool
     */
    protected function isSmtpSettingSourceDb(AbstractTransport $transport): bool
    {
        return ($transport instanceof SmtpTransport) && $transport->isSourceDb();
    }

    /**
     * The checks here are an overkill
     * But are however kept for debugging-sake.
     *
     * @param mixed $fromEmail From email
     * @param mixed $fromName From name
     * @return bool
     */
    protected function isSenderConfigValid($fromEmail, $fromName): bool
    {
        if (empty($fromEmail)) {
            Log::error(__('The sender email should not be empty.'));
            return false;
        } else if (!is_string($fromEmail)) {
            Log::error(__('The sender email should be a valid BMP-UTF8 string.'));
            return false;
        }
        else if (empty($fromName)) {
            Log::error(__('The sender name should not be empty.'));
            return false;
        } else if (!is_string($fromName)) {
            Log::error(__('The sender name should be a valid BMP-UTF8 string.'));
            return false;
        }

        return true;
    }
}
