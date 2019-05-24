<?php
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
 * @since         2.10.0
 */
namespace Passbolt\EmailNotificationSettings\Controller\NotificationOrgSettings;

use App\Controller\AppController;
use App\Controller\Component\QueryStringComponent;
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class NotificationOrgSettingsPostController extends AppController
{
    /**
     * Handle email notifications org settings POST request
     *
     * @return void
     */
    public function post()
    {
        $payload = $this->_validateRequestData();

        // Get the currently set setting, consider both DB and config files
        $existingSettings = EmailNotificationSettings::get();

        // So far, the request seems all right
        // now, merge the payload with the currently set notification settings
        $mergedSettings = array_replace_recursive($existingSettings, $payload);

        EmailNotificationSettings::save($mergedSettings, $this->User->getAccessControl());

        // After update, query db again for the updated values
        $updatedNotificationSettings = EmailNotificationSettings::get();

        $flatten = Hash::flatten($updatedNotificationSettings);

        $this->success(__('The notification settings for the organization were updated.'), $this->_formatForOutput($flatten));
    }

    /**
     * Validate the request body
     *
     * @return array if the request body is valid
     * @throws ForbiddenException If the user making request is not admin
     * @throws BadRequestException If the request is not a Ajax/Json type
     */
    private function _validateRequestData()
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not allowed to access this location.'));
        }
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        $data = $this->request->getData();

        foreach ($data as $key => $value) {
            $data[$key] = QueryStringComponent::normalizeBoolean($value);
        }

        $form = new EmailNotificationSettingsForm();

        if (!$form->validate($data)) {
            $errors = $form->getErrors();

            throw new CustomValidationException('The supplied email notification settings are not valid', $errors);
        }

        $data = EmailNotificationSettingsForm::formatFormDataToOrgSettings($data);

        return Hash::expand($data);
    }

    /**
     * Format the . delimited keys to snake_case
     *
     * @param array $data The data to Format
     * @return array the formatted array
     */
    private function _formatForOutput(array $data = [])
    {
        $output = [];

        foreach ($data as $key => $value) {
            $key = str_replace('.', '_', $key);

            $output[$key] = $value;
        }

        return $output;
    }
}
