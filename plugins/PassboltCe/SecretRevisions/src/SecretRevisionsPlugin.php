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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions;

use App\Service\Command\ProcessUserService;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\SecretRevisions\Command\PopulateCreatedByAndModifiedByInSecretsCommand;
use Passbolt\SecretRevisions\Command\PopulateSecretRevisionsForExistingSecretsCommand;
use Passbolt\SecretRevisions\Event\SecretRevisionsAfterResourceCreatedEventListener;

class SecretRevisionsPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        Configure::write('passbolt.plugins.secretRevisions.isInBeta', true);

        $app->getEventManager()->on(new SecretRevisionsAfterResourceCreatedEventListener());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(PopulateCreatedByAndModifiedByInSecretsCommand::class)
            ->addArgument(ProcessUserService::class);
        $container->add(PopulateSecretRevisionsForExistingSecretsCommand::class)
            ->addArgument(ProcessUserService::class);
    }
}
