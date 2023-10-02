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

namespace Passbolt\Rbacs\Service\UiActions;

use App\Error\Exception\ValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Model\Table\UiActionsTable;

class UiActionsCreateService
{
    /**
     * @var \Passbolt\Rbacs\Model\Table\UiActionsTable $uiActionsTable
     */
    private UiActionsTable $uiActionsTable;

    /**
     * Constructor
     *
     * @param \Passbolt\Rbacs\Model\Table\UiActionsTable|null $uiActionsTable table
     */
    public function __construct(?UiActionsTable $uiActionsTable = null)
    {
        $this->uiActionsTable = $uiActionsTable ?? TableRegistry::getTableLocator()->get('Passbolt/Rbacs.UiActions');
    }

    /**
     * @param string $name name
     * @return \Passbolt\Rbacs\Model\Entity\UiAction
     */
    public function create(string $name): UiAction
    {
        // Build entity
        $entity = $this->uiActionsTable->newEntity([
            'name' => $name,
        ], ['accessibleFields' => [
            'name' => true,
        ]]);

        // Check for validation errors
        if ($entity->getErrors()) {
            $msg = __('The UI actions data could not be validated.');
            throw new ValidationException($msg, $entity, $this->uiActionsTable);
        }

        // Check business rules
        $this->uiActionsTable->checkRules($entity);
        if (!empty($entity->getErrors())) {
            $msg = __('The UI actions data could not be validated.');
            throw new ValidationException($msg, $entity, $this->uiActionsTable);
        }

        // Check for internal error on save
        $entity = $this->uiActionsTable->save($entity, ['checkRules' => false]);
        if (!$entity) {
            throw new InternalErrorException('Could not save the UI action, try again later.');
        }

        return $entity;
    }
}
