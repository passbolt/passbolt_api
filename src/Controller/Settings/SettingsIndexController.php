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

namespace App\Controller\Settings;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;

class SettingsIndexController extends AppController
{
    /**
     * Settings Index action
     *
     * @return void
     */
    public function index() {
        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['header']
        ];
        $options = $this->QueryString->get($whitelist);
        $withHeader = isset($options['contain']['header']) && $options['contain']['header'] === false ? false : true;

        $settings = $this->_getSettings();

        if ($withHeader == false) {
            $this->set($settings);
            $this->set('_serialize', array_keys($settings));
            return;
        }
        $this->success(__('The operation was successful.'), $settings);
    }

    /**
     * Get the list of settings that should be displayed publicly.
     *
     * @return array
     */
    protected function _getSettings() {
        // Build settings array.
        $settings = [
            'app' => [
                'version' => [
                    'number' => Configure::read('passbolt.version'),
                    'name' => Configure::read('passbolt.name')
                ],
                'url' => Router::url('/', true),
                'debug' => Configure::read('debug') ? 1 : 0,
                'server_timezone' => date_default_timezone_get(),
                'image_storage' => [
                    'public_path' => Configure::read('ImageStorage.publicPath')
                ],
            ],
            'passbolt' => [
                'plugins' => Configure::read('passbolt.plugins')
            ],
        ];
        return $settings;
    }
}