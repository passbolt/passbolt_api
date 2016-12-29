<?php
namespace Composer\Installers;

use Composer\IO\IOInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class Installer extends LibraryInstaller
{
    /**
     * Package types to installer class map
     *
     * @var array
     */
    private $supportedTypes = array(
        'aimeos'       => 'AimeosInstaller',
        'asgard'       => 'AsgardInstaller',
        'attogram'     => 'AttogramInstaller',
        'agl'          => 'AglInstaller',
        'annotatecms'  => 'AnnotateCmsInstaller',
        'bitrix'       => 'BitrixInstaller',
        'bonefish'     => 'BonefishInstaller',
        'cakephp'      => 'CakePHPInstaller',
        'chef'         => 'ChefInstaller',
        'ccframework'  => 'ClanCatsFrameworkInstaller',
        'cockpit'      => 'CockpitInstaller',
        'codeigniter'  => 'CodeIgniterInstaller',
        'concrete5'    => 'Concrete5Installer',
        'craft'        => 'CraftInstaller',
        'croogo'       => 'CroogoInstaller',
        'dokuwiki'     => 'DokuWikiInstaller',
        'dolibarr'     => 'DolibarrInstaller',
        'decibel'      => 'DecibelInstaller',
        'drupal'       => 'DrupalInstaller',
        'elgg'         => 'ElggInstaller',
        'ee3'          => 'ExpressionEngineInstaller',
        'ee2'          => 'ExpressionEngineInstaller',
        'fuel'         => 'FuelInstaller',
        'fuelphp'      => 'FuelphpInstaller',
        'grav'         => 'GravInstaller',
        'hurad'        => 'HuradInstaller',
        'imagecms'     => 'ImageCMSInstaller',
        'joomla'       => 'JoomlaInstaller',
        'kirby'        => 'KirbyInstaller',
        'kodicms'      => 'KodiCMSInstaller',
        'kohana'       => 'KohanaInstaller',
        'laravel'      => 'LaravelInstaller',
        'lithium'      => 'LithiumInstaller',
        'magento'      => 'MagentoInstaller',
        'mako'         => 'MakoInstaller',
        'mautic'       => 'MauticInstaller',
        'mediawiki'    => 'MediaWikiInstaller',
        'microweber'   => 'MicroweberInstaller',
        'modulework'   => 'MODULEWorkInstaller',
        'modxevo'      => 'MODXEvoInstaller',
        'moodle'       => 'MoodleInstaller',
        'october'      => 'OctoberInstaller',
        'oxid'         => 'OxidInstaller',
        'phpbb'        => 'PhpBBInstaller',
        'pimcore'      => 'PimcoreInstaller',
        'piwik'        => 'PiwikInstaller',
        'plentymarkets'=> 'PlentymarketsInstaller',
        'ppi'          => 'PPIInstaller',
        'puppet'       => 'PuppetInstaller',
        'radphp'       => 'RadPHPInstaller',
        'phifty'       => 'PhiftyInstaller',
        'redaxo'       => 'RedaxoInstaller',
        'reindex'      => 'ReIndexInstaller',
        'roundcube'    => 'RoundcubeInstaller',
        'shopware'     => 'ShopwareInstaller',
        'silverstripe' => 'SilverStripeInstaller',
        'smf'          => 'SMFInstaller',
        'symfony1'     => 'Symfony1Installer',
        'thelia'       => 'TheliaInstaller',
        'tusk'         => 'TuskInstaller',
        'typo3-cms'    => 'TYPO3CmsInstaller',
        'typo3-flow'   => 'TYPO3FlowInstaller',
        'vanilla'      => 'VanillaInstaller',
        'whmcs'        => 'WHMCSInstaller',
        'wolfcms'      => 'WolfCMSInstaller',
        'wordpress'    => 'WordPressInstaller',
        'yawik'        => 'YawikInstaller',
        'zend'         => 'ZendInstaller',
        'zikula'       => 'ZikulaInstaller',
        'prestashop'   => 'PrestashopInstaller'
    );

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $type = $package->getType();
        $frameworkType = $this->findFrameworkType($type);

        if ($frameworkType === false) {
            throw new \InvalidArgumentException(
                'Sorry the package type of this package is not yet supported.'
            );
        }

        $class = 'Composer\\Installers\\' . $this->supportedTypes[$frameworkType];
        $installer = new $class($package, $this->composer, $this->getIO());

        return $installer->getInstallPath($package, $frameworkType);
    }

    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        if (!$repo->hasPackage($package)) {
            throw new \InvalidArgumentException('Package is not installed: '.$package);
        }

        $repo->removePackage($package);

        $installPath = $this->getInstallPath($package);
        $this->io->write(sprintf('Deleting %s - %s', $installPath, $this->filesystem->removeDirectory($installPath) ? '<comment>deleted</comment>' : '<error>not deleted</error>'));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        $frameworkType = $this->findFrameworkType($packageType);

        if ($frameworkType === false) {
            return false;
        }

        $locationPattern = $this->getLocationPattern($frameworkType);

        return preg_match('#' . $frameworkType . '-' . $locationPattern . '#', $packageType, $matches) === 1;
    }

    /**
     * Finds a supported framework type if it exists and returns it
     *
     * @param  string $type
     * @return string
     */
    protected function findFrameworkType($type)
    {
        $frameworkType = false;

        krsort($this->supportedTypes);

        foreach ($this->supportedTypes as $key => $val) {
            if ($key === substr($type, 0, strlen($key))) {
                $frameworkType = substr($type, 0, strlen($key));
                break;
            }
        }

        return $frameworkType;
    }

    /**
     * Get the second part of the regular expression to check for support of a
     * package type
     *
     * @param  string $frameworkType
     * @return string
     */
    protected function getLocationPattern($frameworkType)
    {
        $pattern = false;
        if (!empty($this->supportedTypes[$frameworkType])) {
            $frameworkClass = 'Composer\\Installers\\' . $this->supportedTypes[$frameworkType];
            /** @var BaseInstaller $framework */
            $framework = new $frameworkClass(null, $this->composer, $this->getIO());
            $locations = array_keys($framework->getLocations());
            $pattern = $locations ? '(' . implode('|', $locations) . ')' : false;
        }

        return $pattern ? : '(\w+)';
    }

    /**
     * Get I/O object
     *
     * @return IOInterface
     */
    private function getIO()
    {
        return $this->io;
    }
}
