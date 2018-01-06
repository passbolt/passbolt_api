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
namespace PassboltTestData\Shell\Task\Alt0;

class GroupsUsersDataTask extends \PassboltTestData\Shell\Task\Base\GroupsUsersDataTask
{
    public $fixtureName = 'GroupsUsers';

    /**
     * @inheritdoc
     * @return array
     */
    protected function getGroupsUsersSettings()
    {
        return [
            'accounting' => [
                'managers' => ['ada'],
                'users' => ['ada', 'betty']
            ],
            'board' => [
                'managers' => ['ada', 'betty'],
                'users' => ['ada', 'betty']
            ],
            'creative' => [
                'managers' => ['ada'],
                'users' => ['ada']
            ],
            'developer' => [
                'managers' => ['ada', 'betty'],
                'users' => ['ada', 'betty']
            ],
            'ergonom' => [
                'managers' => ['dame'],
                'users' => ['dame']
            ],
            'freelancer' => [
                'managers' => ['edith'],
                'users' => ['edith', 'frances', 'grace']
            ],
        ];
    }
}
