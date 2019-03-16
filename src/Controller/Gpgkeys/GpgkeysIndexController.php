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
namespace App\Controller\Gpgkeys;

use App\Controller\AppController;
use Cake\Event\Event;

class GpgkeysIndexController extends AppController
{
    /**
     * Gpgkey Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Gpgkeys');
        $whitelist = ['filter' => ['modified-after']];
        $options = $this->QueryString->get($whitelist);
        $gpgkeys = $this->Gpgkeys->find('index', $options);
        $this->success(__('The operation was successful.'), $gpgkeys);
    }
}
