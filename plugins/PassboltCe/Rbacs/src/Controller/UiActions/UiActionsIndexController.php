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

namespace Passbolt\Rbacs\Controller\UiActions;

use App\Controller\AppController;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\Rbacs\Model\Entity\UiAction;

class UiActionsIndexController extends AppController
{
    /**
     * @var \Passbolt\Rbacs\Model\Table\UiActionsTable $UiActions
     */
    protected $UiActions;

    /**
     * @var array $paginate options
     */
    public $paginate = [
        'sortableFields' => [
            'UiActions.name',
        ],
    ];

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->UiActions = $this->fetchTable('Passbolt/Rbacs.UiActions');
        $this->loadComponent('ApiPagination', [
            'model' => 'UiActions',
        ]);
    }

    /**
     * List all the ui actions
     *
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an admin
     */
    public function index(): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        $uiActions = $this->UiActions->find();
        $this->paginate($uiActions);

        $uiActions = $this->decorateUiActionsWithControlFunctions($uiActions->toArray());

        $this->success(__('The operation was successful.'), $uiActions);
    }

    /**
     * @param \Passbolt\Rbacs\Model\Entity\UiAction[] $uiActions Array of UI actions entity.
     * @return array
     * @throws \Cake\Http\Exception\InternalErrorException When control function mapping for UI action is not defined
     */
    private function decorateUiActionsWithControlFunctions(array $uiActions): array
    {
        foreach ($uiActions as $uiAction) {
            if (!isset(UiAction::CONTROL_FUNCTION_MAPPING[$uiAction->name])) {
                /**
                 * Raise error if mapping not found for this UI action.
                 *
                 * @see \Passbolt\Rbacs\Model\Entity\UiAction::CONTROL_FUNCTION_MAPPING
                 */
                throw new InternalErrorException("Control function for '{$uiAction->name}' UI action is not defined"); // phpcs:ignore
            }

            $uiAction['allowed_control_functions'] = UiAction::CONTROL_FUNCTION_MAPPING[$uiAction->name];
        }

        return $uiActions;
    }
}
