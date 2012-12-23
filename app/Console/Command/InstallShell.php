<?php

class InstallShell extends AppShell {
	
	public function main() {
		$this->schema();
		$this->data();
		$this->out("\ninstallation success");
	}

	public function schema() {
		$this->dispatchShell('schema create --force_drop --force_create -q');
		$this->out('schema deployed');
	}
	
	public function data() {
		CakePlugin::load('Data');
		$this->dispatchShell('Data.data install');
		$this->out('data deployed');
	}

}