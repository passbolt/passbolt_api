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
namespace Passbolt\WebInstaller\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Routing\Router;
use Passbolt\WebInstaller\Utility\WebInstaller;

/**
 * Class WebInstallerController
 * This is the main controller for the web installer plugin. All the other controllers
 * will inherit from this one.
 *
 * @package Passbolt\WebInstaller\Controller
 */
class WebInstallerController extends Controller
{
    /**
     * The web installer model
     *
     * @var \Passbolt\WebInstaller\Utility\WebInstaller
     */
    protected $webInstaller;

    /**
     * Step information. Will be set by each controller.
     *
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
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->stepInfo['current'] = $this->request->getRequestTarget();
        $session = $this->request->getSession();
        $this->webInstaller = new WebInstaller($session);
        $this->loadComponent('Flash');
    }

    /**
     * Before filter.
     * Do not let the user proceed if the configuration is not found in the session.
     * Instead redirect him to the first step.
     *
     * @param \Cake\Event\EventInterface $event event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(\Cake\Event\EventInterface $event): ?Response
    {
        parent::beforeFilter($event);

        $systemCheckCtrl = ($this->request->getParam('controller') === 'SystemCheck');
        $systemCheckPage = ($this->request->getParam('action') === 'index');
        $onSystemCheckPage = $systemCheckCtrl && $systemCheckPage;
        if (!$this->webInstaller->isInitialized() && !$onSystemCheckPage) {
            $this->Flash->error(__('The session has expired. Please start the configuration again.'));

            return $this->redirect(Router::url('/install/system_check', true));
        }

        return null;
    }

    /**
     * Before render.
     *
     * @param \Cake\Event\EventInterface $event event
     * @return void
     */
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);
        $this->set('stepInfo', $this->stepInfo);
        $this->set('navigationSections', $this->getNavigationSections());
    }

    /**
     * Return the navigation items.
     *
     * @return array
     */
    protected function getNavigationSections(): array
    {
        $pluginEeEnabled = !empty(Configure::read('passbolt.plugins.ee'));
        $hasAdmin = $this->webInstaller->getSettings('hasAdmin');
        $hasSmtpSettings = $this->webInstaller->getSettings('hasSmtpSettings');
        $sections = [];

        $sections['system_check'] = __('System check');
        if ($pluginEeEnabled) {
            $sections['subscription_key'] = __('Subscription key');
        }
        $sections['database'] = __('Database');
        $sections['server_keys'] = __('Server keys');
        $sections['options'] = __('Options');
        if (!$hasSmtpSettings) {
            $sections['emails'] = __('Emails');
        }
        if (!$hasAdmin) {
            $sections['first_user'] = __('First user');
        }
        $sections['installation'] = __('Installation');
        $sections['end'] = __('That\'s it!');

        return $sections;
    }

    /**
     * Error handler.
     *
     * @param string $message error message
     * @return void
     */
    protected function _error(string $message): void
    {
        $this->Flash->error($message);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Success handler.
     *
     * @return void
     */
    protected function goToNextStep(): void
    {
        $this->redirect(Router::url($this->stepInfo['next'], true));
    }
}
