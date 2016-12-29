<?php
namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Installers\YawikInstaller;
use Composer\Package\Package;
use Composer\Package\PackageInterface;

/**
 * Class YawikInstallerTest
 *
 * @package Composer\Installers\Test
 */
class YawikInstallerTest extends TestCase
{
    /**
     * @varComposer
     */
    private $composer;

    /**
     * @var PackageInterface
     */
    private $io;

    /**
     * @var Package
     */
    private $package;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->package = new Package('YawikCompanyRegistration', '1.0', '1.0');
        $this->io = $this->getMock('Composer\IO\PackageInterface');
        $this->composer = new Composer();
    }

    /**
     * testInflectPackageVars
     *
     * @dataProvider packageNameProvider
     * @return void
     */
    public function testInflectPackageVars($input)
    {
        $installer = new YawikInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => $input));
        $this->assertEquals($result, array('name' => 'YawikCompanyRegistration'));
    }

    public function packageNameProvider()
    {
        return array(
            array('yawik-company-registration'),
            array('yawik_company_registration'),
            array('YawikCompanyRegistration')
        );
    }
}
