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
namespace PassboltTestData\Shell\Task\Base;

use PassboltTestData\Lib\DataTask;

class AvatarsDataTask extends DataTask
{
    public $entityName = 'Avatars';

    /**
     * Execute data task
     *
     * @return void
     */
    public function execute()
    {
        $this->loadModel('Avatars');
        $this->loadModel('Users');
        $users = $this->Users->find()
            ->contain('Profiles')
            ->all();
        $count = 0;
        foreach ($users as $user) {
            $matches = [];
            preg_match('/^(.*)@(.*)$/', $user->username, $matches);
            $userAvatarFileName = $matches[1] . '.png';
            $userAvatarFullPath = PASSBOLT_TEST_DATA_AVATAR_PATH . DS . $userAvatarFileName;
            if (file_exists($userAvatarFullPath)) {
                $data = [
                    'file' => [
                        'tmp_name' => $userAvatarFullPath,
                        'name' => strtolower($user->profile->first_name) . '.png',
                    ],
                    'user_id' => $user->id,
                    'foreign_key' => $user->profile->id,
                ];

                $entity = $this->Avatars->newEntity();
                $entity = $this->Avatars->patchEntity($entity, $data);
                $avatar = $this->Avatars->save($entity);

                $errors = $avatar->getErrors();
                if (!empty($errors)) {
                    $this->out('Error inserting data for entity "' . $this->entityName);
                } else {
                    $count++;
                }
            }
        }
        $this->out('Data for entity "' . $this->entityName . '" inserted (' . $count . ')');
    }
}
