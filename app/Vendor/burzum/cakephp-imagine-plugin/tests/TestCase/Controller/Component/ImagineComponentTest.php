<?php
/**
 * Copyright 2011-2015, Florian Krämer
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Copyright 2011-2015, Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Burzum\Imagine\Test\TestCase\Controller\Component;

use Cake\TestSuite\TestCase;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;

	class ImagineImagesTestController extends Controller {

	/**
	 * @var string
	 */
		public $name = 'Images';

	/**
	 * @var array
	 */
		public $uses = array('Images');

	/**
	 * @var array
	 */
		public $components = array(
			'Burzum/Imagine.Imagine'
		);

	/**
	 * Redirect url
	 * @var mixed
	 */
		public $redirectUrl = null;

	/**
	 * 
	 */
		public function beforeFilter(Event $Event) {
			parent::beforeFilter($Event);
			$this->Imagine->userModel = 'UserModel';
		}

	/**
	 * 
	 */
		public function redirect($url, $status = NULL, $exit = true) {
			$this->redirectUrl = $url;
		}
	}


/**
 * Imagine Component Test
 *
 * @package Imagine
 * @subpackage Imagine.tests.cases.components
 */
class ImagineComponentTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Burzum\Imagine.Image'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Imagine.salt', 'this-is-a-nice-salt');
		$request = new Request();
		$response = new Response();
		$this->Controller = new ImagineImagesTestController($request, $response);
		$this->Controller->Imagine->Controller = $this->Controller;
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Controller);
	}

/**
 * testGetHash method
 *
 * @return void
 */
	public function testGetHash() {
		$this->Controller->request->query = [
			'thumbnail' => 'width|200;height|150'
		];
		$hash = $this->Controller->Imagine->getHash();
		$this->assertTrue(is_string($hash));
	}

/**
 * testCheckHash method
 *
 * @return void
 */
	public function testCheckHash() {
		$this->Controller->request->query = [
			'thumbnail' => 'width|200;height|150',
			'hash' => '69aa9f46cdc5a200dc7539fc10eec00f2ba89023'
		];
		$this->Controller->Imagine->checkHash();
	}

/**
 * @expectedException Cake\Network\Exception\NotFoundException
 */
	public function testInvalidHash() {
		$this->Controller->request->query = [
			'thumbnail' => 'width|200;height|150',
			'hash' => 'wrong-hash-value'
		];
		$this->Controller->Imagine->checkHash();
	}

/**
 * @expectedException Cake\Network\Exception\NotFoundException
 */
	public function testMissingHash() {
		$this->Controller->request->query = [
			'thumbnail' => 'width|200;height|150'
		];
		$this->Controller->Imagine->checkHash();
	}

/**
 * testCheckHash method
 *
 * @return void
 */
	public function testUnpackParams() {
		$this->assertEquals($this->Controller->Imagine->operations, []);
		$this->Controller->request->query['thumbnail'] = 'width|200;height|150';
		$this->Controller->Imagine->unpackParams();
		$this->assertEquals($this->Controller->Imagine->operations, [
				'thumbnail' => [
					'width' => 200,
					'height' => 150
				]
			]
		);
	}

}
