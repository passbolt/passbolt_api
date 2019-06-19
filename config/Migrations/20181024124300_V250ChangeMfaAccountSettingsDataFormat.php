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
 * @since         2.4.0
 */

use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;

class V250ChangeMfaAccountSettingsDataFormat extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $connectionName = 'default';
        if ($this->input->getOption('connection')) {
            $connectionName = $this->input->getOption('connection');
        }
        $connection = ConnectionManager::get($connectionName);
        $accountSettings = TableRegistry::getTableLocator()->get('AccountSettings', [
            'connection' => $connection
        ]);
        $settings = $accountSettings->find()
            ->select()
            ->where(['property' => 'mfa'])
            ->all();

        foreach ($settings as $setting) {
            $value = json_decode($setting->value, 1);

            $providers = [];

            // Rename provider
            foreach ($value['providers'] as $provider) {
                if ($provider === 'otp') {
                    $providers[] = 'totp';
                } else {
                    $providers = $provider;
                }
            }
            $value['providers'] = $providers;
            $value['totp'] = [];

            // Change verified field
            foreach ($value['otp'] as $key => $prop) {
                if ($key === 'verified') {
                    $value['totp']['verified'] = $setting->created;
                } else {
                    $value['totp'][$key] = $prop;
                }
            }

            // Remove old provider key
            unset($value['otp']);
            $setting->value = json_encode($value);
        }
        $accountSettings->saveMany($settings);
    }
}
