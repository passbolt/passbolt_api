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

namespace App\Error;

use App\Error\Exception\ExceptionWithErrorsDetailInterface;
use Cake\Error\Renderer\WebExceptionRenderer;
use Psr\Http\Message\ResponseInterface;

class AppExceptionRenderer extends WebExceptionRenderer
{
    /**
     * Renders the response for the exception.
     * If the exception contains an error attribute, set it as controller view variable.
     *
     * @see \App\Controller\ErrorController
     * @return \Cake\Http\Response The response to be sent.
     */
    public function render(): ResponseInterface
    {
        if ($this->error instanceof ExceptionWithErrorsDetailInterface) {
            $this->controller->set(['body' => $this->error]);
        }

        return parent::render();
    }
}
