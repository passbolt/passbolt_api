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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiryPolicies\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Exception\StopException;
use Cake\Event\EventDispatcherTrait;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService;

/**
 * NotifyAboutExpiredResourcesCommand class
 */
class PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand extends PassboltCommand
{
    use EventDispatcherTrait;

    protected PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService $service;

    /**
     * @param \Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService $service Service to get owners to be notified
     */
    public function __construct(PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return $parser->setDescription([
            __('Notify resource owners about their expired passwords.'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);
        // Root user is not allowed to execute this command.
        $this->assertCurrentProcessUser($io);

        try {
            $nUsers = $this->service->notifyUsers()->all()->count();
        } catch (StopException $e) {
            $io->info($e->getMessage());

            return $this->successCode();
        }

        $io->success(__('{0} resource owners were notified.', $nUsers));

        return $this->successCode();
    }
}
