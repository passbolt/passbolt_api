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

/**
 * @property \App\Model\Table\GpgkeysTable $Gpgkeys
 */
class GpgkeysIndexController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'Gpgkeys',
        ]);
    }

    public $paginate = [
        'sortableFields' => [
            'Gpgkeys.key_id',
        ],
    ];

    /**
     * Gpgkey Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Gpgkeys');
        $whitelist = ['filter' => ['modified-after', 'is-deleted']];
        $options = $this->QueryString->get($whitelist);
        $gpgkeys = $this->Gpgkeys->find('index', $options);
        $this->paginate($gpgkeys);
        $this->success(__('The operation was successful.'), $gpgkeys);
    }
}
