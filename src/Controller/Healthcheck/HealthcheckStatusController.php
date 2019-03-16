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
use Cake\Event\Event;

class HealthcheckStatusController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['status']);

        return parent::beforeFilter($event);
    }

    /**
     * A lightweight method that returns OK
     * Useful to know if the site is up or not
     *
     * @return void
     */
    public function status()
    {
        $this->viewBuilder()
            ->setLayout('ajax')
            ->setTemplatePath('Healthcheck');
        $this->success(__('OK'), 'OK');
    }
}
