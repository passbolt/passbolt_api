<?php

namespace Passbolt\Reports\Test\TestCase\Utility;

use App\Model\Entity\User;
use App\Test\Lib\Model\UsersModelTrait;
use Cake\TestSuite\TestCase;
use Passbolt\Reports\Utility\ReportServiceCollection;

class ReportServiceCollectionTest extends TestCase
{
    use UsersModelTrait;

    /**
     * @var ReportServiceCollection
     */
    private $sut;

    public function setUp()
    {
        parent::setUp();
    }

    public function testThatCreateReportUseReportPropertiesGivenToCollection()
    {
        $expectedName = 'report name';
        $expectedDescription = 'report desc';
        $expectedSlug = 'report slug';
        $expectedTemplate = 'report template';

        $createdBy = new User(self::getDummyUser([]));

        $this->sut = new ReportServiceCollection($expectedSlug, $expectedName, $expectedDescription, $expectedTemplate);

        $report = $this->sut->createReport($createdBy);

        $reportData = $report->jsonSerialize();

        $this->assertSame($expectedName, $reportData['name']);
        $this->assertSame($expectedDescription, $reportData['description']);
        $this->assertSame($expectedSlug, $reportData['slug']);

        $this->assertSame($expectedTemplate, $report->getTemplate());
    }
}
