<?php
/**
 * Healthcheck task
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Healthchecks', 'Lib');

class HealthcheckTask extends AppShell {
    private $__errorCount = 0;

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
        $this->stdout->styles(
            'fail', array('text' => 'red', 'blink' => false),
            'success', array('text' => 'green', 'blink' => false)
        );

		$parser = parent::getOptionParser();
		$parser
			->description(__('Check the configuration of the passbolt installation and associated environment'));

		return $parser;
	}

/**
 * Display the passbolt ascii banner
 *
 * @return void
 */
    protected function _welcome() {
        $this->hr();
        $this->out('     ____                  __          ____  ');
        $this->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
        $this->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
        $this->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
        $this->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
        $this->out('');
        $this->out(' Open source password manager for teams');
        $this->hr();
        $this->out(' Healthcheck shell');
        $this->hr();
    }

/**
 * Register a new user.
 *
 * @return void
 */
	public function execute() {
	    $this->_welcome();

	    // All checks
        $checks = Healthchecks::all();
        $this->assertEnvironment($checks);
        $this->assertConfigFiles($checks);
        $this->out('');

        // Final message
        $this->summary();
	}

/**
 * Assert all the checks
 *
 * @param $checks array
 * @return void
 */
    protected function assertEnvironment($checks) {
        $this->out('');
        $this->out(' '. __('Environment'));
        $this->out('');

        $this->assert(
            $checks['environment']['phpVersion'],
            __('PHP version %s.', PHP_VERSION),
            __('PHP version is too low, passbolt need PHP 5.2.8 or higher.')
        );

        $this->assert(
            $checks['environment']['PCRE'],
            __('PCRE compiled with unicode support'),
            __('PCRE has not been compiled with Unicode support.') . ' '
            . __('Recompile PCRE with Unicode support by adding --enable-unicode-properties when configuring')
        );

        $this->assert(
            $checks['environment']['tmpWritable'],
            __('The app/tmp directory is writable.'),
            __('The app/tmp directory is not writable.')
        );

        $this->assert(
            $checks['environment']['imgPublicWritable'],
            __('The app/webroot/img/public directory is writable.'),
            __('The app/webroot/img/public directory is not writable.')
        );

    }

/**
 * Assert all the checks
 *
 * @param $checks array
 * @return void
 */
    protected function assertConfigFiles($checks) {

        $this->out('');
        $this->out(' '. __('Config files'));
        $this->out('');
        $this->assert(
            $checks['configFile']['core'],
            __('The core config file is present'),
            __('The core config file is missing in app/Core.') . ' '
            . __('Copy %s to %s', 'app/Config/core.php.default', 'app/Config/core.php')
        );

        $this->assert(
            $checks['configFile']['database'],
            __('The database config file is present'),
            __('The database config file is missing in app/Core.') . ' '
            . __('Copy %s to %s', 'app/Config/database.php.default', 'app/Config/database.php')
        );

        $this->assert(
            $checks['configFile']['email'],
            __('The email config file is present'),
            __('The email config file is missing in app/Core.') . ' '
            . __('Copy %s to %s', 'app/Config/email.php.default', 'app/Config/email.php')
        );

        $this->assert(
            $checks['configFile']['app'],
            __('The application config file is present'),
            __('The application config file is missing in app/Core.') . ' '
            . __('Copy %s to %s', 'app/Config/app.php.default', 'app/Config/app.php')
        );
    }

/**
 * Display a success or error message depending on given condition
 *
 * @param $condition bool
 * @param $success string
 * @param $error string
 * @return void
 */
    protected function assert($condition, $success, $error) {
        if($condition) {
            $this->display($success, 'pass');
        } else {
            $this->__errorCount++;
            $this->display($error, 'fail');
        }
    }

/**
 * Display a message for given case
 *
 * @param $msg string
 * @param $case string pass or fail
 * @throws Exception case is not defined or missing
 * @return void
 */
    protected function display($msg, $case) {

        switch ($case) {
            case 'pass':
                $msg = ' <success>['. __('PASS') . ']</success> ' . $msg;
            break;
            case 'fail':
                $msg = ' <fail>['. __('FAIL') . ']</fail> ' . $msg;
            break;
            default:
                throw new Exception('Task output case not defined: ' . $case . ' ' . $msg);
            break;
        }
        $this->out($msg);
    }

/**
 * Display a summary with an error count if any
 *
 * @return void
 */
    protected function summary() {
        if($this->__errorCount >= 1) {
            $summary = ' <fail> ' . __('%s error(s) found. Please correct before installing.' . '</fail>', $this->__errorCount);
        } else {
            $summary = ' <success>' . __('No error found. Nice one sparky!') . '</success>';
        }
        $this->out($summary);
        $this->out('');
    }
}
