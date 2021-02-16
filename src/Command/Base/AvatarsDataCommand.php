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
namespace PassboltTestData\Command\Base;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use PassboltTestData\Lib\DataCommand;

class AvatarsDataCommand extends DataCommand
{
    public $entityName = 'Avatars';

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        return $io->out(__('Will not perform anything for the moment as Avatars are highly optional.'));

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
                        'error' => 0,
                        'type' => 'image/png',
                        'name' => strtolower($user->profile->first_name) . '.png',
                    ],
                    'user_id' => $user->id,
                    'foreign_key' => $user->profile->id,
                ];

                $entity = $this->Avatars->newEntity($data, ['validate' => false]);
                $avatar = $this->Avatars->save($entity, ['checkRules' => false]);
                if (!$avatar) {
                    $io->out('Error inserting data for entity "' . $this->entityName);
                } else {
                    $count++;
                }
            }
        }
    }
}
