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

namespace Passbolt\Rbacs\Service\Rbacs;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection;
use Passbolt\Rbacs\Model\Table\RbacsTable;

class RbacsUpdateService
{
    /**
     * @var \Passbolt\Rbacs\Model\Table\RbacsTable $rbacsTable
     */
    private RbacsTable $rbacsTable;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rbacsTable = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection $dtoCollection collection of changes
     * @throws \App\Error\Exception\ValidationException if the RBACs could not be validated
     * @throws \Cake\Http\Exception\BadRequestException if one or more RBACs could not be found
     * @throws \Cake\Http\Exception\InternalErrorException if the RBACs could not be saved
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function update(UserAccessControl $uac, RbacsUpdateDtoCollection $dtoCollection): ResultSetInterface
    {
        $updatedEntities = $this->patchEntities($uac, $this->getEntities($dtoCollection), $dtoCollection);

        try {
            $this->rbacsTable->saveManyOrFail($updatedEntities);
        } catch (PersistenceFailedException $exception) {
            $buildRulesErrors = $exception->getEntity()->getErrors();

            throw new CustomValidationException(
                __('The RBAC settings could not be updated.'),
                $buildRulesErrors
            );
        } catch (\Exception $exception) {
            throw new InternalErrorException(
                __('The RBAC settings could not be updated.'),
                null,
                $exception
            );
        }

        // Get updated entities
        $rbacs = $this->rbacsTable->find()
            ->where(['Rbacs.id IN' => $dtoCollection->getIds()])
            ->contain('UiAction')
            ->all();

        return $rbacs;
    }

    /**
     * @param \Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection $dtoCollection [{id:<uuid>, control_function:<string>},...]
     * @return \Cake\Datasource\ResultSetInterface
     */
    protected function getEntities(RbacsUpdateDtoCollection $dtoCollection): ResultSetInterface
    {
        $rbacs = $this->rbacsTable->find()
            ->select()
            ->where(['id IN' => $dtoCollection->getIds()])
            ->all();

        $this->assertUpdateRecordExists($rbacs, $dtoCollection);

        return $rbacs;
    }

    /**
     * Assert if all the changes corresponds to existing entities
     *
     * @param \Cake\Datasource\ResultSetInterface $rbacs entities collection
     * @param \Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection $dtoCollection changes collection
     * @return void
     */
    public function assertUpdateRecordExists(ResultSetInterface $rbacs, RbacsUpdateDtoCollection $dtoCollection)
    {
        if (!count($rbacs)) {
            throw new NotFoundException(__('No data found.'));
        }
        if (count($rbacs) !== $dtoCollection->count()) {
            throw new NotFoundException(__('Some data not found.'));
        }
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Cake\Datasource\ResultSetInterface $rbacs collection of rbac entities
     * @param \Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection $dtoCollection update set
     * @return array
     */
    protected function patchEntities(
        UserAccessControl $uac,
        ResultSetInterface $rbacs,
        RbacsUpdateDtoCollection $dtoCollection
    ) {
        $updateEntities = [];

        foreach ($rbacs as $i => $rbac) {
            $dto = $dtoCollection->getById($rbac->id);

            // Skip if it didn't change
            if ($dto['control_function'] === $rbac->control_function) {
                continue;
            }

            /** @var \Cake\ORM\Entity $updatedEntity */
            $updatedEntity = $this->rbacsTable->patchEntity($rbac, [
                'control_function' => $dto['control_function'],
                'modified_by' => $uac->getId(),
            ], [
                'accessibleFields' => [
                    'control_function' => true,
                    'modified_by' => true,
                ],
            ]);

            if ($updatedEntity->getErrors()) {
                $errors = [$i => $updatedEntity->getErrors()];
                throw new CustomValidationException(__('This is not a valid setting.'), $errors, $this->rbacsTable);
            }

            $updateEntities[] = $updatedEntity;
        }

        return $updateEntities;
    }
}
