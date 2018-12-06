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
use App\Model\Entity\Role;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectorySettingsController extends DirectoryController
{
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
            $errors = $form->errors();
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
        $directoryOrgSettings = DirectoryOrgSettings::disable($uac);

        $this->success(__('The operation was successful.'));
    }
}
