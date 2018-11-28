<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\Utility\Inflector;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Actions\Reports\ActionReportCollection;

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
            if (is_array($report->getData())) {
                $this->fail('The report type should not be an array.');
            }
            $type = Inflector::singularize($type);
            $fullname = get_class($report->getData());
            $length = strlen($type);
            $endswith = $length === 0 || (substr($fullname, -$length) === $type);
            $this->assertEquals(true, $endswith, __('Failed to check that type {0} is in {1}', $type, $fullname));
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
     * @param ActionReportCollection $reports
     * @param string $model
     */
    public function assertNoReportsForModel(ActionReportCollection $reports, string $model)
    {
        foreach ($reports as $report) {
            $this->assertNotEquals($model, $report->getModel());
        }
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
        if (isset($data['type'])) {
            $this->assertReportDataType($report, $data['type']);
        }
    }

    /**
     * @param ActionReportCollection $reports
     */
    public function assertReportEmpty(ActionReportCollection $reports)
    {
        $this->assertEquals(true, $reports->isEmpty(), 'The report should be empty.');
    }

    /**
     * @param ActionReportCollection $reports
     */
    public function assertReportNotEmpty(ActionReportCollection $reports)
    {
        $this->assertEquals(false, $reports->isEmpty(), 'The report should not be empty.');
    }
}
