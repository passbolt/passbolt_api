<?php
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
     * Settings visibility key.
     * @var array
     */
    const SETTINGS_VISIBILITY_KEY = 'settingsVisibility';

    /**
     * Keys that will be always whitelisted, in addition to the ones defined in config. (once logged in).
     * @var array
     */
    protected $alwaysWhiteListed = [
        'version'
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
                    'session_timeout' => Configure::read('Session.timeout', ini_get('session.gc_maxlifetime')),
                    'image_storage' => [
                        'public_path' => Configure::read('ImageStorage.publicPath')
                    ],
                ],
                'passbolt' => [
                    'edition' => Configure::read('passbolt.edition'),
                    'plugins' => $this->_getWhiteListedPluginConfig($this->_getPluginWhiteList(false)),
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
                    'plugins' => $this->_getWhiteListedPluginConfig($this->_getPluginWhiteList(true)),
                ],
            ];
        }

        return $settings;
    }

    /**
     * Get plugin options that are white listed.
     *
     * @param bool $public for public visibility or not (require log in).
     *
     * @return array list of
     */
    protected function _getPluginWhiteList($public = false)
    {
        $confKey = $public === true ? 'whiteListPublic' : 'whiteList';
        $pluginsConf = Configure::read('passbolt.plugins', []);
        $res = [];

        foreach ($pluginsConf as $pluginName => $pluginConf) {
            if (!$public) {
                foreach ($this->alwaysWhiteListed as $whiteListed) {
                    $res[] = $pluginName . '.' . $whiteListed;
                }
            }

            $whiteListOptions = Hash::extract($pluginConf, self::SETTINGS_VISIBILITY_KEY . '.' . $confKey);
            if (isset($whiteListOptions) && is_array($whiteListOptions)) {
                foreach ($whiteListOptions as $whiteList) {
                    $res[] = $pluginName . '.' . $whiteList;
                }
            }
        }

        return $res;
    }

    /**
     * Get white listed config.
     *
     * @param array $whiteList white list options array
     *
     * @return array white listed plugins configurations
     */
    protected function _getWhiteListedPluginConfig(array $whiteList)
    {
        $pluginsConfig = [];
        // Add white listed plugin options.
        foreach ($whiteList as $path) {
            if (!empty(Configure::read('passbolt.plugins.' . $path))) {
                $pluginsConfig = Hash::insert(
                    $pluginsConfig,
                    $path,
                    Configure::read('passbolt.plugins.' . $path)
                );
            }
        }

        return $pluginsConfig;
    }
}
