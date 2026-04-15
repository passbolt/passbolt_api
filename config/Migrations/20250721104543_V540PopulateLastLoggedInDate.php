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
 * @since         5.4.1
 */

use App\Service\Users\PopulateLastLoggedInDateService;
use Cake\Log\Log;
use Migrations\AbstractMigration;

class V540PopulateLastLoggedInDate extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {

        // An issue was found in PopulateLastLoggedInDateService.
        // As the migration was faulty, we skip this one and (re-)run instead V541PopulateLastLoggedInDate
//        try {
//            (new PopulateLastLoggedInDateService())->populate();
//        } catch (\Throwable $e) {
//            $msg = 'There was an error in V540PopulateLastLoggedInDate.';
//            $msg .= ' ' . $e->getMessage();
//            Log::error($msg);
//        }
    }
}
