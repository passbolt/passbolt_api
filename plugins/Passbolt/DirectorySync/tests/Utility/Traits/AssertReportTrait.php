<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Passbolt\DirectorySync\Utility\ActionReport;

trait AssertReportTrait
{
    /**
     * @param ActionReport $report
     * @param string $status
     */
    public function assertReportStatus(ActionReport $report, string $status)
    {
        $this->assertEquals($report->getStatus(), $status);
    }

    /**
     * @param ActionReport $report
     * @param string $model
     */
    public function assertReportModel(ActionReport $report, string $model)
    {
        $this->assertEquals($report->getModel(), $model);
    }

    /**
     * @param ActionReport $report
     * @param string $model
     */
    public function assertReportAction(ActionReport $report, string $model)
    {
        $this->assertEquals($report->getAction(), $model);
    }

    /**
     * @param ActionReport $report
     * @param array $data
     */
    public function assertReport(ActionReport $report, array $data)
    {
        $this->assertReportStatus($report, $data['status']);
        $this->assertReportModel($report, $data['model']);
        $this->assertReportAction($report, $data['action']);
    }
}