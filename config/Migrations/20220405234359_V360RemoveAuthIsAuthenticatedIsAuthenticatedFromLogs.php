<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Passbolt\Log\Service\ActionLogs\ActionLogsDeleteService;

class V360RemoveAuthIsAuthenticatedIsAuthenticatedFromLogs extends AbstractMigration
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
        $service = new ActionLogsDeleteService();
        $service->delete($service::AUTH_IS_AUTHENTICATED_IS_AUTHENTICATED);
    }
}
