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
namespace Passbolt\Reports\Utility;

use Passbolt\Reports\Utility\CombinedReports\EmployeeOnBoardingReport;
use Passbolt\Reports\Utility\CombinedReports\EmptyCombinedReport;
use Passbolt\Reports\Utility\SingleReports\Users\ActiveUsersCountReport;
use Passbolt\Reports\Utility\SingleReports\Users\NonActiveUsersCountReport;
use Passbolt\Reports\Utility\SingleReports\Users\NonActiveUsersListReport;

/**
 * Contains the list of all available report services.
 *
 * @package Passbolt\Reports\Utility
 */
class ReportPool
{
    /**
     * Return a an array of callable to instantiate to get a report
     * Each Report is created callable to avoid to instantiate a report collection when it will
     * not be used, but still maintain of a list of the report somewhere
     *
     * @return callable[]
     */
    public function getReports()
    {
        return [
            // Combined reports
            EmployeeOnBoardingReport::SLUG => function () {
                return new EmployeeOnBoardingReport();
            },

            // Single reports
            ActiveUsersCountReport::SLUG => function () {
                return new ActiveUsersCountReport();
            },
            NonActiveUsersCountReport::SLUG => function () {
                return new NonActiveUsersCountReport();
            },
            NonActiveUsersListReport::SLUG => function () {
                return new NonActiveUsersListReport();
            },

            // The sky the limit reports -> make setCallableGetData
            'my-dynamic-report' => function () {
                return (new EmptyCombinedReport())
                    ->setSlug('my-dynamic-report')
                    ->setDescription('Out of control reports')
                    ->setName('No limits')
                    ->addReport(new NonActiveUsersCountReport());
            },
        ];
    }
}
