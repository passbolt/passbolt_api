<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Passbolt\DirectorySync\Utility\ActionReport;

trait AssertReportTrait
{
    public function assertReportStatus(ActionReport $report, string $status)
    {
        $this->assertEquals($report->getStatus(), $status);
    }
}