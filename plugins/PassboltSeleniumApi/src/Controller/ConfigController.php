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
namespace PassboltSeleniumApi\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

class ConfigController extends AppController
{
    /**
     * Extra config file name and path
     */
    const EXTRA_CONFIG_FILENAME = 'core_extra_config.php';
    const EXTRA_CONFIG_PATH = TMP . 'selenium' . DS;
    const EXTRA_CONFIG_FILE = self::EXTRA_CONFIG_PATH . self::EXTRA_CONFIG_FILENAME;

    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
            $this->Auth->allow('setExtraConfig');
            $this->Auth->allow('resetExtraConfig');
            $this->Auth->allow('index');
        } else {
            throw new NotFoundException();
        };

        return parent::beforeFilter($event);
    }

    /**
     * Return current server configuration
     *
     * @return void
     */
    public function index()
    {
        $config = Configure::read();
        $this->success(__('Configuration read successfully.'), $config);
    }

    /**
     * Set extra selenium config
     *
     * @return void
     */
    public function setExtraConfig()
    {
        $data = $this->request->getData();
        $seleniumExtraConfig = '<?php return ' . var_export($data, true) . ';';
        $fileName = self::EXTRA_CONFIG_PATH . self::EXTRA_CONFIG_FILENAME;
        file_put_contents($fileName, $seleniumExtraConfig);
        $this->success(__('Additional configuration added in: {0}', $fileName));
    }

    /**
     * Reset extra selenium config
     *
     * @return void
     */
    public function resetExtraConfig()
    {
        $fileName = self::EXTRA_CONFIG_PATH . self::EXTRA_CONFIG_FILENAME;
        if (file_exists($fileName)) {
            unlink($fileName);
            $this->success(__('Additional configuration deleted: {0}', $fileName));
        } else {
            $this->success(__('No extra config to reset.'));
        }
    }
}
