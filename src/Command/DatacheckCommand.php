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
 * @since         2.13.0
 */
namespace App\Command;

use App\Service\AuthenticationTokens\AuthenticationTokensHealthcheckService;
use App\Service\Comments\CommentsHealthcheckService;
use App\Service\Favorites\FavoritesHealthcheckService;
use App\Service\Gpgkeys\GpgkeysHealthcheckService;
use App\Service\Groups\GroupsHealthcheckService;
use App\Service\Profiles\ProfilesHealthcheckService;
use App\Service\Resources\ResourcesHealthcheckService;
use App\Service\Secrets\SecretsHealthcheckService;
use App\Service\Users\UsersHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Exception\CakeException;
use Cake\Utility\Hash;

class DatacheckCommand extends PassboltCommand
{
    /**
     * @var \Cake\Console\Arguments
     */
    private $args;

    /**
     * @var \Cake\Console\ConsoleIo
     */
    private $io;

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Re-validate the data of this installation.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        // Display options
        $parser
            ->addOption('hide-success-details', [
                'help' => __d('cake_console', 'Hide passing checks details.'),
                'boolean' => true,
            ])
            ->addOption('hide-error-details', [
                'help' => __d('cake_console', 'Hide passing checks details.'),
                'boolean' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $this->args = $args;
        $this->io = $io;

        $io->out('Data check shell');

        $services[] = new AuthenticationTokensHealthcheckService();
        $services[] = new CommentsHealthcheckService();
        $services[] = new FavoritesHealthcheckService();
        $services[] = new GpgkeysHealthcheckService();
        $services[] = new GroupsHealthcheckService();
        $services[] = new ProfilesHealthcheckService();
        $services[] = new ResourcesHealthcheckService();
        $services[] = new SecretsHealthcheckService();
        $services[] = new UsersHealthcheckService();

        foreach ($services as $i => $service) {
            try {
                $results = $service->check();
                $this->displayResults($results, $service->getServiceCategory(), $service->getServiceName());
            } catch (\Exception $exception) {
                $io->out($exception->getMessage());
            }
        }

        return $this->successCode();
    }

    /**
     * Display all results
     *
     * @param array $results checks results
     * @param string $serviceCategory healthcheck service category ('data')
     * @param string $serviceName section name
     * @return void
     */
    protected function displayResults(array $results, string $serviceCategory, string $serviceName)
    {
        $results = $results[$serviceCategory];
        $this->displayServiceTotal($results, $serviceName);
        foreach ($results as $name => $subresults) {
            $details = $subresults->getDetails();
            $this->displayCheckTotal($details, $name);
            $this->displayCheckDetails($details);
        }
    }

    /**
     * Display grand total
     *
     * @param array $results results
     * @param string $serviceName section name
     * @return void
     */
    protected function displayServiceTotal(array $results, string $serviceName)
    {
        $status = !count(Hash::extract($results, '{s}.{s}.{n}[status=error]')) ?
            Healthcheck::STATUS_SUCCESS : Healthcheck::STATUS_ERROR;
        $this->display(__('Data integrity for {0}.', $serviceName), $status);
    }

    /**
     * Display a given check total
     *
     * @param array $details details
     * @param string $name name
     * @return void
     */
    protected function displayCheckTotal(array $details, string $name)
    {
        $success = count(Hash::extract($details, '{n}[status=success]'));
        $total = count($details);
        $msg = $name . ': ' . $success . '/' . $total;
        $status = $success === $total ? Healthcheck::STATUS_SUCCESS : Healthcheck::STATUS_ERROR;
        $this->display($msg, $status, 2);
    }

    /**
     * Display a given check details
     *
     * @param array $details results details
     * @return void
     */
    protected function displayCheckDetails(array $details)
    {
        $count = 0;
        foreach ($details as $detail) {
            $hideSuccessDetail = $this->args->getOption('hide-success-details');
            $hideErrorDetail = $this->args->getOption('hide-error-details');
            if (
                (!$hideSuccessDetail && $detail['status'] === Healthcheck::STATUS_SUCCESS) ||
                (!$hideErrorDetail && $detail['status'] === Healthcheck::STATUS_ERROR)
            ) {
                $count++;
                $this->display($detail['message'], $detail['status'], 4);
            }
        }
    }

    /**
     * Display a message for given case
     *
     * @param string $msg message
     * @param string $case pass or fail
     * @param int $padding how many space char in front
     * @throws \Cake\Core\Exception\CakeException case is not defined or missing
     * @return void
     */
    protected function display(string $msg, string $case, int $padding = 0)
    {
        $pad = '';
        for ($i = 0; $i < $padding; $i++) {
            $pad .= ' ';
        }

        switch ($case) {
            case Healthcheck::STATUS_SUCCESS:
                $msg = $pad . '<success>[' . __('PASS') . ']</success> ' . $msg;
                break;
            case Healthcheck::STATUS_ERROR:
                $msg = $pad . '<error>[' . __('FAIL') . '] ' . $msg . '</error>';
                break;
            case Healthcheck::STATUS_WARNING:
                $msg = $pad . '<warning>[' . __('WARN') . '] ' . $msg . '</warning>';
                break;
            case Healthcheck::STATUS_INFO:
                $msg = $pad . ' <info>[' . __('HELP') . ']</info> ' . $msg;
                break;
            default:
                throw new CakeException('Task output case not defined: ' . $case . ' ' . $msg);
        }
        $this->io->out($msg);
    }
}
