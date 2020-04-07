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
namespace Passbolt\Reports\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Reports\Utility\CombinedReports\EmployeeOnBoardingReport;

class ReportsViewControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/Groups',
        'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Comments',
    ];

    public function testReportsViewControllerError_ThrowErrorWhenNotAuthenticated()
    {
        $this->getJson('/reports/' . EmployeeOnBoardingReport::SLUG . '.json');
        $this->assertAuthenticationError();
    }

    public function testReportsViewControllerError_ThrowUnauthorizedErrorWhenNotAdmin()
    {
        $this->authenticateAs('ada');
        $this->getJson('/reports/' . EmployeeOnBoardingReport::SLUG . '.json');
        $this->assertError(403, 'Only administrators can view admin reports.');
    }

    public function testReportsViewControllerError_BadRequestWhenSlugIsNotSupported()
    {
        $this->authenticateAs('admin');
        $this->getJson('/reports/this-report-does-not-exist.json');
        $this->assertBadRequestError('The requested report `this-report-does-not-exist` does not exist.');
    }

    public function testReportsViewControllerSuccess()
    {
        $this->authenticateAs('admin');
        $this->getJson('/reports/' . EmployeeOnBoardingReport::SLUG . '.json?api-version=2');
        $this->assertResponseSuccess();
    }
}
