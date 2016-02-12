<?php
/**
 * Insert Category Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.CategoryTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Category', 'Model');

class CategoryTask extends ModelTask {

	public $model = 'Category';

	public function execute() {
		$Model = ClassRegistry::init($this->model);
		// @todo work on permissionable and save
		//$Model->hasOne = array();
		$Model->Behaviors->disable('Permissionable');
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			if (!$m = $Model->save($item)) {
				pr($Model->validationErrors);
				$errorMsg = mysql_error();
				echo $errorMsg;
			}
		}
	}

	public static function getAlias() {
		$Category = ClassRegistry::init('Category');
		$aliases = array(
			// Passbolt case, mainly used to test permission system inheritance
			'root' => $Category->findByName("Bolt Softwares Pvt. Ltd."),
			'adm' => $Category->findByName("administration"),
			'acc' => $Category->findByName("accounts"),
			'mar' => $Category->findByName("marketing"),
			'hr' => $Category->findByName("human resource"),
			'mis' => $Category->findByName("misc"),
			'pro' => $Category->findByName("projects"),
			'cak' => $Category->findByName("cakephp"),
			'cp1' => $Category->findByName("cp-project1"),
			'cp2' => $Category->findByName("cp-project2"),
			'cp3' => $Category->findByName("cp-project3"),
			'dru' => $Category->findByName("drupal"),
			'dp1' => $Category->findByName("d-project1"),
			'dp2' => $Category->findByName("d-project2"),
			'oth' => $Category->findByName("others"),
			'op1' => $Category->findByName("o-project1"),
			'op2' => $Category->findByName("o-project2"),
			'pvt' => $Category->findByName("private"),
			'pjr' => $Category->findByName("pv-jean_bartik"),

			// Unit tests sandbox category
			'utt' => $Category->findByName("utest")
		);
		foreach ($aliases as $name => $obj) {
			$aliases[$name] = $obj['Category']['id'];
		}
		return $aliases;
	}
	
	protected function getData() {
		// Passbolt main Use case
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.bolt'),
			'parent_id' => null,
			'name' => 'Bolt Softwares Pvt. Ltd.',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.empty'),
			'parent_id' => Common::uuid('category.id.bolt'),
			'name' => 'empty',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-03-18 03:34:39',
			'modified' => '2014-03-18 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.administration'),
			'parent_id' => Common::uuid('category.id.bolt'),
			'name' => 'administration',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.accounts'),
			'parent_id' => Common::uuid('category.id.administration'),
			'name' => 'accounts',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.marketing'),
			'parent_id' => Common::uuid('category.id.administration'),
			'name' => 'marketing',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.human'),
			'parent_id' => Common::uuid('category.id.administration'),
			'name' => 'human resource',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.misc'),
			'parent_id' => Common::uuid('category.id.administration'),
			'name' => 'misc',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.projects'),
			'parent_id' => Common::uuid('category.id.bolt'),
			'name' => 'projects',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.cakephp'),
			'parent_id' => Common::uuid('category.id.projects'),
			'name' => 'cakephp',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.cp-project1'),
			'parent_id' => Common::uuid('category.id.cakephp'),
			'name' => 'cp-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.cp-project2'),
			'parent_id' => Common::uuid('category.id.cakephp'),
			'name' => 'cp-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.drupal'),
			'parent_id' => Common::uuid('category.id.projects'),
			'name' => 'drupal',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.d-project1'),
			'parent_id' => Common::uuid('category.id.drupal'),
			'name' => 'd-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.cp-project3'),
			'parent_id' => Common::uuid('category.id.cakephp'),
			'name' => 'cp-project3',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.others'),
			'parent_id' => Common::uuid('category.id.projects'),
			'name' => 'others',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.o-project1'),
			'parent_id' => Common::uuid('category.id.others'),
			'name' => 'o-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.d-project2'),
			'parent_id' => Common::uuid('category.id.drupal'),
			'name' => 'd-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.o-project2'),
			'parent_id' => Common::uuid('category.id.others'),
			'name' => 'o-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.private'),
			'parent_id' => Common::uuid('category.id.bolt'),
			'name' => 'private',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-05-06 03:34:39',
			'modified' => '2014-05-06 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.pv-jean_bartik'),
			'parent_id' => null,
			'name' => 'pv-jean_bartik',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-05-06 03:35:39',
			'modified' => '2014-05-06 03:35:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));

		// Sand box unit test
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.utest'),
			'parent_id' => null,
			'name' => 'utest',
			'category_type_id' => null,
			'deleted' => 0
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.utest1'),
			'parent_id' => Common::uuid('category.id.utest'),
			'name' => 'utest1',
			'category_type_id' => null,
			'deleted' => 0
		));
		$c[] = array('Category'=>array(
			'id' => Common::uuid('category.id.utest2'),
			'parent_id' => Common::uuid('category.id.utest'),
			'name' => 'utest2',
			'category_type_id' => null,
			'deleted' => 0
		));
		
		return $c;
	}
}
