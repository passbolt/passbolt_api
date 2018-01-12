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
 * @since         2.0.0
 */
namespace PassboltTestData\Shell\Task\Security;

use PassboltTestData\Lib\Security\Xss;
use PassboltTestData\Shell\Task\Base\GroupsUsersDataTask;

class XssGroupsUsersDataTask extends GroupsUsersDataTask
{
    protected $_truncate = false;

    /**
     * Get groups users settings
     *
     * @return array
     */
    protected function getGroupsUsersSettings()
    {
        $exploits = Xss::getExploits();
        $settings = [];

        foreach ($exploits as $groupRule => $GroupExploit) {
            $groupAlias = 'xss' . count($settings);
            $i = 0;
            foreach ($exploits as $userRule => $userExploit) {
                $userAlias = "xss$i";
                $settings[$groupAlias]['managers'][] = $userAlias;
                $settings[$groupAlias]['users'][] = $userAlias;
                $i++;
            }
        }

        return $settings;
    }
}
