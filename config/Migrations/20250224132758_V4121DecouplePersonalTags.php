<?php
declare(strict_types=1);
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
 * @since         4.12.1
 */
// @codingStandardsIgnoreStart
use Cake\Log\Log;
use Migrations\AbstractMigration;
use Passbolt\Tags\Service\Tags\DecouplePersonalTagsService;

class V4121DecouplePersonalTags extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        try {
            (new DecouplePersonalTagsService())->decouple();
        } catch (\Throwable $e) {
            Log::error('There was an error in V4121DecouplePersonalTags');
            Log::error($e->getMessage());
        }
    }
}
