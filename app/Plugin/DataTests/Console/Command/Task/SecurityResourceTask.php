<?php
/**
 * Insert Security Resource Task
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Resource', 'Model');
App::uses('User', 'Model');

class SecurityResourceTask extends ModelTask {

	public $model = 'Resource';

	/**
	 * Execute the task
	 * Overrides ModelTask by setting the current user from created_by for permissions.created_by to match
	 *
	 * @return void
	 */
	public function execute() {
		$User = $this->_getModel('User');
		$Model = $this->_getModel($this->model);
		$this->beforeInsert($Model);
		$data = $this->getData();

		$i = 0;
		// Insert regular data
		foreach ($data as $item) {
			// the 'owner' entry for permission.created_by will matching the resource.created_by
			$user = $User->find('first', ['conditions' => ['User.id' => $item['Resource']['created_by']]]);
			User::setActive($user);
			try {
				$this->tryInsertItem($item, $Model);
			} catch(Exception $e) {
				$i--;
				$this->out('Security test data not inserted in ' . $Model->name . ' ' . $item['Resource']['username'] . ' ' . $item['Resource']['name']);
			}
			$i++;
		}

		$this->out('Data for model ' . $this->model . ' inserted (' . $i . ')');

	}

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData() {
		// URL XSS Tests
		$r = [];
		$xss = [
			'xss JavaScript directive quote semicolon' => "javascript:alert('xss1');",
			'xss JavaScript directive quote no semicolon' => "javascript:alert('xss2')",
			'xss JavaScript directive double quote' => 'javascript:alert("XSS3")',
			'xss JavaScript directive case insensitive' => "JaVaScRiPt:alert('XSS4')",
			'xss Javascript directive HTML entities' => 'javascript:alert(&quot;XSS5&quot;)',
			'xss Javascript directive fromCharCode' => 'javascript:alert(String.fromCharCode(88,83,83))',
			'xss Decimal HTML character references' => '&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;',
			'xss Decimal HTML character references without trailing semicolons' => '&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058&#0000097&#0000108&#0000101&#0000114&#0000116&#0000040&#0000039&#0000088&#0000083&#0000083&#0000039&#0000041',
			'xss Hexadecimal HTML char references without trailing semicolons' => '&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29',
			'xss Embedded tab' => "jav        ascript:alert('XSS10');",
			'xss Embedded Encoded tab' => "jav&#x09;ascript:alert('XSS11');",
			'xss Embedded carriage return to break up XSS' => "jav&#x0D;ascript:alert('XSS12');",
			'xss Embedded newline to break up XSS' => "jav&#x0A;ascript:alert('XSS13');",
			'xss space and meta chars before the javascript' => "&#14;  javascript:alert('XSS14');",
			'xss Extraneous >' => '"' . "><script>alert('xss15')</script>",
			'xss Extraneous closing double quote' => '">' . "onclick=alert('xxs16')",
			'xss & JavaScript includes' =>	"&{alert('XSS17')}",
			'xss null breaks up javascript directive' => 'java\0script:alert("XSS18")',
		];
		$i = 1;
		foreach ($xss as $name => $x) {
			$r[] = ['Resource' => [
				'id' => Common::uuid($name),
				'name' => substr($name,0,64),
				'username' => 'test' . $i++,
				'expiry_date' => null,
				'uri' => $x,
				'description' => 'xss test',
				'deleted' => 0,
				'created_by' => Common::uuid('user.id.ada'),
				'modified_by' => Common::uuid('user.id.ada')
			]];
		}
		return $r;
	}
}
