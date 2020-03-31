<?php

namespace Passbolt\Reports\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;

class ReportsViewControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Comments'];

    public function testThatViewReportThrowErrorWhenNotAuthenticated()
    {
        $this->markTestIncomplete();
        $reportSlug = 'some-report-slug';
        $this->getJson('/reports/' . $reportSlug . '.json');
        $this->assertAuthenticationError();
    }

    public function testThaViewReportThrowUnauthorizedErrorWhenNotAuthenticatedAsAdmin()
    {
        $this->markTestIncomplete();
    }

    public function testThatViewReportReturnSuccessfulJsonResponseWhenReportSlugIsSupported()
    {
        $this->markTestIncomplete();
        $this->authenticateAs('ada');
        $this->getJson('/reports/employee-onboarding.json?api-version=2');

        $this->assertResponseSuccess();
    }

    public function testThatViewReportReturnBadRequestWhenReportSlugIsNotSupported()
    {
        $this->markTestIncomplete();
        $this->authenticateAs('ada');
        $reportSlug = 'this-report-does-not-exist';
        $this->getJson('/reports/' . $reportSlug . '.json');
        $this->assertBadRequestError(sprintf('The request report type `%s` does not exist.', $reportSlug));
    }
}
