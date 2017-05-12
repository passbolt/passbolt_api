<?php
/**
 * Validation Rules Controller Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AppController', 'Controller');
App::uses('ApiController', 'Controller');

class ApiControllerTest extends ControllerTestCase
{

	public $fixtures = array(
		'app.user',
		'app.role',
	);

/**
 * Test view endpoint access permission
 *
 * @return void
 */
	public function testViewNotDebugNotAllowed() {
		Configure::write('debug', 0);
		$this->setExpectedException('ForbiddenException');
		$this->testAction('/api/swagger.json');
	}

/**
 * Test view returns swagger file
 *
 * @return void
 */
	public function testViewSwaggerFile() {
		Configure::write('debug', 1);
		$json = $this->testAction('/api/swagger.json', array('return' => 'contents'));
		$result = json_decode($json, true);
		$this->assertNotEmpty($result['swagger'], 'Swagger data should be set');
		$this->assertNotEmpty($result['info'], 'Info data should be set');
		$this->assertNotEmpty($result['host'], 'Host should be set');
		$this->assertNotEmpty($result['basePath'], 'basePath should be set');
		$this->assertNotEmpty($result['schemes'], 'schemes should be set');
		//$this->assertNotEmpty($result['paths'], 'paths should be set');
		$this->assertNotEmpty($result['definitions'], 'definitions should be set');
		$this->assertNotEmpty($result['externalDocs'], 'externalDocs should be set');
	}
}

