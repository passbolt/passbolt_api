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
        $this->assertEquals($status, $report->getStatus());
    }

    /**
     * @param ActionReport $report
     * @param string $type data class or type name
     */
    public function assertReportDataType(ActionReport $report, string $type)
    {
        if ($type === 'array') {
            $this->assertEquals(true, is_array($report->getData()));
        } else {
            $fullname = get_class($report->getData());
            $length = strlen($type);
            $endswith = $length === 0 || (substr($fullname, -$length) === $type);
            $this->assertEquals(true, $endswith);
        }
    }

    /**
     * @param ActionReport $report
     * @param string $model
     */
    public function assertReportModel(ActionReport $report, string $model)
    {
        $this->assertEquals($model, $report->getModel());
    }

    /**
     * @param ActionReport $report
     * @param string $model
     */
    public function assertReportAction(ActionReport $report, string $model)
    {
        $this->assertEquals($model, $report->getAction());
    }

    /**
     * @param ActionReport $report
     * @param array $data
     */
    public function assertReport(ActionReport $report, array $data)
    {
        if (isset($data['status'])) {
            $this->assertReportStatus($report, $data['status']);
        }
        if (isset($data['model'])) {
            $this->assertReportModel($report, $data['model']);
        }
        if (isset($data['action'])) {
            $this->assertReportAction($report, $data['action']);
        }
    }
}