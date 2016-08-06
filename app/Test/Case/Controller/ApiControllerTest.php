<?php
/**
 * Validation Rules Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AppController', 'Controller');
App::uses('ApiController', 'Controller');

class ApiControllerTest extends ControllerTestCase
{

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
		$this->assertNotEmpty($result['swagger']);
		$this->assertNotEmpty($result['info']);
		$this->assertNotEmpty($result['host']);
		$this->assertNotEmpty($result['basePath']);
		$this->assertNotEmpty($result['schemes']);
		$this->assertNotEmpty($result['paths']);
		$this->assertNotEmpty($result['definitions']);
		$this->assertNotEmpty($result['externalDocs']);
	}
}

