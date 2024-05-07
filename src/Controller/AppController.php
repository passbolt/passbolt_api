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
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
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
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
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
        $this->loadAuthenticationComponent();

        // Init user action.
        UserAction::initFromRequest($this->User->getAccessControl(), $this->request);
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
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
     * @param string|null $message message in the header section
     * @param mixed $body data for the body section
     * @return void
     */
    protected function success(?string $message = null, $body = null): void
    {
        $header = [
            'id' => UserAction::getInstance()->getUserActionId(),
            'status' => 'success',
            'servertime' => time(),
            'action' => UserAction::getInstance()->getActionId(),
            'message' => $message ?? 'The operation was successful.',
            'url' => Router::url(),
            'code' => 200,
        ];
        $this->set(compact('header', 'body'));

        $this->viewBuilder()->setOption('serialize', ['header', 'body']);
    }

    /**
     * Render an error response
     *
     * @param string|null $message optional message
     * @param mixed $body optional json response body
     * @param int|null $errorCode optional http error code
     * @return void
     */
    protected function error(?string $message = null, $body = null, ?int $errorCode = 400): void
    {
        $this->response = $this->response->withStatus($errorCode);

        $header = [
            'id' => UserAction::getInstance()->getUserActionId(),
            'status' => 'error',
            'servertime' => time(),
            'action' => UserAction::getInstance()->getActionId(),
            'message' => $message ?? 'The operation failed.',
            'url' => Router::url(),
            'code' => $errorCode,
        ];
        $this->set(compact('header', 'body'));

        $this->viewBuilder()->setOption('serialize', ['header', 'body',]);
    }

    /**
     * Render a response in legacy json format if required
     *
     * @return void
     */
    protected function setViewBuilderOptions(): void
    {
        // render a legacy JSON view by default
        if ($this->request->is('json')) {
            return;
        }
        if (!Configure::read('debug')) {
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
     * Loads the authentication component and sets up the
     * Authentication logout redirection.
     *
     * @return void
     * @throws \Exception
     */
    private function loadAuthenticationComponent(): void
    {
        $loginUrl = Router::url([
            'prefix' => 'Auth',
            'plugin' => null,
            'controller' => 'AuthLogin',
            'action' => 'loginGet',
            '_method' => 'GET',
        ]);

        $this->loadComponent('Authentication.Authentication', [
            'logoutRedirect' => $loginUrl,
        ]);
    }

    /**
     * @throws \Cake\Http\Exception\NotFoundException if request is not JSON
     * @return void
     */
    protected function assertJson(): void
    {
        if (!$this->request->is('json')) {
            throw new NotFoundException(__('Please use .json extension in URL or accept application/json.'));
        }
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if request data is not an array or is empty
     * @return void
     */
    protected function assertNotEmptyArrayData(): void
    {
        $data = $this->getRequest()->getData();
        if (!is_array($data) || !count($data)) {
            throw new BadRequestException(__('The request data can not be empty.'));
        }
    }
}
