<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.2.0
 */
namespace Passbolt\Subscription\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyImportService;

/**
 * Subscription Check shell command.
 */
class SubscriptionImportCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Import a subscription key using file or text.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('file', [
            'short' => 'f',
            'help' => __('Path to subscription key file.'),
            'default' => SubscriptionKeyGetService::SUBSCRIPTION_FILE,
        ]);
        $parser->addOption('text', [
            'short' => 't',
            'help' => __('Subscription key text (Base 64).'),
            'default' => '',
        ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);
        $importService = new SubscriptionKeyImportService();

        try {
            if ($args->getOption('text')) {
                $text = $args->getOption('text');
                if (!is_string($text)) {
                    throw new SubscriptionException(__('The subscription key text is not valid.'));
                }
                $importService->import($text);
            } else {
                $file = $args->getOption('file');
                if (empty($file) || !is_string($file)) {
                    throw new SubscriptionException(__('The subscription key file is invalid.'));
                }
                $importService->importFromFile($file);
            }
        } catch (SubscriptionException $e) {
            $this->error($e->getMessage(), $io);
            $this->abort();
        }

        $this->success(__('The subscription key was successfully imported in the database.'), $io);

        return $this->successCode();
    }
}
