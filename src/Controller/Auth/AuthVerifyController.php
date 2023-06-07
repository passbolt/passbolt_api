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
namespace App\Controller\Auth;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

class AuthVerifyController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['verifyGet']);

        return parent::beforeFilter($event);
    }

    /**
     * User Index action
     *
     * @return void
     */
    public function verifyGet()
    {
        $this->assertJson();

        $configMissing = (Configure::read('passbolt.gpg.serverKey.public') === null);
        $configMissing = ($configMissing || Configure::read('passbolt.gpg.serverKey.public') === null);
        if ($configMissing) {
            $msg = __('The OpenPGP public key information was not found in config.');
            throw new InternalErrorException($msg);
        }
        $publicKeyFileName = Configure::read('passbolt.gpg.serverKey.public');
        if (!file_exists($publicKeyFileName)) {
            throw new InternalErrorException('The OpenPGP public key for this passbolt instance was not found.');
        }
        if (!is_readable($publicKeyFileName)) {
            throw new InternalErrorException("The OpenPGP public key file '$publicKeyFileName' is not readable.");
        }
        $key = [
            'fingerprint' => Configure::read('passbolt.gpg.serverKey.fingerprint'),
            'keydata' => file_get_contents($publicKeyFileName),
        ];
        $this->success(__('The operation was successful.'), $key);
    }
}
