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
 * @since         2.13.0
 */
namespace Passbolt\Reports\Controller\Reports;

use App\Controller\AppController;
use Exception;
use Passbolt\Reports\Factory\ReportServiceFactory;
use Passbolt\Reports\Utility\ReportServiceCollection;

class AdminReportsIndexController extends AppController
{
    /**
     * @var ReportServiceCollection
     */
    private $reportGeneratorsCollection;

    /**
     * @var ReportServiceFactory
     */
    private $generateReportService;

    /**
     * @return void
     * @throws Exception
     */
    public function initialize()
    {
        parent::initialize();
        $this->generateReportService = new ReportServiceFactory();
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->generateReportService->getReportServiceCollections();

        $this->success([], $this->reportGeneratorsCollection);
    }
}
