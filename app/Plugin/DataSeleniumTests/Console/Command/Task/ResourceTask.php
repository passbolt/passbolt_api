<?php
/**
 * Insert Resource Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.ResourceTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Resource', 'Model');

class ResourceTask extends ModelTask {

	public $model = 'Resource';

	public function execute() {
		$Model = ClassRegistry::init($this->model);
		// @todo work on permissionable and save
		$Model->Behaviors->disable('Permissionable');
		$Model->Behaviors->disable('Trackable');
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			$Model->set($item);
			if (!$Model->validates()) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save($item);
			if (!$instance) {
				$this->out('<error>Unable to insert ' . pr($item[$this->model]['name']) . '</error>');
			}
		}
	}

	protected function getData() {
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.apache'),
			'name' => 'apache',
			'username' => 'www-data',
			'expiry_date' => null,
			'uri' => 'http://www.apache.org/',
			'description' => 'Apache is the world\'s most used web server software.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.ada'),
			'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.april'),
			'name' => 'april',
			'username' => 'support',
			'expiry_date' => null,
			'uri' => 'https://www.april.org/',
			'description' => 'L\'association pionnière du logiciel libre en France',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.betty'),
			'modified_by' => Common::uuid('user.id.betty'),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.bower'),
			'name' => 'bower',
			'username' => 'bower',
			'expiry_date' => null,
			'uri' => 'bower.io',
			'description' => 'A package manager for the web!',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.carol'),
			'modified_by' => Common::uuid('user.id.carol'),
			'created' => date('Y-m-d H:i:s', strtotime('-2 years')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 years')),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.centos'),
			'name' => 'centos',
			'username' => 'root',
			'expiry_date' => null,
			'uri' => 'centos.org',
			'description' => 'The CentOS Linux distribution is a platform derived from Red Hat Enterprise Linux (RHEL).',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.dame'),
			'created' => date('Y-m-d H:i:s', strtotime('-2 months')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 months')),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.canjs'),
			'name' => 'Canjs',
			'username' => 'yeswecan',
			'expiry_date' => null,
			'uri' => 'canjs.com',
			'description' => 'CanJS is a JavaScript library that makes developing complex applications simple and fast.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.edith'),
			'modified_by' => Common::uuid('user.id.edith'),
			'created' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 weeks')),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.cakephp'),
			'name' => 'cakephp',
			'username' => 'cake',
			'expiry_date' => null,
			'uri' => 'cakephp.org',
			'description' => 'The rapid and tasty php development framework',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.ada'),
			'created' => date('Y-m-d H:i:s', strtotime('-2 hours')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 hours')),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.chai'),
			'name' => 'chai',
			'username' => 'masala',
			'expiry_date' => null,
			'uri' => 'http://chaijs.com/',
			'description' => 'Chai is a BDD / TDD assertion library for node and the browser',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.betty'),
			'modified_by' => Common::uuid('user.id.betty')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.composer'),
			'name' => 'composer',
			'username' => 'getcomposer',
			'expiry_date' => null,
			'uri' => 'getcomposer.org',
			'description' => 'Dependency Manager for PHP',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.carol'),
			'modified_by' => Common::uuid('user.id.carol'),
			'created' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 minutes')),
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.debian'),
			'name' => 'Debian',
			'username' => 'jessy',
			'expiry_date' => null,
			'uri' => 'passbolt.dev',
			'description' => 'The universal operating system',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.dame')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.docker'),
			'name' => 'Docker',
			'username' => 'docker',
			'expiry_date' => null,
			'uri' => 'https://www.docker.com/',
			'description' => 'An open platform for distributed applications for developers and sysadmins',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.edith'),
			'modified_by' => Common::uuid('user.id.edith')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.enlightenment'),
			'name' => 'Enlightenment',
			'username' => 'efl',
			'expiry_date' => null,
			'uri' => 'https://www.enlightenment.org/',
			'description' => 'Party like it\'s 1996.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.ada')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.fosdem'),
			'name' => 'FOSDEM',
			'username' => 'fodem',
			'expiry_date' => null,
			'uri' => 'fosdem.org',
			'description' => 'FOSDEM is a free event for software developers to meet, share ideas and collaborate.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.betty'),
			'modified_by' => Common::uuid('user.id.betty')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.framasoft'),
			'name' => 'framasoft',
			'username' => 'framasoft',
			'expiry_date' => null,
			'uri' => 'https://soutenir.framasoft.org/',
			'description' => 'Parce que libre ne veut pas dire gratuit!',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.carol'),
			'modified_by' => Common::uuid('user.id.carol')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.fsfe'),
			'name' => 'free software foundation europe',
			'username' => 'fsfe',
			'expiry_date' => null,
			'uri' => 'https://fsfe.org/index.en.html',
			'description' => 'Free Software Foundation Europe is a charity that empowers users to control technology.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.dame')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.ftp'),
			'name' => 'ftp',
			'username' => 'user',
			'expiry_date' => null,
			'uri' => 'ftp://192.168.1.1',
			'description' => 'ftp test',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.edith'),
			'modified_by' => Common::uuid('user.id.edith')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.grogle'),
			'name' => 'Grogle',
			'username' => 'grd',
			'expiry_date' => null,
			'uri' => 'http://fr.groland.wikia.com/wiki/Grogle',
			'description' => '',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.ada')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.grunt'),
			'name' => 'Grunt',
			'username' => 'grunt',
			'expiry_date' => null,
			'uri' => 'gruntjs.com',
			'description' => 'The javascript taskrunner',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.betty'),
			'modified_by' => Common::uuid('user.id.betty')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.gnupg'),
			'name' => 'Gnupg',
			'username' => 'gpg',
			'expiry_date' => null,
			'uri' => 'gnupg.org',
			'description' => 'GnuPG is a complete and free implementation of the OpenPGP standard as defined by RFC4880',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.carol'),
			'modified_by' => Common::uuid('user.id.carol')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.git'),
			'name' => 'Git',
			'username' => 'git',
			'expiry_date' => null,
			'uri' => 'git-scm.com',
			'description' => 'Git is a free and open source distributed version control system.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.dame')
		));
		$r[] = array('Resource' => array(
			'id' => Common::uuid('resource.id.inkscape'),
			'name' => 'Inkscape',
			'username' => 'vector',
			'expiry_date' => null,
			'uri' => 'https://inkscape.org/',
			'description' => 'Inkscape is a professional vector graphics editor. It is free and open source.',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.edith'),
			'modified_by' => Common::uuid('user.id.edith')
		));
//		$r[] = array('Resource' => array(
//			'id' => Common::uuid('resource.id.'),
//			'name' => '',
//			'username' => '',
//			'expiry_date' => null,
//			'uri' => '',
//			'description' => '',
//			'deleted' => 0,
//			'created_by' => Common::uuid('user.id.'),
//			'modified_by' => Common::uuid('user.id.')
//		));
		return $r;
	}
}
