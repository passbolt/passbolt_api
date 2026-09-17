<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.2.0
 */
namespace Passbolt\Edition\Command;

use App\Command\PassboltCommand;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Service\EditionManager;
use Passbolt\Edition\Service\EditionSetService;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyImportService;

/**
 * Imports a subscription key supplied via file or text and flips the edition
 * to PRO. Typically invoked by the Docker entrypoint when a key file is
 * mounted into the container, so it runs on every container restart.
 *
 * Skips when the instance was explicitly downgraded to CE — detected when
 * `Configure::passbolt.edition` is `ce` AND
 * `Configure::passbolt.editionLastChangeDateTime` is non-null (the row's
 * `modified` differs from `created`). A fresh CE install or one populated by
 * V5130PopulateEdition has matching `created` / `modified` and a null
 * change timestamp, so the import still proceeds in that case.
 */
class SubscriptionImportCommand extends PassboltCommand
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Import a subscription key using file or text.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('file', [
            'short' => 'f',
            'help' => __('Path to subscription key file.'),
            'default' => SubscriptionKeyGetService::SUBSCRIPTION_FILE,
        ]);
        $parser->addOption('text', [
            'short' => 't',
            'help' => __('Subscription key text (Base 64).'),
            'default' => '',
        ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $text = $args->getOption('text');
        $file = $args->getOption('file');
        $useText = !empty($text);

        if ($useText && !is_string($text)) {
            $this->error(__('The subscription key text is not valid.'), $io);
            $this->abort();
        }
        if (!$useText && (empty($file) || !is_string($file))) {
            $this->error(__('The subscription key file is invalid.'), $io);
            $this->abort();
        }

        if (
            Configure::read('passbolt.edition') === EditionDto::EDITION_CE
            && Configure::read(EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME) instanceof DateTime
        ) {
            $io->warning([
                __('A subscription key was provided but the instance was explicitly downgraded to CE edition.'),
                __('Ignoring the subscription key.'),
                __(
                    'Remove the subscription file mount to silence this warning '
                    . 'or re-upload the key from the admin UI.',
                ),
            ]);

            return $this->successCode();
        }

        $uac = $this->buildAdminUac();
        try {
            /** @var \Passbolt\Edition\Model\Table\EditionOrganizationTable $editionTable */
            $editionTable = $this->fetchTable('Passbolt/Edition.EditionOrganization');
            $editionTable->getConnection()->transactional(
                function () use ($useText, $text, $file, $uac): void {
                    // Save subscription key
                    $importService = new SubscriptionKeyImportService();
                    if ($useText) {
                        /** @var string $text */
                        $importService->import($text, $uac);
                    } else {
                        /** @var string $file */
                        $importService->importFromFile($file, $uac);
                    }

                    // Set edition to PRO in the DB
                    (new EditionSetService())->setToPro($uac);
                },
            );
        } catch (SubscriptionException $e) {
            $this->error($e->getMessage(), $io);
            $this->abort();
        } catch (CakeException $e) {
            $this->error($e->getMessage(), $io);
            $this->abort();
        }

        $this->success(__('The subscription key was successfully imported in the database.'), $io);

        return $this->successCode();
    }

    /**
     * @return \App\Utility\UserAccessControl
     * @throws \Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException If no active admin exists.
     */
    private function buildAdminUac(): UserAccessControl
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $firstAdmin = $usersTable->findFirstAdmin();
        if ($firstAdmin === null) {
            throw new SubscriptionException(__('No active admins were found.'));
        }

        return new UserAccessControl(Role::ADMIN, $firstAdmin->id);
    }
}
