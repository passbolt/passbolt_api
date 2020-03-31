<?php

namespace Passbolt\Reports\Test\TestCase\Utility\ReportService;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\Lib\Model\UsersModelTrait;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use Passbolt\Reports\Utility\ReportService\EmployeeOnboardingReportService;
use PHPUnit\Framework\MockObject\MockObject;

class EmployeeOnboardingReportServiceTest extends TestCase
{
    use UsersModelTrait;

    /**
     * @var EmployeeOnboardingReportService
     */
    private $sut;

    /**
     * @var MockObject|UsersTable
     */
    private $usersTableMock;

    public function setUp()
    {
        $this->usersTableMock = $this->getMockForModel('Users');
        $this->sut = new EmployeeOnboardingReportService();
        parent::setUp();
    }

    public function testThatGetReportDataReturnTheListOfInactiveUsers()
    {
        $expectedUsersList = [new User(self::getDummyUser([]))];
        $expectedCreatedBy = new User(self::getDummyUser([]));

        $this->usersTableMock
            ->expects($this->once())
            ->method('findNonActiveUsersAsList')
            ->willReturn($expectedUsersList);

        $report = $this->sut->createReport($expectedCreatedBy);

        $reportData = $report->jsonSerialize();

        // Assert the returned report data

        // Assert data
        $this->assertSame(
            [
                'users' => $expectedUsersList,
            ],
            $reportData['data']
        );

        // Assert created_at
        $this->assertEquals(
            (new FrozenTime())->getTimestamp(),
            (new FrozenTime($reportData['created_at']))->getTimestamp(),
            'Report created_at date is not correct',
            5
        );

        // Assert created_By
        $this->assertEquals($expectedCreatedBy, $reportData['created_by']);

        // Assert name
        $this->assertEquals(__('Employee Onboarding Report'), $reportData['name']);

        // Assert description
        $this->assertEquals(
            __('List of all users who have never activated their account, or never logged in.'),
            $reportData['description']
        );

        // Assert slug
        $this->assertEquals(EmployeeOnboardingReportService::SLUG, $reportData['slug']);
    }
}
