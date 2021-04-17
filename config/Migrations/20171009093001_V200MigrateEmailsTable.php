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

use Migrations\AbstractMigration;

use Migrations\Migrations;

class V200MigrateEmailsTable extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Initial
        $this->table('email_queue')->drop()->save();
        $table = $this->table('email_queue', ['collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn(
                'email',
                'string',
                [
                    'default' => null,
                    'limit' => 129,
                    'null' => false,
                ]
            )
            ->addColumn(
                'from_name',
                'string',
                [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ]
            )
            ->addColumn(
                'from_email',
                'string',
                [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ]
            )
            ->addColumn(
                'subject',
                'string',
                [
                    'default' => null,
                    'limit' => 255,
                    'null' => false,
                ]
            )
            ->addColumn(
                'config',
                'string',
                [
                    'default' => null,
                    'limit' => 30,
                    'null' => false,
                ]
            )
            ->addColumn(
                'template',
                'string',
                [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ]
            )
            ->addColumn(
                'layout',
                'string',
                [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ]
            )
            ->addColumn(
                'theme',
                'string',
                [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ]
            )
            ->addColumn(
                'format',
                'string',
                [
                    'default' => null,
                    'limit' => 5,
                    'null' => false,
                ]
            )
            ->addColumn(
                'template_vars',
                'text',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'headers',
                'text',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'sent',
                'boolean',
                [
                    'default' => 0,
                    'limit' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'locked',
                'boolean',
                [
                    'default' => 0,
                    'limit' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'send_tries',
                'integer',
                [
                    'default' => 0,
                    'limit' => 2,
                    'null' => false,
                ]
            )
            ->addColumn(
                'send_at',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'created',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => false,
                ]
            )
            ->addColumn(
                'modified',
                'datetime',
                [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ]
            )
            ->addColumn(
                'attachments',
                'text',
                [
                    'default' => null,
                    'null' => true,
                ]
            )
            ->create();

        // AddAttachmentsToEmailQueue
        $table->update();
    }

    /**
     * Down
     *
     * @return void
     */
    public function down()
    {
        return;
    }
}
