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

namespace App\Controller;

use App\Utility\UserAction;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property \App\Controller\Component\UserComponent $User
 * @property \App\Controller\Component\QueryStringComponent $QueryString
 * @property \Cake\Controller\Component\AuthComponent $Auth
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     * Used to add common initialization code like loading components.
     *
     * @return void
     * @throws \Exception If a component class cannot be found.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('User');
        $this->loadComponent('QueryString');

        /*
         * Auth Component
         */
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Gpg',
            ],
            'loginAction' => [
                'prefix' => 'Auth',
                'controller' => 'AuthLogin',
                'action' => 'loginGet',
                '_method' => 'GET',
                'plugin' => null,
            ],
        ]);

        // Init user action.
        UserAction::initFromRequest($this->User->getAccessControl(), $this->request);

        // Tell the browser to force HTTPS use
        if (Configure::read('passbolt.ssl.force')) {
            $this->response = $this->response
                ->withHeader('strict-transport-security', 'max-age=31536000; includeSubDomains');
        }
    }

    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $safeMode = !Configure::read('debug');
        $safeMode = $safeMode && preg_match('/^https/', Configure::read('App.fullBaseUrl'));
        $this->set('safeMode', $safeMode);

        return parent::beforeFilter($event);
    }

    /**
     * Success renders set the variables used to render the json view
     * All passbolt response contains an header (metadata like status) an a body (data)
     *
     * @param string $message message in the header section
     * @param array  $body data for the body section
     * @return void
     */
    protected function success($message = null, $body = null)
    {
        $prefix = $this->request->getParam('prefix') ?? strtolower($this->request->getParam('prefix'));
        $action = $this->request->getParam('action');

        $this->set([
            'header' => [
                'id' => UserAction::getInstance()->getUserActionId(),
                'status' => 'success',
                'servertime' => time(),
                'title' => 'app_' . $prefix . '_' . $action . '_success',
                'action' => UserAction::getInstance()->getActionId(),
                'message' => $message,
                'url' => Router::url(),
                'code' => 200,
            ],
            'body' => $body,
            '_serialize' => ['header', 'body'],
        ]);
        $this->setViewBuilderOptions();
    }

    /**
     * Render an error response
     *
     * @param string $message optional message
     * @param mixed  $body optional json reponse body
     * @param int    $errorCode optional http error code
     * @return void
     */
    protected function error($message = null, $body = null, $errorCode = 200)
    {
        if ($errorCode !== 200) {
            $this->response = $this->response->withStatus($errorCode);
        }

        $this->set([
            'header' => [
                'id' => UserAction::getInstance()->getUserActionId(),
                'status' => 'error',
                'servertime' => time(),
                'action' => UserAction::getInstance()->getActionId(),
                'message' => $message,
                'url' => Router::url(),
                'code' => $errorCode,
            ],
            'body' => $body,
            '_serialize' => ['header', 'body'],
        ]);
        $this->setViewBuilderOptions();
    }

    /**
     * Render a response in legacy json format if required
     *
     * @return void
     */
    protected function setViewBuilderOptions()
    {
        // render a legacy JSON view by default
        if ($this->request->is('json')) {
            if ($this->getApiVersion() === 'v1') {
                throw new InternalErrorException(__('API v1 support is deprecated in this version.'));
            }
        } elseif (!Configure::read('debug')) {
            // Render a page not found if there is not template for the endpoint
            // and the request is specifically not json format
            // examples:
            // - POST /users/register.json will be a json response
            // - POST /users/register will be a user registration form in html
            // - POST /users/edit.json will be a json response
            // - POST /users/edit does not exist as html for so will trigger 404
            $template = $this->viewBuilder()->getTemplate();
            if (!isset($template)) {
                throw new NotFoundException(__('Page not found.'));
            }
        }
    }

    /**
     * Get the request api version.
     *
     * @return string
     */
    public function getApiVersion()
    {
        $apiVersion = $this->request->getQuery('api-version');
        // Default to v2 in v3
        if (!isset($apiVersion)) {
            return 'v2';
        }

        // Reformat api-version
        if ($apiVersion === '1') {
            return 'v1';
        }
        if ($apiVersion === '2') {
            return 'v2';
        }

        // Return what is given
        return $apiVersion;
    }
}
