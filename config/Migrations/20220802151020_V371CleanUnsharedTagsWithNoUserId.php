<?php
declare(strict_types=1);

use Cake\Log\Log;
use Migrations\AbstractMigration;
use Passbolt\Tags\Service\Tags\CleanUnsharedTagsWithNoUserIdService;

class V371CleanUnsharedTagsWithNoUserId extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        try {
            (new CleanUnsharedTagsWithNoUserIdService())->cleanUp();
        } catch (\Throwable $e) {
            Log::error('There was an error in V371CleanUnsharedTagsWithNoUserId');
            Log::error($e->getMessage());
        }
    }
}
