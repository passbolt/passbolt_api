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
namespace PassboltTestData\Shell\Task\Security;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\Security\Xss;
use PassboltTestData\Shell\Task\Base\ProfilesDataTask;

class XssProfilesDataTask extends ProfilesDataTask
{
    protected $_truncate = false;

    /**
     * Get the users data
     *
     * @return array
     */
    public function getData()
    {
        $exploits = Xss::getExploits();
        $profiles = [];

        foreach ($exploits as $rule => $exploit) {
            $userAlias = 'xss' . count($profiles);
            $profiles[] = [
                'id' => UuidFactory::uuid("profile.id.$userAlias"),
                'user_id' => UuidFactory::uuid("user.id.$userAlias"),
                'gender' => 'f',
                'date_of_birth' => '1970-01-01',
                'title' => 'Ms',
                'first_name' => substr($exploit, 0, 255),
                'last_name' => substr($exploit, 0, 255),
            ];
        }

        return $profiles;
    }
}
