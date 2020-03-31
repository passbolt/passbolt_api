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
use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Exception;
use InvalidArgumentException;
use Passbolt\Reports\Factory\ReportServiceFactory;
use Passbolt\Reports\Utility\Report;
use Passbolt\Reports\Utility\ReportServiceInterface;

class AdminReportsViewController extends AppController
{
    const DEFAULT_LAYOUT = 'Passbolt/Reports.Reports/ReportsLayout';

    /**
     * @var ReportServiceFactory
     */
    private $generateReportService;

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @return void
     * @throws Exception
     */
    public function initialize()
    {
        parent::initialize();
        $this->generateReportService = new ReportServiceFactory();
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @param string $reportSlug Slug of the report to retrieve
     * @throws Exception
     * @throws BadRequestException If the requested report does not exist
     * @return void
     */
    public function getReport(string $reportSlug)
    {
        $uac = $this->User->getAccessControl();

        try {
            $reportService = $this->generateReportService->get($reportSlug);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestException(__('The requested report `{0}` does not exist.', $reportSlug));
        }

        /** @var User $user */
        $user = $this->usersTable->get($uac->userId());

        $report = $reportService->createReport($user, $this->formatRequestData($reportService));

        if (!$this->request->is('json')) {
            $this->renderReportInHtml($report);
        } else {
            $this->success(__('The operation was successful.'), $report);
        }
    }

    /**
     * @param ReportServiceInterface $reportService An instance of report service
     * @return FindIndexOptions
     */
    private function formatRequestData(ReportServiceInterface $reportService)
    {
        $findIndexOptions = $reportService->getSupportedOptions();
        $options = $this->QueryString->get($findIndexOptions->toArray(), $findIndexOptions->getFilterValidators());

        return FindIndexOptions::createFromArray($options);
    }

    /**
     * @param Report $report Instance of Report to render
     * @return void
     */
    private function renderReportInHtml(Report $report)
    {
        // Rendering in HTML
        $this->viewBuilder()
            ->setTemplatePath('Reports/html')
            ->setLayout(static::DEFAULT_LAYOUT)
            ->setTheme(false)
            ->setTemplate($report->getTemplate())
            ->setVars($report->jsonSerialize());
        $this->render();
    }
}
