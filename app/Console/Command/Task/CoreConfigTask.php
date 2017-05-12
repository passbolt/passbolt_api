<?php

/**
 * Core Config task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Hash', 'Utility');

class CoreConfigTask extends AppShell {

/**
 * Contains tasks to load and instantiate
 *
 * @var array
 */
	public $tasks = array('Project');

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->description(__('Manipulate the passbolt core configuration'))
			->addArgument('action', [
				'index' => 0,
				'required' => true,
				'choices' => ['write', 'gen-cipher-seed', 'gen-security-salt'],
				'help' => __('The action to perform on the config')
			])
			->addArgument('config-name', [
				'short' => 'n',
				'index' => 1,
				'help' => __('The configuration variable name')
			])
			->addArgument('config-value', [
				'short' => 'v',
				'index' => 2,
				'help' => __('The configuration variable value')
			]);

		return $parser;
	}

/**
 * Config actions dispatcher
 *
 * @return void
 */
	public function execute() {
		$action = array_shift($this->args);
		switch ($action) {
			case 'gen-cipher-seed':
				$this->_generateCipherSeed();
				break;
			case 'gen-security-salt':
				$this->_generateSecuritySalt();
				break;
			case 'write':
				$this->_write($this->args[0], $this->args[1]);
				break;
		}
	}

/**
 * Generate a random cipher seed and update the core.php file
 *
 * @return void
 */
	protected function _generateCipherSeed() {
		$this->Project->securityCipherSeed(APP);
	}

/**
 * Generate a random security salt and update the core.php file
 *
 * @return void
 */
	protected function _generateSecuritySalt() {
		$this->Project->securitySalt(APP);
	}

/**
 * Write configuration variable
 *
 * @param string $name item name
 * @param string $value item value
 * @return bool
 */
	protected function _write($name, $value) {
		$File = new File(APP . 'Config' . DS . 'core.php');
		$contents = $File->read();
		if (preg_match('/(.*Configure::write\(\'' . $name . '\'.*;)/', $contents, $match)) {
			App::uses('Security', 'Utility');
			$result = str_replace($match[0], "\t" . 'Configure::write(\'' . $name . '\', \'' . $value . '\');', $contents);
			if ($File->write($result)) {
				return true;
			}
			return false;
		}
		return false;
	}

}
