<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.2.0
 */

namespace Passbolt\DirectorySync\Controller;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

class DirectoryIgnoreController extends DirectoryController
{
    /**
     * Check if a record is ignored
     *
     * @param string $foreignModel foreign model
     * @param string $foreignKey foreign key
     * @throws \App\Error\Exception\ValidationException If the model name or id is not valid
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is not an admin
     * @return void
     */
    public function toggle(string $foreignModel, string $foreignKey): void
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        $this->assertDirectoryEnabled();
        $foreignModel = $this->normalizeForeignModel($foreignModel);
        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            throw new BadRequestException(__('The record model is not valid.'));
        }

        $this->loadModel('Passbolt/DirectorySync.DirectoryIgnore');
        try {
            $ignored = $this->DirectoryIgnore->get($foreignKey);
            $result = $this->DirectoryIgnore->delete($ignored);
        } catch (RecordNotFoundException $exception) {
        }
        $this->success(__('The record is currently ignored as part of directory synchronization.'), $ignored);
    }

    /**
     * Check if a record is ignored
     *
     * @param string $foreignModel foreign model
     * @param string $foreignKey foreign key
     * @throws \App\Error\Exception\ValidationException If the model name or id is not valid
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is not an admin
     * @return void
     */
    public function view(string $foreignModel, string $foreignKey): void
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        $this->assertDirectoryEnabled();
        $foreignModel = $this->normalizeForeignModel($foreignModel);
        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            throw new BadRequestException(__('The record model is not valid.'));
        }

        $this->loadModel('Passbolt/DirectorySync.DirectoryIgnore');
        try {
            $ignored = $this->DirectoryIgnore->get($foreignKey);
        } catch (RecordNotFoundException $exception) {
            $msg = __('The record is currently not ignored as part of directory synchronization.');
            throw new NotFoundException($msg);
        }
        $this->success(__('The record is currently ignored as part of directory synchronization.'), $ignored);
    }

    /**
     * Mark a record as ignored.
     *
     * @param string $foreignModel foreign model
     * @param string $foreignKey foreign key
     * @throws \App\Error\Exception\ValidationException If the model name or id is not valid
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is not an admin
     * @return void
     */
    public function add(string $foreignModel, string $foreignKey): void
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        $this->assertDirectoryEnabled();
        $foreignModel = $this->normalizeForeignModel($foreignModel);
        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            throw new BadRequestException(__('The record model is not valid.'));
        }
        $this->loadModel('Passbolt/DirectorySync.DirectoryIgnore');

        try {
            $ignored = $this->DirectoryIgnore->createOrFail($foreignModel, $foreignKey);
        } catch (ValidationException $exception) {
            $errors = $exception->getEntity()->getErrors();
            if (isset($errors['id']['AssociatedRecordExists'])) {
                throw new NotFoundException($errors['id']['AssociatedRecordExists']);
            }
            throw $exception;
        }
        $this->success(__('The record will be ignored in the next directory synchronization.'), $ignored);
    }

    /**
     * Delete
     *
     * @param string $foreignModel foreign model
     * @param string $foreignKey foreign key
     * @return void
     */
    public function delete(string $foreignModel, string $foreignKey): void
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        $this->assertDirectoryEnabled();
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The record id is not valid.'));
        }
        $foreignModel = $this->normalizeForeignModel($foreignModel);
        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            throw new BadRequestException(__('The record model is not valid.'));
        }

        $this->loadModel('Passbolt/DirectorySync.DirectoryIgnore');

        try {
            $record = $this->DirectoryIgnore->get($foreignKey);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The record does not exist.'));
        }

        $result = $this->DirectoryIgnore->delete($record);
        if (!$result) {
            $msg = __('The record could not be unmarked as ignored. Please try again later.');
            throw new InternalErrorException($msg);
        }
        $this->success(__('The record will not be ignored in the next directory synchronization.'));
    }

    /**
     * @param string $foreignModel foreign model
     * @return string
     */
    private function normalizeForeignModel(string $foreignModel): string
    {
        $foreignModel = ucfirst($foreignModel);
        if ($foreignModel === 'Directoryentries') {
            $foreignModel = 'DirectoryEntries';
        }

        return $foreignModel;
    }
}
