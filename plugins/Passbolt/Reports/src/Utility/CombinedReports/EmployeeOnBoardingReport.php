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
namespace Passbolt\Reports\Utility\CombinedReports;

use Passbolt\Reports\Utility\AbstractCombinedReport;
use Passbolt\Reports\Utility\SingleReports\Users\ActiveUsersCountReport;
use Passbolt\Reports\Utility\SingleReports\Users\NonActiveUsersCountReport;
use Passbolt\Reports\Utility\SingleReports\Users\NonActiveUsersListReport;

class EmployeeOnBoardingReport extends AbstractCombinedReport
{
    const SLUG = 'employee-on-boarding';

    /**
     * EmployeeOnBoardingReport constructor
     */
    public function __construct()
    {
        $this->setSlug(self::SLUG)
            ->setName(__('Employee on-boarding Report'))
            ->setDescription(__('List of all users who have never activated their account, or never logged in.'))
            ->addReport(new ActiveUsersCountReport())
            ->addReport(new NonActiveUsersCountReport())
            ->addReport(new NonActiveUsersListReport());
    }
}
