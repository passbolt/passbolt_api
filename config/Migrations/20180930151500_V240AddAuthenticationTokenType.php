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

use Migrations\AbstractMigration;

class V240AddAuthenticationTokenType extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Delete all authentication token to make sure there is no token without type
        $this->query('DELETE from authentication_tokens');

        $this->table('authentication_tokens')
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 16,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('authentication_tokens')
            ->addIndex(['type']);

    }
}
