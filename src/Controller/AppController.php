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
namespace App\Controller;

use App\Controller\Events\EmailsListener;
use App\Auth\GpgAuthenticate;
use Cake\Core\Configure;
use Cake\Controller\Controller;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Text;
use Cake\Routing\Router;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('User');

        /*
         * Auth Component
         */
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Gpg'
            ],
            'loginAction' => [
                'prefix' => 'Auth',
                'controller' => 'AuthLogin',
                'action' => 'loginGet',
                '_method' => 'GET'
            ],
        ]);

        $this->response = $this->response
            ->withHeader('X-GPGAuth-Version', '1.3.0')
            ->withHeader('X-GPGAuth-Login-URL', '/auth/login')
            ->withHeader('X-GPGAuth-Logout-URL', '/auth/logout')
            ->withHeader('X-GPGAuth-Verify-URL', '/auth/verify')
            ->withHeader('X-GPGAuth-Pubkey-URL', '/auth/verify.json');

        /*
         * Email notifications
         */
        $emails = new EmailsListener();
        $this->getEventManager()->on($emails);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * Success renders set the variables used to render the json view
     * All passbolt response contains an header (metadata like status) an a body (data)
     *
     * @param array $body data for the body section
     * @return void
     */
    protected function success($body = null)
    {
        $this->set([
            'header' => [
                'id' => Text::uuid(),
                'status' => 'success',
                'servertime' => time(),
                'message' => null,
                'url' => Router::url(),
            ],
            'body' => $body,
            '_serialize' => ['header', 'body']
        ]);
        $this->renderLegacyJson();
    }

    protected function error($message = null, $body = null)
    {
        $this->set([
            'header' => [
                'id' => Text::uuid(),
                'status' => 'error',
                'servertime' => time(),
                'message' => $message,
                'url' => Router::url(),
            ],
            'body' => $body,
            '_serialize' => ['header', 'body']
        ]);
        $this->renderLegacyJson();
    }

    protected function renderLegacyJson()
    {
        // render a legacy JSON view by default
        if($this->request->is('json')) {
            $apiVersion = $this->request->getQuery('api-version');
            if (!isset($apiVersion) || $apiVersion === 'v1') {
                $this->viewBuilder()->setClassName('LegacyJson');
            }
        } else if (!Configure::read('debug')) {
            $template = $this->viewBuilder()->getTemplate();
            if (!isset($template)) {
                throw new NotFoundException(__('Page not found.'));
            }
        }
    }
}
