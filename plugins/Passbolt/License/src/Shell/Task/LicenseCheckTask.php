<?php
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
use Cake\Core\Configure;
use Passbolt\License\Utility\License;

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
     */
    public function main()
    {
        $file = Configure::read('passbolt.plugins.license.license');

        if (!file_exists($file)) {
            $this->out('');
            $this->_error(__('License error: License not found in {0}', $file), false);

            return false;
        }

        $license = new License(file_get_contents($file));
        try {
            $license->validate();
        } catch (\Exception $e) {
            $this->out('');
            $this->_error(__('License error: {0}', $e->getMessage()), false);

            return false;
        }

        return true;
    }
}
