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

use Migrations\AbstractMigration;
use Migrations\Migrations;
use Cake\ORM\TableRegistry;

class V200MigrateFileStorageTable extends AbstractMigration
{
    public static $fileStorageMigrations = [
        'initial_migration'         => '20141213004653',
        'fixing_mime_type_field'    => '20160302083933',
    ];

    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $migrations = new Migrations([
            'connection' => 'default',
            'plugin' => 'Burzum/FileStorage',
        ]);
        // If the table file_storage exists, we prevent the initial migration to happen.
        // We can do this because the table structure remains the same between the 2 versions.
        $exists = $this->hasTable('file_storage');
        if ($exists) {
            $this->table('file_storage')
                ->changeColumn('id', 'char', ['limit' => 36])
                ->save();
            $migrations->markMigrated(self::$fileStorageMigrations['initial_migration']);
        }
        // Continue with the next plugin migrations.
        $migrations->migrate([
            'target' => self::$fileStorageMigrations['fixing_mime_type_field']
        ]);

        // Transform "ProfileAvatar" into "Avatar" in existing db data.
        $FileStorage = TableRegistry::get('FileStorage');
        $avatars = $FileStorage->find()->all();
        foreach ($avatars as $key => $avatar) {
            $avatar = $FileStorage->patchEntity(
                $avatar,
                [
                    'model' => 'Avatar',
                    'path' => str_replace('ProfileAvatar', 'Avatar', $avatar->path)
                ]
            );
            $FileStorage->save($avatar);
        }
    }
}
