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
 * @since         3.4.0
 */
namespace Passbolt\EmailDigest;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Passbolt\EmailDigest\Command\PreviewCommand;
use Passbolt\EmailDigest\Command\SenderCommand;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $this->setEmailTemplateVariablesSerializationType();
    }

    /**
     * Emails are stored serialized in Json format.
     * Decoding is made with associative array.
     *
     * @see EmailQueueTable::_initializeSchema()
     * @return void
     */
    public function setEmailTemplateVariablesSerializationType(): void
    {
        // Set the EmailQueue Serialization type to json.
        Configure::write('EmailQueue.serialization_type', 'email_queue.json');
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        // Alias commands
        $commands->add('passbolt email_digest preview', PreviewCommand::class);
        $commands->add('passbolt email_digest send', SenderCommand::class);

        return $commands;
    }
}
