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
namespace Passbolt\Scim\Service;

use App\Error\Exception\FormValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;

class ScimGetSettingsService extends ScimBaseSettingsService
{
    /**
     * Read the SCIM settings in the DB
     * If not found, the default settings are returned
     * A validation error is thrown if the settings in the DB are not valid
     *
     * @return array
     * @throws \Cake\Http\Exception\InternalErrorException if the data in the DB is not valid
     */
    public function getSettings(): array
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = $OrganizationSettings->getByProperty(self::SCIM_SETTINGS_PROPERTY_NAME);
        if (is_null($settings)) {
            return $this->getDefaultSettings();
        }

        $value = json_decode($settings->get('value'), true);
        if (is_null($value)) {
            throw new InternalErrorException(
                __('Could not parse the SCIM settings found in database.')
            );
        }
        $form = new ScimSettingsForm();

        if (!$form->execute($value)) {
            $validationException = new FormValidationException(
                __('Could not validate the SCIM settings found in database.'),
                $form
            );

            throw new InternalErrorException($validationException->getMessage(), 500, $validationException);
        }

        return $this->getRenderedValue($settings, $form);
    }

    /**
     * @return null[]
     */
    protected function getDefaultSettings(): array
    {
        return [];
    }
}
