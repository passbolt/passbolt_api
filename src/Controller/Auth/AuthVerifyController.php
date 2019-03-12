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
namespace App\Controller\Auth;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Http\Exception\InternalErrorException;

class AuthVerifyController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('verifyGet');

        return parent::beforeFilter($event);
    }

    /**
     * User Index action
     *
     * @return void
     */
    public function verifyGet()
    {
        $configMissing = (Configure::read('passbolt.gpg.serverKey.public') === null);
        $configMissing = ($configMissing || Configure::read('passbolt.gpg.serverKey.public') === null);
        if ($configMissing) {
            $msg = __('The public key information was not found in config.');
            throw new InternalErrorException($msg);
        }
        $file = new File(Configure::read('passbolt.gpg.serverKey.public'));
        if (!$file->exists()) {
            throw new InternalErrorException(__('The public key for this passbolt instance was not found.'));
        }
        $key = [
            'fingerprint' => Configure::read('passbolt.gpg.serverKey.fingerprint'),
            'keydata' => $file->read()
        ];
        $this->success(__('The operation was successful.'), $key);
    }
}
