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
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Exception;
use InvalidArgumentException;
use Passbolt\Reports\Utility\ReportInterface;
use Passbolt\Reports\Utility\ReportViewService;

class ReportsViewController extends AppController
{
    const DEFAULT_LAYOUT = 'Passbolt/Reports.Reports/ReportsLayout';

    /**
     * @var ReportViewService
     */
    private $reportViewService;

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
        $this->reportViewService = new ReportViewService();
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @param string $reportSlug Slug of the report to retrieve
     * @throws Exception
     * @throws BadRequestException If the requested report does not exist
     * @return void
     */
    public function view(string $reportSlug)
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('Only administrators can view reports.'));
        }

        // Retrieve the report argument passed as url parameters.
        $arguments = func_get_args();
        $reportArguments = array_slice($arguments, 1);

        try {
            $report = $this->reportViewService->getReport($reportSlug, $reportArguments);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestException(__('The requested report `{0}` does not exist.', $reportSlug));
        }

        /** @var FindIndexOptions $options */
        $options = $this->formatRequestData($report->getSupportedOptions());

        /** @var User $creator */
        $creator = $this->usersTable->get($this->User->id(), ['contain' => 'Profiles']);

        $report
            ->setOptions($options)
            ->setCreator($creator);

        if (!$this->request->is('json')) {
            $this->renderReportInHtml($report);
        } else {
            $this->success(__('The operation was successful.'), $report);
        }
    }

    /**
     * Format request data
     * Get supported options from report service and extract / validate them using QueryString component
     *
     * @param FindIndexOptions $supportedOptions allowed options
     * @return FindIndexOptions
     */
    private function formatRequestData(FindIndexOptions $supportedOptions)
    {
        $query = $this->QueryString->get($supportedOptions->getAllowedOptions(), $supportedOptions->getFilterValidators());

        return FindIndexOptions::createFromArray($query);
    }

    /**
     * Set view variables, theme and template for html render
     *
     * @param ReportInterface $report Instance of Report to render
     * @return void
     */
    private function renderReportInHtml(ReportInterface $report)
    {
        $this->viewBuilder()
            ->setTemplatePath('Reports/html')
            ->setLayout(static::DEFAULT_LAYOUT)
            ->setTheme(false)
            ->setTemplate($report->getTemplate());
        $this->set('report', $report->createReport());
    }
}
