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
namespace Passbolt\WebInstaller\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

/**
 * Class WebInstallerController
 * This is the main controller for the web installer plugin. All the other controllers
 * will inherit from this one.
 * @package Passbolt\WebInstaller\Controller
 */
class WebInstallerController extends Controller
{
    public $components = ['Flash'];

    /**
     * Configuration key.
     * This is where the temporary config information will be stored in the session.
     */
    const CONFIG_KEY = 'Passbolt.Config';

    /**
     * Step information. Will be set by each controller.
     * @var array
     */
    protected $stepInfo = [
        'previous' => null,
        'current' => null,
        'next' => null,
        'template' => null,
    ];

    /**
     * Initialize implementation.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['current'] = $this->request->getRequestTarget();
    }

    /**
     * Before filter.
     * Do not let the user proceed if the configuration is not found in the session.
     * Instead redirect him to the first step.
     * @param Event $event event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $session = $this->request->getSession();
        $config = $session->read(self::CONFIG_KEY);

        if ($config === null) {
            if (PASSBOLT_IS_CONFIGURED) {
                throw new ForbiddenException(__('The web installer cannot be used since this instance of passbolt is already configured.'));
            }

            $session->write(self::CONFIG_KEY, []);

            // Redirect to start page if not already there.
            $isStartPage = $this->request->controller == 'SystemCheck' && $this->request->action == 'index';
            if (!$isStartPage) {
                $this->Flash->error(__('The session has expired. Please start the configuration again.'));

                return $this->redirect('install');
            }
        }
    }

    /**
     * Before render.
     * @param Event $event event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('stepInfo', $this->stepInfo);
        $this->set('navigationSections', $this->_getNavigationSections());
    }

    /**
     * Return the navigation items.
     * @return array
     */
    protected function _getNavigationSections()
    {
        $session = $this->request->getSession();
        $pluginLicenseEnabled = !empty(Configure::read('passbolt.plugins.license'));
        $hasAdmin = $session->read('Passbolt.Config.hasExistingAdmin');
        $sections = [];

        $sections['system_check'] = __('System check');
        if ($pluginLicenseEnabled) {
            $sections['license_key'] = __('Subscription key');
        }
        $sections['database'] = __('Database');
        $sections['server_keys'] = __('Server keys');
        $sections['emails'] = __('Emails');
        $sections['options'] = __('Options');
        $sections['installation'] = __('Installation');
        if (!$hasAdmin) {
            $sections['first_user'] = __('First user');
        }
        $sections['end'] = __('That\'s it!');

        return $sections;
    }

    /**
     * Save data in session for the corresponding key.
     * @param string $key configuration key
     * @param array $data data to be saved
     * @return void
     */
    protected function _saveConfiguration($key, $data)
    {
        $session = $this->request->getSession();
        $session->write(self::CONFIG_KEY . '.' . $key, $data);
    }

    /**
     * Load a previously saved configuration. (in session).
     * @param string $key configuration key
     * @return void
     */
    protected function _loadSavedConfiguration($key)
    {
        $session = $this->request->getSession();
        $savedConfiguration = $this->request->getSession()->read(self::CONFIG_KEY . '.' . $key);
        if (!empty($savedConfiguration)) {
            $this->request->data = $this->request->getSession()->read(self::CONFIG_KEY . '.' . $key);
        }
    }

    /**
     * Error handler.
     * @param string $message error message
     * @return void
     */
    protected function _error($message)
    {
        $this->Flash->error($message);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Success handler.
     * @return void
     */
    protected function _success()
    {
        $this->redirect($this->stepInfo['next']);
    }
}
