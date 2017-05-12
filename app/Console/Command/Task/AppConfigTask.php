<?php
/**
 * App Config task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Hash', 'Utility');

class AppConfigTask extends AppShell {

/**
 * Contains tasks to load and instantiate
 *
 * @var array
 */
	public $tasks = array();

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->description(__('Manipulate the passbolt app configuration'))
			->addArgument('action', [
				'index' => 0,
				'required' => true,
				'choices' => ['write'],
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
			case 'write':
				$this->_write($this->args[0], $this->args[1]);
				break;
		}
	}

/**
 * Write configuration variable
 *
 * @param string $name item name
 * @param string $value item value
 * @return void
 */
	protected function _write($name, $value) {
		$appConfigFile = APP . 'Config' . DS . 'app.php';
		require $appConfigFile;
		$config = Set::insert($config, $name, $value);

		$textConfig = '<?php
/**
 * Main application configuration file
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$config = ' . var_export($config, true) . ';';
		file_put_contents($appConfigFile, $textConfig);
	}

}
