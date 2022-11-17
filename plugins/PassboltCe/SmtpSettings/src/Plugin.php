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
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\SmtpSettings\Service\SmtpSettingsFormatSenderInEmailQueueService;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $this->formatSenderInEmailQueue();
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(SmtpSettingsSendTestEmailService::class);
    }

    /**
     * @return void
     */
    public function formatSenderInEmailQueue(): void
    {
        (new SmtpSettingsFormatSenderInEmailQueueService())->attachBeforeFindOnEmailQueueTables();
    }
}
