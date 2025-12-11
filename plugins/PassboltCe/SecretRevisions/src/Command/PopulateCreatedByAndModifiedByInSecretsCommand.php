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
 * @since         5.8.0
 */
namespace Passbolt\SecretRevisions\Command;

use App\Command\PassboltCommand;
use App\Service\Command\ProcessUserService;
use App\Service\Secrets\PopulateCreatedByAndModifiedByInSecretsService;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class PopulateCreatedByAndModifiedByInSecretsCommand extends PassboltCommand
{
    /**
     * @var \App\Service\Command\ProcessUserService
     */
    protected ProcessUserService $processUserService;

    /**
     * @param \App\Service\Command\ProcessUserService $processUserService Process user service.
     */
    public function __construct(ProcessUserService $processUserService)
    {
        parent::__construct();
        $this->processUserService = $processUserService;
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Populate created_by and modified_by fields in secrets table.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription($this->getCommandDescription());

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $this->assertCurrentProcessUser($io, $this->processUserService);

        $io->out(__('Populating created_by and modified_by fields in secrets table...'));

        (new PopulateCreatedByAndModifiedByInSecretsService())->populate();

        $io->success(__('Secrets table populated successfully.'));

        return $this->successCode();
    }
}
