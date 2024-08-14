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
 * @since         2.0.0
 */
namespace App\Command;

use App\Service\Command\ProcessUserService;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\HealthcheckWithOptionsInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Collection\Collection;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class HealthcheckCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;
    use FeaturePluginAwareTrait;

    /**
     * Total number of errors for that check
     *
     * @var int
     */
    private $__errorCount = 0;

    /**
     * Control what get displayed / what to hide
     *
     * @var array
     */
    protected $_displayOptions = [
        'hide-pass' => false,
        'hide-warning' => false,
        'hide-help' => false,
        'hide-title' => false,
        'hide-notice' => false,
    ];

    /**
     * @var \Cake\Console\ConsoleIo
     */
    private $io;

    /**
     * @var \Cake\Console\Arguments
     */
    private $args;

    /**
     * @var \App\Service\Command\ProcessUserService
     */
    protected ProcessUserService $processUserService;

    private HealthcheckServiceCollector $healthcheckServiceCollector;

    /**
     * @param \App\Service\Command\ProcessUserService $processUserService Process user service
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector Health check service collector.
     */
    public function __construct(
        ProcessUserService $processUserService,
        HealthcheckServiceCollector $healthcheckServiceCollector
    ) {
        parent::__construct();

        $this->processUserService = $processUserService;
        $this->healthcheckServiceCollector = $healthcheckServiceCollector;
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Check the configuration of this installation and associated environment.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        // Display options
        $parser
            ->addOption('hide-pass', [
                'help' => __d('cake_console', 'Hide passing checks.'),
                'boolean' => true,
            ])
            ->addOption('hide-warning', [
                'help' => __d('cake_console', 'Hide warnings.'),
                'boolean' => true,
            ])
            ->addOption('hide-help', [
                'help' => __d('cake_console', 'Hide help messages.'),
                'boolean' => true,
            ])
            ->addOption('hide-title', [
                'help' => __d('cake_console', 'Hide section titles.'),
                'boolean' => true,
            ])
            ->addOption('hide-notice', [
                'help' => __d('cake_console', 'Hide info messages.'),
                'boolean' => true,
            ]);

        // Checks
        $domains = $this->healthcheckServiceCollector->getDomainsInCollectedServices();
        foreach ($domains as $domain) {
            $domainReadable = $this->healthcheckServiceCollector->getTitleFromDomain($domain);
            $parser->addOption($domain, [
                'help' => __d('cake_console', 'Run {0} tests only.', $domainReadable),
                'boolean' => true,
            ]);
        }

        $this->addDatasourceOption($parser);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $this->io = $io;
        $this->args = $args;

        // Root user is not allowed to execute this command.
        $this->assertCurrentProcessUser($io, $this->processUserService);

        // display options
        $displayOptions = array_keys($this->_displayOptions);
        foreach ($displayOptions as $option) {
            $this->_displayOptions[$option] = $args->getOption($option);
        }

        // If user only want to run one check
        $paramChecks = [];
        $healthcheckServices = $this->healthcheckServiceCollector->getServices();
        foreach ($healthcheckServices as $healthcheckService) {
            if (
                $healthcheckService instanceof HealthcheckCliInterface
                && $args->getOption($healthcheckService->cliOption())
            ) {
                $paramChecks[] = $healthcheckService;
            }

            if ($healthcheckService instanceof HealthcheckWithOptionsInterface) {
                $healthcheckService->setOptions($this->args->getOptions());
            }
        }
        if (count($paramChecks)) {
            $healthcheckServices = $paramChecks;
        }

        $io->out(' Healthcheck shell', 0);
        // Get services from collector and run checks
        $resultCollection = new Collection([]);
        foreach ($healthcheckServices as $healthcheckService) {
            $io->out('.', 0); // Print a dot for each checks to show progress
            $result = $healthcheckService->check();
            $resultCollection = $this->appendResult($resultCollection, $result);
        }

        // Remove all dots
        $io->out(str_repeat(chr(0x08), $resultCollection->count()) . str_repeat(' ', $resultCollection->count()), 0);

        $io->out();
        $io->hr();

        // Print results
        $resultsGroupByDomain = $resultCollection->groupBy(function ($result) {
            return $result->domain();
        });
        foreach ($resultsGroupByDomain as $domain => $checkResults) {
            $this->title($this->healthcheckServiceCollector->getTitleFromDomain($domain));

            foreach ($checkResults as $checkResult) {
                $this->render($checkResult);
            }
        }

        $io->out();
        $this->summary();

        return $this->successCode();
    }

    /**
     * @param \Cake\Collection\Collection $resultCollection result collection
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result healthcheck
     * @return \Cake\Collection\Collection
     */
    private function appendResult(Collection $resultCollection, HealthcheckServiceInterface $result): Collection
    {
        $skipResult = $result instanceof SkipHealthcheckInterface && $result->isSkipped();

        if (!$skipResult) {
            /** @var \Cake\Collection\Collection $resultCollection */
            $resultCollection = $resultCollection->appendItem($result);
        }

        return $resultCollection;
    }

    /**
     * Print result of given health check.
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $healthcheckService Health check service.
     * @return void
     */
    public function render(HealthcheckServiceInterface $healthcheckService): void
    {
        switch ($healthcheckService->level()) {
            case HealthcheckServiceCollector::LEVEL_ERROR:
                $this->assert(
                    $healthcheckService->isPassed(),
                    $healthcheckService->getSuccessMessage(),
                    $healthcheckService->getFailureMessage(),
                    $healthcheckService->getHelpMessage()
                );
                break;
            case HealthcheckServiceCollector::LEVEL_WARNING:
                $this->warning(
                    $healthcheckService->isPassed(),
                    $healthcheckService->getSuccessMessage(),
                    $healthcheckService->getFailureMessage(),
                    $healthcheckService->getHelpMessage()
                );
                break;
            case HealthcheckServiceCollector::LEVEL_NOTICE:
                $this->notice(
                    $healthcheckService->isPassed(),
                    $healthcheckService->getSuccessMessage(),
                    $healthcheckService->getFailureMessage(),
                    $healthcheckService->getHelpMessage()
                );
                break;
        }
    }

    /**
     * Display a success or error message depending on given condition
     *
     * @param bool $condition to check
     * @param string|string[] $success to display when success
     * @param string|string[] $error to display when error
     * @param string|string[]|null $help string optional help message
     * @return void
     */
    protected function assert(bool $condition, $success, $error, $help = null)
    {
        if ($condition) {
            $this->display($success, 'pass');
        } else {
            $this->__errorCount++;
            $this->display($error, 'fail');
            $this->help($help);
        }
    }

    /**
     * Display a success or warning message depending on given condition
     *
     * @param bool $condition to check
     * @param string|string[] $success message to display when success
     * @param string|string[] $warning message to display if fails
     * @param string|string[]|null $help optional help message
     * @return void
     */
    protected function warning(bool $condition, $success, $warning, $help = null)
    {
        if ($this->_displayOptions['hide-warning']) {
            return;
        }
        if ($condition) {
            $this->display($success, 'pass');
        } else {
            $this->display($warning, 'warn');
            $this->help($help);
        }
    }

    /**
     * @param string $msg message
     * @return void
     * @throws \Exception
     */
    protected function info(string $msg): void
    {
        $this->display($msg, 'info');
    }

    /**
     * Display one or more help messages
     *
     * @param string|string[]|null $help messages
     * @return void
     */
    protected function help($help = null)
    {
        if (isset($help)) {
            if (is_array($help)) {
                foreach ($help as $helpMsg) {
                    $this->display($helpMsg, 'info');
                }
            } else {
                $this->display($help, 'info');
            }
        }
    }

    /**
     * Display a notice message, and a help message if condition is false
     *
     * @param bool $condition to check
     * @param string|string[] $success info message to display when success
     * @param string|string[] $fail info message to display if fails
     * @param string|string[]|null $help optional help message
     * @return void
     */
    protected function notice(bool $condition, $success, $fail, $help = null): void
    {
        if ($this->_displayOptions['hide-notice']) {
            return;
        }
        if ($condition) {
            $this->display($success, 'notice');
        } else {
            $this->display($fail, 'notice');
            $this->help($help);
        }
    }

    /**
     * Display a message for given case
     *
     * @param string|string[] $msg message
     * @param string $case pass or fail
     * @return void
     */
    protected function display($msg, string $case)
    {
        switch ($case) {
            case 'pass':
                if ($this->_displayOptions['hide-pass']) {
                    return;
                }
                $msg = ' <success>[' . __('PASS') . ']</success> ' . $msg;
                break;
            case 'fail':
                $msg = ' <error>[' . __('FAIL') . '] ' . $msg . '</error>';
                break;
            case 'warn':
                $msg = ' <warning>[' . __('WARN') . '] ' . $msg . '</warning>';
                break;
            case 'info':
                if ($this->_displayOptions['hide-help']) {
                    return;
                }
                $msg = ' <info>[' . __('HELP') . ']</info> ' . $msg;
                break;
            case 'notice':
                $msg = ' <info>[' . __('INFO') . ']</info> ' . $msg;
                break;
            default:
                throw new \Exception('Task output case not defined: ' . $case . ' ' . $msg);
        }
        $this->io->out($msg);
    }

    /**
     * Title section display
     *
     * @param string $title message
     * @return void
     */
    protected function title(string $title)
    {
        if ($this->_displayOptions['hide-title']) {
            return;
        }
        $this->io->out('');
        $this->io->out(' ' . $title);
        $this->io->out('');
    }

    /**
     * Display a summary with an error count if any
     *
     * @return void
     */
    protected function summary()
    {
        if ($this->__errorCount >= 1) {
            $this->display(__('{0} error(s) found. Hang in there!', $this->__errorCount), 'fail');
        } else {
            $this->display(__('No error found. Nice one sparky!'), 'pass');
        }
        $this->io->out('');
    }
}
