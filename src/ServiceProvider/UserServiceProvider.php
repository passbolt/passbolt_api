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
 * @since         3.5.0
 */

namespace App\ServiceProvider;

use App\Service\Users\UserRecoverService;
use App\Service\Users\UserRecoverServiceInterface;
use App\Service\Users\UserRegisterService;
use App\Service\Users\UserRegisterServiceInterface;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\ServerRequest;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface;

class UserServiceProvider extends ServiceProvider
{
    protected $provides = [
        UserRegisterServiceInterface::class,
        UserRecoverServiceInterface::class,
        FilesystemAdapter::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(UserRegisterServiceInterface::class, UserRegisterService::class)
            ->addArgument(ServerRequest::class);
        $container
            ->add(UserRecoverServiceInterface::class, UserRecoverService::class)
            ->addArgument(ServerRequest::class)
            ->addArgument(SelfRegistrationDryRunServiceInterface::class);
        $container
            ->add(FilesystemAdapter::class, LocalFilesystemAdapter::class)
            ->addArgument(TMP . 'avatars');
    }
}
