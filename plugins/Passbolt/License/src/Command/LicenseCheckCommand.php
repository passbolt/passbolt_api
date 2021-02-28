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
namespace Passbolt\License\Command;

use App\Command\PassboltCommand;
use Cake\Chronos\Date;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\License\Utility\LicenseKey;

/**
 * License Check shell command.
 */
class LicenseCheckCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Check the license.'));

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);
        $file = Configure::read('passbolt.plugins.license.license');

        if (!file_exists($file)) {
            $io->out();
            $this->error(__('License error: License not found in {0}', $file), $io);

            return $this->errorCode();
        }

        $license = new LicenseKey(file_get_contents($file));
        $validFormat = $license->validateFormat();

        if (!$validFormat) {
            $io->out();
            $this->error(__('Subscription key error: {0}', $license->getFirstErrorMessage()), $io);
            $this->displayErrorFooter($io);

            return $this->errorCode();
        }

        $validData = $license->validateData();
        if (!$validData) {
            $io->out();
            $this->error(__('Subscription key metadata error: {0}', $license->getFirstErrorMessage()), $io);
        }

        $this->displayInfo($license, $io);

        // Conclusion.
        if (!$validData) {
            $this->displayErrorFooter($io);

            return $this->errorCode();
        }

        $this->displayValidFooter($io);

        return $this->successCode();
    }

    /**
     * Display info for a valid license.
     *
     * @param \Passbolt\License\Utility\LicenseKey $license the license object
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     * @throws \Exception
     */
    protected function displayInfo(LicenseKey $license, ConsoleIo $io)
    {
        $data = $license->getData();
        $users = TableRegistry::getTableLocator()->get('Users');

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
