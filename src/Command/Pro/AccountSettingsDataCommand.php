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
 * @since         2.0.0
 */
namespace PassboltTestData\Command\Pro;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataCommand;

class AccountSettingsDataCommand extends DataCommand
{
    public $entityName = 'AccountSettings';

    /**
     * Get the tags data
     *
     * @return array
     */
    public function getData()
    {
        $settings[] = [
            'id' => UuidFactory::uuid('account.settings.ping.theme'),
            'user_id' => UuidFactory::uuid('user.id.ping'),
            'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
            'property' => 'theme',
            'value' => 'midgar'
        ];
        $settings[] = [
            'id' => UuidFactory::uuid('account.settings.betty.theme'),
            'user_id' => UuidFactory::uuid('user.id.betty'),
            'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
            'property' => 'theme',
            'value' => 'default'
        ];
        return $settings;
    }
}
