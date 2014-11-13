<?php
namespace Composer\Installers;

use Composer\DependencyResolver\Pool;
use Composer\Package\PackageInterface;
use Composer\Package\LinkConstraint\MultiConstraint;
use Composer\Package\LinkConstraint\VersionConstraint;

class CakePHPInstaller extends BaseInstaller
{
    protected $locations = array(
        'plugin' => 'Plugin/{$name}/',
    );

    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars($vars)
    {
        $nameParts = explode('/', $vars['name']);
        foreach ($nameParts as &$value) {
            $value = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $value));
            $value = str_replace(array('-', '_'), ' ', $value);
            $value = str_replace(' ', '', ucwords($value));
        }
        $vars['name'] = implode('/', $nameParts);

        return $vars;
    }

    /**
     * Change the default plugin location when cakephp >= 3.0
     */
    public function getLocations()
    {
        if ($this->matchesCakeVersion('>=', '3.0.0')) {
            $this->locations['plugin'] = 'plugins/{$name}/';
        }
        return $this->locations;
    }

    /**
     * Add installer-name for CakePHP >= 3.0.0
     *
     * @param PackageInterface $package
     * @param string $frameworkType
     * @return string
     */
    public function getInstallPath(PackageInterface $package, $frameworkType = '')
    {
        $extra = $package->getExtra();
        if (empty($extra['installer-name']) && $this->matchesCakeVersion('>=', '3.0.0')) {
            $this->setInstallerName($package);
        }
        return parent::getInstallPath($package, $frameworkType);
    }

    /**
     * Check if CakePHP version matches against a version
     *
     * @param string $matcher
     * @param string $version
     * @return bool
     */
    protected function matchesCakeVersion($matcher, $version)
    {
        $repositoryManager = $this->composer->getRepositoryManager();
        if ($repositoryManager) {
            $repos = $repositoryManager->getLocalRepository();
            if (!$repos) {
                return false;
            }
            $cake3 = new MultiConstraint(array(
                new VersionConstraint($matcher, $version),
                new VersionConstraint('!=', '9999999-dev'),
            ));
            $pool = new Pool('dev');
            $pool->addRepository($repos);
            $packages = $pool->whatProvides('cakephp/cakephp');
            foreach ($packages as $package) {
                $installed = new VersionConstraint('=', $package->getVersion());
                if ($cake3->matches($installed)) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    /**
     * Set installer-name based on namespace for the source path checking in
     * following order:
     *
     * - With only one autoload path the namespace for that path will be used.
     * - With multiple paths if path 'src' exists it's namespace will be used.
     * - With two autoload paths provided, the namespace of path other than
     *   'tests' will be used.
     *
     * No installer-name is set if PSR-4 autoload block is not found or if none
     * of the above conditions are met.
     *
     * @param PackageInterface $package
     */
    protected function setInstallerName(PackageInterface $package)
    {
        $primaryNS = null;
        $autoLoad = $package->getAutoload();
        foreach ($autoLoad as $type => $typeConfig) {
            if ($type !== 'psr-4') {
                continue;
            }
            $count = count($typeConfig);

            if ($count === 1) {
                $primaryNS = key($typeConfig);
                break;
            }

            $matches = preg_grep('#^(\./)?src/?$#', $typeConfig);
            if ($matches) {
                $primaryNS = key($matches);
                break;
            }

            if ($count === 2) {
                reset($typeConfig);
                if (preg_match('#^(\./)?tests/?$#', current($typeConfig))) {
                    next($typeConfig);
                }
                $primaryNS = key($typeConfig);
                break;
            }

            break;
        }

        if ($primaryNS) {
            $package->setExtra(array(
                'installer-name' => trim(str_replace('\\', '/', $primaryNS), '/')
            ));
        }
    }

}
