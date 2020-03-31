<?php

namespace Passbolt\Reports\Test\TestCase\Factory;

use App\Model\Entity\User;
use App\Test\Lib\Model\UsersModelTrait;
use Cake\I18n\FrozenTime;
use InvalidArgumentException;
use Passbolt\Reports\Factory\ReportServiceFactory;
use Passbolt\Reports\Utility\Report;
use Passbolt\Reports\Utility\ReportServiceInterface;
use Passbolt\Reports\Utility\ReportServicePool;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportServiceFactoryTest extends TestCase
{
    use UsersModelTrait;

    /**
     * @var ReportServiceFactory
     */
    private $sut;

    /**
     * @var MockObject|ReportServicePool
     */
    private $reportServicePoolMock;

    protected function setUp()
    {
        $this->reportServicePoolMock = $this->createMock(ReportServicePool::class);
        $this->sut = new ReportServiceFactory($this->reportServicePoolMock);
        parent::setUp();
    }

    public function testThatGetThrowInvalidArgumentExceptionIfReportSlugDoesNotMatchAnyReportCollection()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->sut->get('does-not-exist');
    }

    public function testThatGetReturnCollectionIfReportSlugMatchReportCollection()
    {
        $expectedReportSlug = 'existing-report-collection';
        $expectedReportService = $this->createMock(ReportServiceInterface::class);

        $this->reportServicePoolMock
            ->expects($this->once())
            ->method('getReportServices')
            ->willReturn([
                $expectedReportSlug => function (ReportServiceFactory $reportServiceFactory) use ($expectedReportService) {
                    return $expectedReportService;
                },
            ]);

        $reportService = $this->sut->get($expectedReportSlug);

        $this->assertEquals($expectedReportService, $reportService);
    }

    public function testThatCreateReportServiceCollectionAddAllProvidedReportServicesToTheCollection()
    {
        $expectedSubReport = new Report('name', 'desc', 'slug', 'template', new FrozenTime(), new User(self::getDummyUser([])), []);
        $subReportService = $this->createMock(ReportServiceInterface::class);
        $subReportService->expects($this->any())
            ->method('createReport')
            ->willReturn($expectedSubReport);

        $expectedSubReportSlug = 'sub-service-slug';
        $expectedName = 'name';
        $expectedDescription = 'desc';
        $expectedTemplate = 'tmpl';
        $expectedServices = [
            $subReportService,
        ];

        $reportServiceCollection = $this->sut->createReportServiceCollection(
            $expectedSubReportSlug,
            $expectedName,
            $expectedDescription,
            $expectedTemplate,
            $expectedServices
        );

        $report = $reportServiceCollection->createReport(new User(self::getDummyUser([])));

        $this->assertContains($expectedSubReport, $report->getData());
    }
}
