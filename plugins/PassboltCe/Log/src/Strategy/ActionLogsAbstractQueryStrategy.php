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
 * @since         3.12.0
 */
namespace Passbolt\Log\Strategy;

use App\Utility\UserAccessControl;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Passbolt\Log\Model\Entity\ActionLog;

abstract class ActionLogsAbstractQueryStrategy
{
    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * @var \Cake\Http\Response
     */
    protected $response;

    /**
     * @var \App\Utility\UserAccessControl
     */
    protected $uac;

    /**
     * @param \Cake\Http\ServerRequest $request Server request
     * @param \Cake\Http\Response $response Response
     * @param \App\Utility\UserAccessControl $uac User Access control
     */
    final public function __construct(ServerRequest $request, Response $response, UserAccessControl $uac)
    {
        $this->request = $request;
        $this->response = $response;
        $this->uac = $uac;
    }

    /**
     * Fetch additional data based on the newly saved action log
     * Return false if the action log should not be stored by the engine
     *
     * @param \Passbolt\Log\Model\Entity\ActionLog $actionLog Action log entity
     * @return string|false
     */
    abstract public function query(ActionLog $actionLog);

    /**
     * @return \Cake\Http\ServerRequest
     */
    final public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * @return \Cake\Http\Response
     */
    final public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return \App\Utility\UserAccessControl
     */
    final public function getUac(): UserAccessControl
    {
        return $this->uac;
    }
}
