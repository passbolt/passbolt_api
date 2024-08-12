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
 * @since         4.8.0
 */

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class ShowQueuedEmailsCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Shows records from email_queue table.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('limit', [
            'short' => 'l',
            'help' => __('Number of records to show.'),
            'default' => 15,
        ]);

        $parser->addOption('failed', [
            'help' => __('Return only failed records.'),
            'boolean' => true,
        ]);

        $parser->addOption('oldest', [
            'help' => __('Returns older records.'),
            'boolean' => true,
        ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $this->assertOptions($args->getOptions(), $io);

        $limit = (int)$args->getOption('limit');
        $failed = $args->getOption('failed');
        $oldest = $args->getOption('oldest');

        $io->out('List of queued emails:');

        $data = [];

        // Header
        $data[] = [
            __('Email'),
            __('Subject'),
            __('Error'),
            __('Created'),
            __('Sent'),
        ];

        /** @var \EmailQueue\Model\Table\EmailQueueTable $emailQueueTable */
        $emailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $order = $oldest ? 'ASC' : 'DESC';
        $queueEmails = $emailQueueTable->find()
            ->select(['email', 'subject', 'error', 'created', 'sent'])
            ->limit($limit)
            ->order([$emailQueueTable->aliasField('created') => $order]);

        if ($failed) {
            $queueEmails->where([$emailQueueTable->aliasField('error') . ' IS NOT' => null]);
        }

        $queueEmails = $queueEmails->all();

        if ($queueEmails->isEmpty()) {
            $io->out('No records found.');

            return $this->successCode();
        }

        /** @var \Cake\ORM\Entity $queueEmail */
        foreach ($queueEmails as $queueEmail) {
            $data[] = [
                // sequence matters, see headers (first element)
                $queueEmail->get('email'),
                $queueEmail->get('subject'),
                $queueEmail->get('error'),
                $queueEmail->get('created')->format('Y-m-d H:i:s'),
                $queueEmail->get('sent'),
            ];
        }

        $io->helper('Table')->output($data);

        return $this->successCode();
    }

    /**
     * @param array $options Options.
     * @param \Cake\Console\ConsoleIo $io I/O object.
     * @return void
     */
    private function assertOptions(array $options, ConsoleIo $io): void
    {
        if (!Validation::range($options['limit'], 1, 100)) {
            $this->error(__('Limit option value should be between 1 and 100.'), $io);
            $this->abort();
        }
    }
}
