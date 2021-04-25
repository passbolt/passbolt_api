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
use App\Utility\UserAction;
use Cake\Event\EventInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class ErrorController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeRender(EventInterface $event)
    {
        if ($this->request->is('json')) {
            // If the body is a that exposes the getErrors functionality
            // for example ValidationRulesException
            $error = $this->viewBuilder()->getVar('error');

            $body = '';
            if ($error instanceof ExceptionWithErrorsDetailInterface) {
                $body = $error->getErrors();
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
            $this->setViewBuilderOptions();

            // render a legacy JSON view by default
            $apiVersion = $this->request->getQuery('api-version');
            if ($apiVersion === 'v1') {
                throw new InternalErrorException('API v1 support is deprecated in this version.');
            }
        }
        $this->viewBuilder()->setTemplatePath('Error');
    }
}
