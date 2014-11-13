<?php
namespace Composer\Installers\Test;

use Composer\Installers\CakePHPInstaller;
use Composer\Repository\RepositoryManager;
use Composer\Repository\InstalledArrayRepository;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Package\Link;
use Composer\Package\Version\VersionParser;
use Composer\Composer;

class CakePHPInstallerTest extends TestCase
{
    private $composer;
    private $io;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->package = new Package('CamelCased', '1.0', '1.0');
        $this->io = $this->getMock('Composer\IO\PackageInterface');
        $this->composer = new Composer();
    }

    /**
     * testInflectPackageVars
     *
     * @return void
     */
    public function testInflectPackageVars()
    {
        $installer = new CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'CamelCased'));
        $this->assertEquals($result, array('name' => 'CamelCased'));

        $installer = new CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'with-dash'));
        $this->assertEquals($result, array('name' => 'WithDash'));

        $installer = new CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'with_underscore'));
        $this->assertEquals($result, array('name' => 'WithUnderscore'));

        $installer = new CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'cake/acl'));
        $this->assertEquals($result, array('name' => 'Cake/Acl'));

        $installer = new CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'cake/debug-kit'));
        $this->assertEquals($result, array('name' => 'Cake/DebugKit'));
    }

    /**
     * Test getLocations returning appropriate values based on CakePHP version
     *
     */
    public function testGetLocations() {
        $package = new RootPackage('CamelCased', '1.0', '1.0');
        $composer = new Composer();
        $rm = new RepositoryManager(
            $this->getMock('Composer\IO\IOInterface'),
            $this->getMock('Composer\Config')
        );
        $composer->setRepositoryManager($rm);
        $installer = new CakePHPInstaller($package, $composer);

        // 2.0 < cakephp < 3.0
        $this->setCakephpVersion($rm, '2.0.0');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);

        $this->setCakephpVersion($rm, '2.5.9');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);

        $this->setCakephpVersion($rm, '~2.5');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);

        // special handling for 2.x versions when 3.x is still in development
        $this->setCakephpVersion($rm, 'dev-master');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);

        $this->setCakephpVersion($rm, '>=2.5');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);

        // cakephp >= 3.0
        $this->setCakephpVersion($rm, '3.0.*-dev');
        $result = $installer->getLocations();
        $this->assertContains('plugins/', $result['plugin']);

        $this->setCakephpVersion($rm, '~8.8');
        $result = $installer->getLocations();
        $this->assertContains('plugins/', $result['plugin']);
    }

    /**
     * Test if installer-name was set
     *
     */
    public function testGetInstallPath() {
        $autoload = array(
            'psr-4' => array(
                'FOC\\Authenticate' => ''
            )
        );
        $this->package->setAutoload($autoload);
        $this->package->setType('cakephp-plugin');
        $rm = new RepositoryManager(
            $this->getMock('Composer\IO\IOInterface'),
            $this->getMock('Composer\Config')
        );
        $this->composer->setRepositoryManager($rm);
        $installer = new CakePHPInstaller($this->package, $this->composer);

        $this->setCakephpVersion($rm, '3.0.0');
        $installer->getInstallPath($this->package, 'cakephp');
        $extra = $this->package->getExtra();
        $this->assertEquals('FOC/Authenticate', $extra['installer-name']);

        $autoload = array(
            'psr-4' => array(
                'FOC\Acl\Test' => './tests',
                'FOC\Acl' => ''
            )
        );
        $this->package->setAutoload($autoload);
        $this->package->setExtra(array());
        $installer->getInstallPath($this->package, 'cakephp');
        $extra = $this->package->getExtra();
        $this->assertEquals('FOC/Acl', $extra['installer-name']);

        $autoload = array(
            'psr-4' => array(
                'Foo\Bar' => 'foo',
                'Acme\Plugin\Test' => 'tests',
                'Acme\Plugin' => './src'
            )
        );
        $this->package->setAutoload($autoload);
        $this->package->setExtra(array());
        $installer->getInstallPath($this->package, 'cakephp');
        $extra = $this->package->getExtra();
        $this->assertEquals('Acme/Plugin', $extra['installer-name']);
    }

    protected function setCakephpVersion($rm, $version) {
        $parser = new VersionParser();
        list(, $version) = explode(' ', $parser->parseConstraints($version));
        $installed = new InstalledArrayRepository();
        $package = new Package('cakephp/cakephp', $version, $version);
        $installed->addPackage($package);
        $rm->setLocalRepository($installed);
    }

}
