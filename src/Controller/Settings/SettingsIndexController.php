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
use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Hash;

class SettingsIndexController extends AppController
{
    /**
     * Configuration white list while accessing the entry point as anonymous.
     * @var array
     */
    protected $pluginConfigurationWhiteList = [
        'rememberMe.options'
    ];

    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Users');
        $this->Auth->allow('index');

        return parent::beforeFilter($event);
    }

    /**
     * Settings Index action
     *
     * @return void
     */
    public function index()
    {
        $role = $this->User->role();
        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['header']
        ];
        $options = $this->QueryString->get($whitelist);
        $withHeader = isset($options['contain']['header']) && $options['contain']['header'] === false ? false : true;

        $settings = $this->_getSettings($role);

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
     * @param string $role role of the user accessing the settings.
     *
     * @return array
     */
    protected function _getSettings(string $role)
    {
        if ($role !== Role::GUEST) {
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
                    'edition' => Configure::read('passbolt.edition'),
                    'plugins' => Configure::read('passbolt.plugins', []),
                ],
            ];
        } else {
            // If user is Guest.
            $settings = [
                'app' => [
                    'url' => Router::url('/', true),
                ],
                'passbolt' => [
                    'edition' => Configure::read('passbolt.edition'),
                    'plugins' => array_fill_keys(array_keys(Configure::read('passbolt.plugins'), []), []),
                ],
            ];

            // Add white listed plugin options.
            foreach ($this->pluginConfigurationWhiteList as $path) {
                if (!empty(Configure::read('passbolt.plugins.' . $path))) {
                    $settings['passbolt']['plugins'] = Hash::insert(
                        $settings['passbolt']['plugins'],
                        $path,
                        Configure::read('passbolt.plugins.' . $path)
                    );
                }
            }
        }

        return $settings;
    }
}
