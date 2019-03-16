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
namespace App\Controller\Healthcheck;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;

class HealthcheckIndexController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index']);

        return parent::beforeFilter($event);
    }

    /**
     * Index
     * Display information about the passbolt instance
     * It is only available in debug mode and for logged in administrators
     *
     * @return void
     */
    public function index()
    {
        // Allow access only in debug mode or if logged in as admin
        if (Configure::read('debug') == 0) {
            if ($this->User->role() != Role::ADMIN) {
                throw new ForbiddenException();
            }
        }
        $this->viewBuilder()
            ->setLayout('login')
            ->setTemplatePath('Healthcheck')
            ->setTemplate('index');
        $checks = Healthchecks::all();
        $checks = array_merge($this->__webChecks(), $checks);
        $this->success(__('All checks ran successfully!'), $checks);
    }

    /**
     * Check that need/can to be performed in web context only
     *
     * @access private
     * @return bool
     */
    private function __webChecks()
    {
        $checks['ssl']['is'] = $this->request->is('ssl');

        return $checks;
    }
}
