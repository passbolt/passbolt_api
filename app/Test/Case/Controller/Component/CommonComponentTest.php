<?php
/**
 * Common Component Test
 *
 * @copyright    Copyright 2015, Passbolt.com
 * @license      http://www.passbolt.com/license
 */

// Stuffs we use for the test
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');
App::uses('ComponentCollection', 'Controller');
App::uses('Common', 'Controller/Component');

// Test Class
class CommonComponentTest extends CakeTestCase {

    public function setUp($complete=true) {
        parent::setUp();
    }

    public function testUuid() {
        $uuid = Common::Uuid();
        $this->assertTrue(
            (preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $uuid) == true),
            'Common::uuid() does not return a valid uuid:' . $uuid
        );

        $uuid1 = Common::Uuid('test');
        $uuid2 = Common::Uuid('test');
        $this->assertTrue(($uuid1===$uuid2), 'UUIDs should match');
        $this->assertTrue(
            (preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $uuid1) == true),
            'Common::uuid() does not return a valid uuid:' . $uuid1
        );
    }

    public function testIsUuid() {

    }
}