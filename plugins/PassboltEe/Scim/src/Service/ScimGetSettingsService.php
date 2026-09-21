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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Service;

use App\Error\Exception\FormValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;
use Passbolt\Scim\Model\Dto\ScimSettingsDto;

class ScimGetSettingsService extends ScimBaseSettingsService
{
    /**
     * Read the SCIM settings in the DB and return the decrypted the values
     *
     * @return array
     */
    public function getSettingsDecryptedValue(): array
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $settings */
        $settings = $scimSettingsTable->find()->first();
        if (is_null($settings)) {
            return $this->getDefaultSettings();
        }

        return $this->decryptSettings($settings);
    }

    /**
     * Read the SCIM settings in the DB
     * If not found, null is returned
     * A validation error is thrown if the settings in the DB are not valid
     *
     * @return \Passbolt\Scim\Model\Dto\ScimSettingsDto|null
     * @throws \Cake\Http\Exception\InternalErrorException if the data in the DB is not valid
     */
    public function getSettings(): ?ScimSettingsDto
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');

        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $settings */
        $settings = $scimSettingsTable->find()->first();

        if (is_null($settings)) {
            return null;
        }
        $value = $this->decryptSettings($settings);
        $form = new ScimSettingsForm();

        if (!$form->execute($value, ['newRecord' => false])) {
            $validationException = new FormValidationException(
                __('Could not validate the SCIM settings found in database.'),
                $form,
            );

            throw new InternalErrorException($validationException->getMessage(), 500, $validationException);
        }

        return ScimSettingsDto::createFromArray([
            'id' => $settings->id,
            'setting_id' => Hash::get($value, 'setting_id'),
            'scim_user_id' => Hash::get($value, 'scim_user_id'),
            'base_api_endpoint' => Router::url('scim/v2/' . Hash::get($value, 'setting_id'), true),
            'expired' => Hash::get($value, 'expired'),
            'created' => $settings->created,
            'created_by' => $settings->created_by,
            'modified' => $settings->modified,
            'modified_by' => $settings->modified_by,
        ]);
    }
}
