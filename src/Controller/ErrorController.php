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

use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Text;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class ErrorController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if ($this->request->is('json')) {
            $this->set([
                'header' => [
                    'id' => Text::uuid(),
                    'status' => 'error',
//                    'title' => 'app_' . $prefix . '_' . $action . '_error',
                    'servertime' => time(),
                    'message' => $this->viewVars['message'],
                    'code' => $this->viewVars['code'],
                    'url' => Router::url(),
                ],
                'body' => Hash::get($this->viewVars, 'body', null),
                '_serialize' => ['header', 'body']
            ]);

            // render a legacy JSON view by default
            $apiVersion = $this->request->getQuery('api-version');
            if (!isset($apiVersion) || $apiVersion === 'v1') {
                $this->viewBuilder()->setClassName('LegacyJson');
            } else {
                // If the body is a validationException, use the exception errors as body.
                $body = Hash::get($this->viewVars, 'body', null);
                if (is_object($body) && get_class($body) === 'App\Error\Exception\ValidationRuleException') {
                    $this->set('body', $body->getErrors());
                }
            }
        }
        $this->viewBuilder()->setTemplatePath('Error');
    }
}
