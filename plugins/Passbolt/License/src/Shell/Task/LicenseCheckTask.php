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
namespace Passbolt\License\Shell\Task;

use App\Shell\AppShell;
use Cake\Chronos\Date;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\License\Utility\LicenseKey;

/**
 * License Check shell command.
 */
class LicenseCheckTask extends AppShell
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Check the license.'));

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     * @throws \Exception
     */
    public function main()
    {
        $file = Configure::read('passbolt.plugins.license.license');

        if (!file_exists($file)) {
            $this->out('');
            $this->_error(__('License error: License not found in {0}', $file), false);

            return false;
        }

        $license = new LicenseKey(file_get_contents($file));
        $validFormat = $license->validateFormat();

        if (!$validFormat) {
            $this->out('');
            $this->_error(__('Subscription key error: {0}', $license->getFirstErrorMessage()), false);
            $this->_displayErrorFooter();

            return false;
        }

        $validData = $license->validateData();
        if (!$validData) {
            $this->out('');
            $this->_error(__('Subscription key metadata error: {0}', $license->getFirstErrorMessage()), false);
        }

        $this->_displayInfo($license);

        // Conclusion.
        if (!$validData) {
            $this->_displayErrorFooter();

            return false;
        }

        $this->_displayValidFooter();

        return true;
    }

    /**
     * Display info for a valid license.
     *
     * @param \Passbolt\License\Utility\LicenseKey $license the license object
     * @return void
     * @throws \Exception
     */
    protected function _displayInfo(LicenseKey $license)
    {
        $data = $license->getData();
        $users = TableRegistry::getTableLocator()->get('Users');

        echo $this->nl();
        $this->out(__('Thanks for choosing Passbolt Pro'));
        $this->out(__('Below are your subscription key details'));
        echo $this->nl();

        // Customer id output.
        $customerIdStr = __('<error>Not Available</error>');
        if (isset($data['customer_id'])) {
            $customerIdStr = "<info>{$data['customer_id']}</info>";
        }
        $this->out(__("Customer id:\t{0}", $customerIdStr));

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
        $this->out(__("Users limit:\t{0}", $usersQtyStr));

        // Created date output.
        if (isset($data['created'])) {
            $date = Date::createFromTimestamp(strtotime($data['created']));
            $this->out(__("Valid from:\t<info>{0}</info>", $date->toFormattedDateString()));
        }

        // Expiry date output.
        if (isset($data['expiry'])) {
            $date = Date::createFromTimestamp(strtotime($data['expiry']));
            $expired = $date->lt(new Date());
            if ($expired) {
                $this->out(__("Expires on:\t<error>{0} (expired)</error>", $date->toFormattedDateString()));
            } else {
                $diffDays = $date->diffInDays(new Date());
                $msg = __("Expires on:\t<info>{0} (in {1} days)</info>", $date->toFormattedDateString(), $diffDays);
                $this->out($msg);
            }
        }
    }

    /**
     * Display valid footer.
     *
     * @return void
     */
    protected function _displayValidFooter()
    {
        echo $this->nl();
        $this->out(__('For any question / feedback / subscription renewal,'));
        $this->out(__('kindly contact us at <info>sales@passbolt.com</info>'));
        echo $this->nl();
    }

    /**
     * Display error footer.
     *
     * @return void
     */
    protected function _displayErrorFooter()
    {
        echo $this->nl();
        $this->_error(__('It looks like you could use some help.'));
        $this->_error(__('We are here for you. You can contact us at sales@passbolt.com'));
        echo $this->nl();
    }
}
