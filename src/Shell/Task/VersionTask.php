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
 * @since         2.0.0
 */
namespace App\Shell\Task;

use App\Shell\AppShell;
use Cake\Core\Configure;

class VersionTask extends AppShell
{
    /**
     * @inheritDoc
     */
    public function getOptionParser(): \Cake\Console\ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Print version number for the passbolt application.'));

        return $parser;
    }

    /**
     * Main debug email task
     *
     * @return bool
     */
    public function main()
    {
        $v = 'Passbolt ' . strtoupper(Configure::read('passbolt.edition')) . ' ';
        $v .= Configure::read('passbolt.version') . "\n" . 'Cakephp ' . Configure::version();
        $this->out($v);

        return true;
    }
}
