<?php
declare(strict_types=1);

namespace Passbolt\Reports\View\Helper;

use Cake\View\Helper;

class ReportHelper extends Helper
{
    /**
     * Get sub report from the results of combined report.
     *
     * @param array $reportData report data (from a combined report)
     * @param string $slug slug of single report to find.
     * @return array|bool the report data if found, false otherwise.
     */
    public static function getSubReport(array $reportData, string $slug)
    {
        /** @var array $report */
        foreach ($reportData as $report) {
            if (isset($report['slug']) && $report['slug'] === $slug) {
                return $report;
            }
        }

        return false;
    }
}
