<?php
/**
 * Our Install command
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Console.Command.InstallShell
 * @since        version 2.12.11
 */

class InstallShell extends AppShell {

	public function main() {
		$this->schema();
		$this->data();
		$this->out("\npassbolt installation success");
	}

	public function schema() {
		$this->dispatchShell('schema create --force_drop --force_create -q');
		$this->out('passbolt schema deployed');
		$this->dispatchShell('schema create --plugin FileStorage --force_drop --force_create -q');
		$this->out('plugins schemas deployed');
	}

	public function data() {
		CakePlugin::load('DataExtras');
		$this->dispatchShell('DataExtras.Data import');
		$this->out('data deployed');
	}

}