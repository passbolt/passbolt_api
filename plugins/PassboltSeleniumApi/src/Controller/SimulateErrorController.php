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
namespace PassboltSeleniumApi\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;

class SimulateErrorController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('error404');
        $this->Auth->allow('error403');
        $this->Auth->allow('error400');
        $this->Auth->allow('error500');

        return parent::beforeFilter($event);
    }

    /**
     * Simulate error 404
     *
     * @throws NotFoundException
     * @return void
     */
    public function error404()
    {
        throw new NotFoundException();
    }

    /**
     * Simulate error 403
     *
     * @throws ForbiddenException
     * @return void
     */
    public function error403()
    {
        throw new ForbiddenException();
    }

    /**
     * Simulate error 400
     *
     * @throws BadRequestException
     * @return void
     */
    public function error400()
    {
        throw new BadRequestException();
    }

    /**
     * Simulate error 500
     *
     * @throws InternalErrorException
     * @return void
     */
    public function error500()
    {
        throw new InternalErrorException();
    }
}
