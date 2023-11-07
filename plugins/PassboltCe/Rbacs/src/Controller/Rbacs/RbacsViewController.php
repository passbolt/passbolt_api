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

class RbacsViewController extends AppController
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
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated([
            'viewForCurrentRole',
        ]);

        return parent::beforeFilter($event);
    }

    /**
     * List all the rbacs for the given role
     *
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an admin
     */
    public function viewForCurrentRole(): void
    {
        $this->assertJson();
        $roleId = $this->User->roleId();
        $rbacs = $this->Rbacs->find()
            ->where(['role_id' => $roleId])
            ->contain('UiAction');

        $this->paginate($rbacs);

        $this->success(__('The operation was successful.'), $rbacs);
    }
}
