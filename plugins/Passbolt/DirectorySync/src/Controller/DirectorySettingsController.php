<?php
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
 * @since         2.6.0
 */
namespace Passbolt\DirectorySync\Controller;

use App\Error\Exception\CustomValidationException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\View\ViewVarsTrait;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;
use Passbolt\DirectorySync\Utility\DirectoryFactory;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectorySettingsController extends DirectoryController
{
    use ViewVarsTrait;

    /**
     * Retrieve the settings
     *
     * @return void
     */
    public function view()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location'));
        }

        try {
            $directoryOrgSettings = DirectoryOrgSettings::get();
            $settings = $directoryOrgSettings->toArray();
        } catch (RecordNotFoundException $e) {
            $settings = [];
        }

        $formData = LdapConfigurationForm::formatOrgSettingsToFormData($settings);
        $this->success(__('The operation was successful.'), $formData);
    }

    /**
     * Update the settings
     *
     * @return void
     */
    public function update()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location'));
        }

        $data = $this->request->getData();
        $form = new LdapConfigurationForm();
        if (!$form->validate($data)) {
            $errors = $form->getErrors();
            throw new CustomValidationException('The settings are not valid', $errors);
        }
        try {
            $form->execute($data);
        } catch (\Exception $e) {
            throw new BadRequestException('The settings cannot be saved. ' . $e->getMessage());
        }

        $uac = $this->User->getAccessControl();
        $settings = LdapConfigurationForm::formatFormDataToOrgSettings($data);
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $this->success(__('The operation was successful.'));
    }

    /**
     * Test provided settings without saving them, and return directory results.
     *
     * @return void
     */
    public function test()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location'));
        }

        $data = $this->request->getData();
        $form = new LdapConfigurationForm();
        if (!$form->validate($data)) {
            $errors = $form->getErrors();
            throw new CustomValidationException('The settings are not valid', $errors);
        }
        try {
            $form->execute($data);
        } catch (\Exception $e) {
            throw new BadRequestException('The settings provided are incorrect. ' . $e->getMessage());
        }

        try {
            $settings = LdapConfigurationForm::formatFormDataToOrgSettings($data);
            $orgSettings = new DirectoryOrgSettings($settings);
            $directory = DirectoryFactory::get($orgSettings);
            $filteredDirectoryResults = $directory->getFilteredDirectoryResults();
            $outputData = [
                'users' => $this->_toArray(array_values($filteredDirectoryResults->getUsers())),
                'groups' => $this->_toArray(array_values($filteredDirectoryResults->getGroups())),
            ];
        } catch (\Exception $e) {
            throw new BadRequestException('The users and groups cannot be retrieved. ' . $e->getMessage());
        }

        try {
            $outputData['tree'] = $this->_toArray($filteredDirectoryResults->getTree());
        } catch (\Exception $e) {
            throw new BadRequestException('The directory structure cannot be retrieved. ' . $e->getMessage());
        }

        try {
            $invalidObjects = $filteredDirectoryResults->getInvalidGroups();
            $invalidObjects = array_merge($invalidObjects, $filteredDirectoryResults->getInvalidUsers());
            $outputData['errors'] = $this->_toArray($invalidObjects);
        } catch (\Exception $e) {
            throw new BadRequestException('There was an issue while retrieving the invalid entries. ' . $e->getMessage());
        }

        $this->success(__('The operation was successful.'), $outputData);
    }

    /**
     * Disable the ldap integration.
     *
     * @return void
     */
    public function disable()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location'));
        }

        $uac = $this->User->getAccessControl();
        DirectoryOrgSettings::disable($uac);

        $this->success(__('The operation was successful.'));
    }

    /**
     * Transform a list of entries to arrays, for output purpose.
     * @param $entries
     */
    private function _toArray($entries) {
        foreach($entries as $key => $entry) {
            $entries[$key] = $entry->toArray();
        }

        return $entries;
    }
}
