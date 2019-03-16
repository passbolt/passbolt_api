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
namespace App\Controller\Pages;

use App\Controller\AppController;
use Cake\Core\Configure;

class HomeController extends AppController
{
    /**
     * Password workspace page action
     *
     * @return void
     */
    public function view()
    {
        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('/Home')
            ->setTemplate('home');

        $this->set('title', Configure::read('passbolt.meta.description'));
        $this->set('jsBuildMode', Configure::read('passbolt.js.build'));

        $this->success();
    }
}
