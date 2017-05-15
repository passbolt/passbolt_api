<?php
/**
 * Import Database From a SQL dump file
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AppShell', 'Console/Command');
App::uses('ConnectionManager', 'Model');
require_once ( APP . 'Config' . DS . 'database.php');

class SqlShell extends AppShell {

	public $file; // file to use for import

/**
 * Define command line options
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->addOption('file', array(
				'help' => 'The file to import/export the schema and data from/to',
				'default' => '',
				'short' => 'f',
			))
			->addOption('data', array(
				'help' => 'The data set to use for import/export the schema and data from/to',
				'default' => 'default',
				'short' => 'f',
			))
			->addOption('datasource', array(
					'help' => 'The database configuration to use',
					'default' => 'default',
					'short' => 'd',
			))
			->description(__('A SQL dump import shell'));

		return $parser;
	}

/**
 * Displays a header for the shell
 *
 * @return void
 */
	protected function _welcome() {
		parent::_welcome();

		// define the file to use
		if ($this->params['file'] != '') {
			$this->file = $this->params['file'];
		} else {
			$this->file = APP . 'tmp' . DS . 'schema' . DS . $this->params['datasource'] . '_' . $this->params['data'] . '.sql';
		}

		$this->out(' Datasource : ' . $this->params['datasource']);
		$this->out(' File :' . $this->file);
	}

/**
 * Import
 *
 * @return bool
 */
	public function import() {
		// run the sql file
		$dataSource = ConnectionManager::getDataSource($this->params['datasource']);
		if (!file_exists($this->file)) {
			$this->out(' Error: could not find the SQL file ');
			$this->out();
			return false;
		}
		$sql = file_get_contents($this->file, FILE_USE_INCLUDE_PATH);

		// success handling
		try {
			$resultSrc = $dataSource->execute($sql);
			if ($resultSrc === true) {
				$this->out(' Success: SQL file imported');
				$this->out();
				return true;
			}
		} catch(exception $e) {
			// Do nothing, see bellow
		}

		// error handling
		$this->out('Error: Something went wrong when importing the SQL file');
		$this->out();
		return false;
	}

/**
 * Export
 *
 * @return bool
 */
	public function export() {
		// Check if the data source is in the config
		$config = new DATABASE_CONFIG();
		if (!isset($config->{$this->params['datasource']})) {
			$this->out('Error: data source not found');
			return false;
		}

		// Check the database support
		$config = $config->{$this->params['datasource']};
		if ($config['datasource'] != 'Database/Mysql') {
			$this->out('Error: Sorry only mySQL is supported at the moment');
			return false;
		}

		// Build the dump command.
		$cmd = 'mysqldump -h ' . $config['host'] . ' -u ' . $config['login'];
		if (!empty($config['password'])) {
			$cmd .= ' -p' . $config['password'];
		}
		$cmd .= ' ' . $config['database'] . ' > ' . $this->file;

		exec($cmd, $output, $status);
		if ($status == 2) {
			$this->out(' Error: Something went wrong!');
			return false;
		} else {
			$this->out(' Success: the database was saved on file!');
			return true;
		}
	}
}
