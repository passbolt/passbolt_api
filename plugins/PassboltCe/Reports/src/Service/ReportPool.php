<?php
declare(strict_types=1);

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
namespace Passbolt\Reports\Service;

use Passbolt\Reports\Utility\AbstractReport;

/**
 * Singleton class.
 * Used to add and list available reports.
 *
 * @package Passbolt\Reports\Utility
 */
class ReportPool
{
    /**
     * Instance of class used for singleton.
     *
     * @var \Passbolt\Reports\Service\ReportPool|null
     */
    private static $instance;

    /**
     * Reports list.
     *
     * @var array
     */
    private static $reports = [];

    /**
     * Get ReportPool singleton.
     *
     * @return \Passbolt\Reports\Service\ReportPool
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ReportPool();
        }

        return self::$instance;
    }

    /**
     * Add a report in the report pool.
     *
     * @param \Passbolt\Reports\Utility\AbstractReport $report The report to add
     * @return \Passbolt\Reports\Utility\AbstractReport[] list of reports
     */
    public function addReport(AbstractReport $report)
    {
        self::$reports[$report->getSlug()] = $report;

        return self::$reports;
    }

    /**
     * Add reports in the report pool.
     *
     * @param Callable[] $reports list of callable Reports (AbstractReport)
     *
     * Example:
     * [
     *    // Combined reports
     *    EmployeeOnBoardingReport::SLUG => function () {
     *      return new EmployeeOnBoardingReport();
     *    },

     *    // The sky the limit reports -> make setCallableGetData
     *    'my-dynamic-report' => function () {
     *      return (new EmptyCombinedReport())
     *        ->setSlug('my-dynamic-report')
     *        ->setDescription('Out of control reports')
     *        ->setName('No limits')
     *        ->addReport(new NonActiveUsersCountReport());
     *    },
     * ];
     * @return Callable[] list of callable reports (AbstractReport)
     */
    public function addReports(array $reports)
    {
        self::$reports = array_merge(self::$reports, $reports);

        return self::$reports;
    }

    /**
     * Returns an array of callable to instantiate to get a report
     * Each Report is created callable to avoid to instantiate a report collection when it will
     * not be used, but still maintain of a list of the report somewhere
     *
     * @return callable[]
     */
    public function getReports()
    {
        return self::$reports;
    }
}
