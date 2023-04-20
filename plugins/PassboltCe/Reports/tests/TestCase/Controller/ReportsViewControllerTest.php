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

namespace Passbolt\Reports\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Reports\Service\ReportPool;
use Passbolt\Reports\Utility\AbstractSingleReport;

class ReportsViewControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/Groups',
        'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Comments',
    ];

    public function testReportsViewControllerError_ThrowErrorWhenNotAuthenticated()
    {
        $slug = 'sample-report';
        $this->addSampleReport($slug);
        $this->getJson('/reports/' . $slug . '.json');
        $this->assertAuthenticationError();
    }

    private function addSampleReport($slug)
    {
        $ReportClass = new class extends AbstractSingleReport
        {
            public function getData()
            {
                return [];
            }

            /** @psalm-suppress InvalidReturnType no return type */
            public function setDescription(string $description)
            {
            }
        };

        /** @psalm-suppress UndefinedClass unconventional stuff is happening here */
        $report = new $ReportClass();
        $report->setSlug($slug)
            ->setName(__('Sample report'))
            ->setDescription(__('This is a description for sample report'));

        ReportPool::getInstance()->addReport($report);
    }

    public function testReportsViewControllerError_ThrowUnauthorizedErrorWhenNotAdmin()
    {
        $slug = 'sample-report';
        $this->addSampleReport($slug);
        $this->logInAsUser();
        $this->getJson('/reports/' . $slug . '.json');
        $this->assertError(403, 'Only administrators can view reports.');
    }

    public function testReportsViewControllerError_BadRequestWhenSlugIsNotSupported()
    {
        $this->logInAsAdmin();
        $this->getJson('/reports/this-report-does-not-exist.json');
        $this->assertBadRequestError('The requested report `this-report-does-not-exist` does not exist.');
    }

    public function testReportsViewControllerSuccess()
    {
        $slug = 'sample-report';
        $this->addSampleReport($slug);
        $this->logInAsAdmin();
        $this->getJson('/reports/' . $slug . '.json?api-version=2');
        $this->assertResponseSuccess();
    }

    public function testReportsViewControllerWithArgumentSuccess()
    {
        $slug = 'sample-report-with-argument';
        $this->addSampleReportWithArgument($slug);
        $this->logInAsAdmin();
        $this->getJson('/reports/' . $slug . '/arg1.json?api-version=2');
        $this->assertResponseSuccess();
    }

    private function addSampleReportWithArgument($slug)
    {
        $ReportClass = new class extends AbstractSingleReport
        {
            public function __construct(?string $arg1 = null)
            {
            }

            public function getData()
            {
                return [];
            }

            /** @psalm-suppress InvalidReturnType no return type */
            public function setDescription(string $description)
            {
            }
        };

        /** @psalm-suppress UndefinedClass unconventional stuff is happening here */
        $report = new $ReportClass();
        $report->setSlug($slug)
            ->setName(__('Sample report with argument'))
            ->setDescription(__('This is a description for sample report with argument'));

        ReportPool::getInstance()->addReport($report);
    }
}
