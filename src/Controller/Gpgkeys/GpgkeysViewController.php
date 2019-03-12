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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

class GpgkeysViewController extends AppController
{
    /**
     * Roles View action
     *
     * @param string $id uuid of the gpgkey
     * @return void
     */
    public function view($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The gpg key id should be a uuid.'));
        }
        // Retrieve the user
        $this->loadModel('Gpgkeys');
        $gpgkeys = $this->Gpgkeys->find('view', ['id' => $id ])->first();
        if (empty($gpgkeys)) {
            throw new NotFoundException(__('The gpg key does not exist.'));
        }
        $this->success(__('The operation was successful.'), $gpgkeys);
    }
}
