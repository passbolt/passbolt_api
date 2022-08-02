<?php
declare(strict_types=1);

use App\Service\ResourceTypes\ResourceTypesTrimSpacesService;
use Cake\Log\Log;
use Migrations\AbstractMigration;

class V380TrimSpacesOnResourceTypesNameAndSlug extends AbstractMigration
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
            (new ResourceTypesTrimSpacesService())->trim();
        } catch (Throwable $e) {
            Log::error('There was an error in V380TrimSpacesOnResourceTypesNameAndSlug');
            Log::error($e->getMessage());
        }
    }
}
