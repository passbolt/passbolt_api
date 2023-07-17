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
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Chronos\Date;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionFormatException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionValidationException;
use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;
use Passbolt\Ee\Service\SubscriptionKeyGetService;

/**
 * Subscription Check shell command.
 */
class SubscriptionCheckCommand extends PassboltCommand
{
    /**
     * @var \Passbolt\Ee\Model\Table\SubscriptionsTable
     */
    protected $Subscriptions;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        /** @phpstan-ignore-next-line */
        $this->Subscriptions = $this->fetchTable('Passbolt/Ee.Subscriptions');
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

        try {
            $service = new SubscriptionKeyGetService();
            $subscription = $service->get(new UserAccessControl(Role::ADMIN));
        } catch (SubscriptionRecordNotFoundException $e) {
            $io->out();
            $this->error($e->getMessage(), $io);
            $this->displayErrorFooter($io);

            return $this->errorCode();
        } catch (SubscriptionFormatException $e) {
            $io->out();
            $this->error(__('Subscription key format error.'), $io);
            $this->error($e->getMessage(), $io);
            $this->displayErrorFooter($io);

            return $this->errorCode();
        } catch (SubscriptionSignatureException $e) {
            $io->out();
            $this->error(__('Subscription key signature error.'), $io);
            $this->error($e->getMessage(), $io);
            $this->displayErrorFooter($io);

            return $this->errorCode();
        } catch (SubscriptionValidationException $e) {
            $io->out();
            $this->error(__('Subscription key validation error.'), $io);
            $this->error($e->getMessage(), $io);
            $this->displayInfo($e->getDto(), $io);
            $this->displayErrorFooter($io);

            return $this->errorCode();
        }

        $this->displayInfo($subscription, $io);
        $this->displayValidFooter($io);

        return $this->successCode();
    }

    /**
     * Display info for a valid license.
     *
     * @param \Passbolt\Ee\Model\Dto\SubscriptionKeyDto $subscription the license object
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     * @throws \Exception
     */
    protected function displayInfo(SubscriptionKeyDto $subscription, ConsoleIo $io)
    {
        $data = $subscription->toArray();
        /** @var \App\Model\Table\UsersTable $users */
        $users = $this->fetchTable('Users');

        $io->nl();
        $io->out(__('Thanks for choosing Passbolt Pro'));
        $io->out(__('Below are your subscription key details'));
        $io->nl();

        // Customer id output.
        $customerIdStr = __('<error>Not Available</error>');
        if (isset($data['customer_id'])) {
            $customerIdStr = "<info>{$data['customer_id']}</info>";
        }
        $io->out(__("Customer id:\t{0}", $customerIdStr));

        // Users quantity output.
        $usersQtyStr = __('<error>Not Available</error>');
        if (isset($data['users'])) {
            try {
                // Should not break in case of database exception.
                // This can happen when Passbolt is not configured and should not prevent licence validation.
                $usersQty = $users->findActive()->count();
            } catch (\Exception $e) {
                $usersQty = 0;
            }

            if ($usersQty > $data['users']) {
                $usersQtyStr = __('<error>{0} (currently: {1}) - Exceeded</error>', $data['users'], $usersQty);
            } else {
                $usersQtyStr = __('<info>{0} (currently: {1})</info>', $data['users'], $usersQty);
            }
        }
        $io->out(__("Users limit:\t{0}", $usersQtyStr));

        // Created date output.
        if (isset($data['created'])) {
            $date = Date::createFromTimestamp(strtotime($data['created']));
            $io->out(__("Valid from:\t<info>{0}</info>", $date->toFormattedDateString()));
        }

        // Expiry date output.
        if (isset($data['expiry'])) {
            $date = Date::createFromTimestamp(strtotime($data['expiry']));
            $expired = $date->lt(new Date());
            if ($expired) {
                $io->out(__("Expires on:\t<error>{0} (expired)</error>", $date->toFormattedDateString()));
            } else {
                $diffDays = $date->diffInDays(new Date());
                $msg = __("Expires on:\t<info>{0} (in {1} days)</info>", $date->toFormattedDateString(), $diffDays);
                $io->out($msg);
            }
        }
    }

    /**
     * Display valid footer.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayValidFooter(ConsoleIo $io)
    {
        $io->nl();
        $io->out(__('For any question / feedback / subscription renewal,'));
        $io->out(__('kindly contact us at <info>sales@passbolt.com</info>'));
        $io->nl();
    }

    /**
     * Display error footer.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayErrorFooter(ConsoleIo $io)
    {
        $io->nl();
        $this->error(__('It looks like you could use some help.'), $io);
        $this->error(__('We are here for you. You can contact us at sales@passbolt.com'), $io);
        $io->nl();
    }
}
