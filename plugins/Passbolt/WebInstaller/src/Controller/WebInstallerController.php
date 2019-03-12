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
namespace Passbolt\WebInstaller\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Passbolt\WebInstaller\Utility\WebInstaller;

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
     * The web installer model
     */
    protected $webInstaller;

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
        $session = $this->request->getSession();
        $this->webInstaller = new WebInstaller($session);
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

        $systemCheckCtrl = ($this->request->getParam('controller') === 'SystemCheck');
        $systemCheckPage = ($this->request->getParam('action') === 'index');
        $onSystemCheckPage = $systemCheckCtrl && $systemCheckPage;
        if (!$this->webInstaller->isInitialized() && !$onSystemCheckPage) {
            $this->Flash->error(__('The session has expired. Please start the configuration again.'));

            return $this->redirect('install/system_check');
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
        $this->set('navigationSections', $this->getNavigationSections());
    }

    /**
     * Return the navigation items.
     * @return array
     */
    protected function getNavigationSections()
    {
        $pluginLicenseEnabled = !empty(Configure::read('passbolt.plugins.license'));
        $hasAdmin = $this->webInstaller->getSettings('hasAdmin');
        $sections = [];

        $sections['system_check'] = __('System check');
        if ($pluginLicenseEnabled) {
            $sections['license_key'] = __('Subscription key');
        }
        $sections['database'] = __('Database');
        $sections['server_keys'] = __('Server keys');
        $sections['emails'] = __('Emails');
        $sections['options'] = __('Options');
        if (!$hasAdmin) {
            $sections['first_user'] = __('First user');
        }
        $sections['installation'] = __('Installation');
        $sections['end'] = __('That\'s it!');

        return $sections;
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
    protected function goToNextStep()
    {
        $this->redirect($this->stepInfo['next']);
    }
}
