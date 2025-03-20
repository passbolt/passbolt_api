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

use App\Error\Exception\ExceptionWithErrorsDetailInterface;
use App\Log\Formatter\JsonTraceFormatter;
use App\Utility\UserAction;
use App\View\AjaxView;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\View\JsonView;
use Exception;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 *
 * Note: We are not extending from AppController because it can cause problems when loading Authentication component.
 *
 * @see: https://github.com/cakephp/cakephp/issues/17655
 * @property \App\Controller\Component\UserComponent $User
 * @property \App\Controller\Component\QueryStringComponent $QueryString
 */
class ErrorController extends Controller
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('User');
        $this->loadComponent('QueryString');

        // Init user action.
        UserAction::initFromRequest($this->User->getAccessControl(), $this->request);
    }

    /**
     * @inheritDoc
     */
    public function viewClasses(): array
    {
        return [JsonView::class, AjaxView::class];
    }

    /**
     * @inheritDoc
     */
    public function beforeRender(EventInterface $event)
    {
        // Required to support automatic view switching for 'ajax', which was supported by deprecated RequestHandlerComponent
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }

        if ($this->request->is('json')) {
            // If the body is a that exposes the getErrors functionality
            // for example ValidationRulesException
            $error = $this->viewBuilder()->getVar('error');

            $body = '';
            if ($error instanceof ExceptionWithErrorsDetailInterface) {
                $body = $error->getErrors();
            }
            $is500Exception = ($error instanceof Exception) && ($error->getCode() === 500);
            if ($is500Exception) {
                Log::error(
                    $error->getMessage(),
                    [JsonTraceFormatter::TRACE => $error->getTraceAsString()]
                );
            }
            $header = [
                'id' => UserAction::getInstance()->getUserActionId(),
                'status' => 'error',
                'servertime' => time(),
                'action' => UserAction::getInstance()->getActionId(),
                'message' => $this->viewBuilder()->getVar('message'),
                'url' => Router::url(),
                'code' => $this->viewBuilder()->getVar('code'),
            ];
            $this->set(compact('header', 'body'));

            $this->viewBuilder()->setOption('serialize', ['header', 'body',]);
        }

        $this->viewBuilder()->setTemplatePath('Error');
    }
}
