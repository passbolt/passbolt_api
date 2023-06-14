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
namespace App\Controller\Gpgkeys;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\GpgkeysTable $Gpgkeys
 */
class GpgkeysViewController extends AppController
{
    /**
     * Roles View action
     *
     * @param string $id uuid of the gpgkey
     * @return void
     */
    public function view(string $id)
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The OpenPGP key identifier should be a valid UUID.'));
        }
        // Retrieve the user
        /** @var \App\Model\Table\GpgkeysTable $gpgkeysTable */
        $gpgkeysTable = $this->fetchTable('Gpgkeys');
        $gpgkeys = $gpgkeysTable->find('view', ['id' => $id])->first();
        if (empty($gpgkeys)) {
            throw new NotFoundException(__('The OpenPGP key does not exist.'));
        }
        $this->success(__('The operation was successful.'), $gpgkeys);
    }
}
