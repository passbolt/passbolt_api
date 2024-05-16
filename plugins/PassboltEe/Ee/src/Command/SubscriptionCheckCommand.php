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
 * @since         2.0.0
 */
namespace Passbolt\Ee\Command;

use App\Command\PassboltCommand;
use App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Subscription Check shell command.
 */
class SubscriptionCheckCommand extends PassboltCommand
{
    protected SubscriptionCheckInCommandServiceInterface $subscriptionCheckInCommandService;

    /**
     * @param \App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface $subscriptionCheckInCommandService Service checking the subscription validity.
     */
    public function __construct(
        SubscriptionCheckInCommandServiceInterface $subscriptionCheckInCommandService
    ) {
        parent::__construct();
        $this->subscriptionCheckInCommandService = $subscriptionCheckInCommandService;
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Check the subscription.'));

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $isSubscriptionValid = $this->subscriptionCheckInCommandService->check($this, $args, $io);

        if (!$isSubscriptionValid) {
            return $this->errorCode();
        }

        return $this->successCode();
    }
}
