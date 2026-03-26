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
 * @since         5.11.0
 */
namespace Passbolt\Scim\Service;

use Cake\Core\Configure;
use Cake\I18n\Date;

class ScimSettingsMigrateExpiredFieldService extends ScimBaseSettingsService
{
    /**
     * Migrate existing SCIM settings to include the expired field if not already set.
     *
     * @return void
     */
    public function migrate(): void
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $settings */
        $settings = $scimSettingsTable->find()->first();

        if ($settings === null) {
            return;
        }

        $data = $this->decryptSettings($settings);

        if (isset($data['expired'])) {
            return;
        }

        /** @var string|null $expiry */
        $expiry = Configure::read('passbolt.plugins.scim.security.secretToken.expiry');
        if ($expiry === null) {
            return;
        }

        $data['expired'] = Date::now()->modify('+' . $expiry)->format('Y-m-d');
        $settings->set('value', $this->encryptSettings($data));
        $scimSettingsTable->saveOrFail($settings);
    }
}
