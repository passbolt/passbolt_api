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

namespace App\Error;

use Cake\Error\ExceptionRenderer;

class AppExceptionRenderer extends ExceptionRenderer
{
    /**
     * Renders the response for the exception.
     * If the exception contains an error attribute, set it as controller view variable.
     *
     * @see \App\Controller\ErrorController
     * @return \Cake\Http\Response The response to be sent.
     */
    public function render()
    {
        $class = get_class($this->error);
        $exceptionWithErrorSet = [
            'App\Error\Exception\CustomValidationException',
            'App\Error\Exception\ValidationException'
        ];
        if (in_array($class, $exceptionWithErrorSet)) {
            $this->controller->set(['body' => $this->error]);
        }

        return parent::render();
    }
}
