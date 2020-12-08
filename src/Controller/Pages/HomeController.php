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
namespace App\Controller\Pages;

use App\Controller\AppController;
use Cake\Core\Configure;

class HomeController extends AppController
{
    /**
     * This entry point serves the API javascript application.
     *
     * @return void
     */
    public function home()
    {
        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('/Home')
            ->setTemplate('home');

        $this->set('theme', $this->User->theme());
        $this->set('title', Configure::read('passbolt.meta.description'));

        $this->success();
    }

    /**
     * This entry point serves an empty page with the splash screen.
     *
     * @return void
     */
    public function empty()
    {
        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('/Home')
            ->setTemplate('empty');

        $this->set('theme', $this->User->theme());
        $this->set('title', Configure::read('passbolt.meta.description'));

        $this->success();
    }
}
