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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Controller\Rbacs;

use App\Controller\AppController;

class RbacsIndexController extends AppController
{
    /**
     * @var \Passbolt\Rbacs\Model\Table\RbacsTable $Rbacs
     */
    protected $Rbacs;

    /**
     * @var array $paginate options
     */
    public $paginate = [];

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Rbacs = $this->fetchTable('Passbolt/Rbacs.Rbacs');
        $this->loadComponent('ApiPagination', [
            'model' => 'Rbacs',
        ]);
    }

    /**
     * List all the rbacs
     *
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an admin
     */
    public function index(): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        $rbacs = $this->Rbacs->find()
            ->contain('UiAction');
        $this->paginate($rbacs);

        $this->success(__('The operation was successful.'), $rbacs);
    }
}
