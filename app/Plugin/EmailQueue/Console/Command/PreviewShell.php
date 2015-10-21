<?php
App::uses('AppShell', 'Console/Command');
App::uses('CakeEmail', 'Network/Email');
App::uses('ClassRegistry', 'Utility');

class PreviewShell extends AppShell {

	public function main() {
		Configure::write('App.baseUrl', '/');

		$conditions = array();
		if ($this->args) {
			$conditions['id'] = $this->args;
		}

		$emailQueue = ClassRegistry::init('EmailQueue.EmailQueue');
		$emails = $emailQueue->find('all', array(
			'conditions' => $conditions
		));

		if (!$emails) {
			$this->out('No emails found');
			return;
		}

		$this->clear();
		foreach ($emails as $i => $email) {
			if ($i) {
				$this->out('Hit a key to continue');
				`read foo`;
				$this->clear();
			}
			$this->out("Email :" . $email['EmailQueue']['id']);
			$this->preview($email);
		}
	}

	public function preview($e) {
		$configName = $e['EmailQueue']['config'];
		$template = $e['EmailQueue']['template'];
		$layout = $e['EmailQueue']['layout'];
		$headers = empty($e['EmailQueue']['headers']) ? array() : (array)$e['EmailQueue']['headers'];

		$email = new CakeEmail($configName);
		$email->transport('Debug')
			->to($e['EmailQueue']['to'])
			->subject($e['EmailQueue']['subject'])
			->template($template, $layout)
			->emailFormat($e['EmailQueue']['format'])
			->addHeaders($headers)
			->viewVars($e['EmailQueue']['template_vars']);

		$return = $email->send();

		$this->out('Content:');
		$this->hr();
		$this->out($return['message']);
		$this->hr();
		$this->out('Headers:');
		$this->hr();
		$this->out($return['headers']);
		$this->hr();
		$this->out('Data:');
		$this->hr();
		debug ($e['EmailQueue']['template_vars']);
		$this->hr();
		$this->out();
	}

}
