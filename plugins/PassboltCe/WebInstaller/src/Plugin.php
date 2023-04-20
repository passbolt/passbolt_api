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
 * @since         3.1.0
 */
namespace Passbolt\WebInstaller;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Http\MiddlewareQueue;
use Passbolt\WebInstaller\Middleware\WebInstallerMiddleware;
use Passbolt\WebInstaller\Service\WebInstallerChangeConfigFolderPermissionService;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue->add(WebInstallerMiddleware::class);
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(
                WebInstallerChangeConfigFolderPermissionService::class,
                WebInstallerChangeConfigFolderPermissionService::class
            )
            ->addArgument(CONFIG);
    }
}
