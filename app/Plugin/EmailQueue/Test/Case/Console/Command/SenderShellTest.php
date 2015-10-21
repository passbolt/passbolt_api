<?php

App::uses('SenderShell', 'EmailQueue.Console/Command');
App::uses('CakeEmail', 'Network/Email');

/**
 * SenderShell Test Case
 *
 */
class SenderShellTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.email_queue.email_queue'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$this->in = $this->getMock('ConsoleInput', array(), array(), '', false);

		$this->Sender = $this->getMock(
			'SenderShell',
			array('in', 'out', 'err', '_stop', '_newEmail'),
			array($this->out, $this->out, $this->in)
		);
		$this->Sender->params = array(
			'limit' => 10,
			'template' => 'default',
			'layout' => 'default',
			'config' => 'default'
		);
	}

	public function testMainAllFail() {
		$email = $this->getMock('CakeEmail', array('to', 'template', 'viewVars', 'send', 'subject', 'emailFormat', 'addHeaders'));

		$this->Sender->expects($this->exactly(3))->method('_newEmail')->with('default')->will($this->returnValue($email));
		$email->expects($this->exactly(3))->method('send')->will($this->returnValue(false));
		$email->expects($this->exactly(3))->method('to')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('subject')->with('Free dealz')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('emailFormat')->with('both')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('addHeaders')->with(['foo' => 'bar'])->will($this->returnSelf());

		$email->expects($this->exactly(3))->method('template')
			->with('default', 'default')
			->will($this->returnSelf());

		$email->expects($this->exactly(3))->method('viewVars')
			->with(array('a' => 1, 'b' => 2))
			->will($this->returnSelf());
		$this->Sender->main();

		$emails = ClassRegistry::init('EmailQueue.EmailQueue')->find('all', array(
			'conditions' => array('id' => array('email-1', 'email-2', 'email-3')
		)));
		$this->assertEquals(2, $emails[0]['EmailQueue']['send_tries']);
		$this->assertEquals(3, $emails[1]['EmailQueue']['send_tries']);
		$this->assertEquals(4, $emails[2]['EmailQueue']['send_tries']);

		$this->assertFalse($emails[0]['EmailQueue']['locked']);
		$this->assertFalse($emails[1]['EmailQueue']['locked']);
		$this->assertFalse($emails[2]['EmailQueue']['locked']);

		$this->assertFalse($emails[0]['EmailQueue']['sent']);
		$this->assertFalse($emails[1]['EmailQueue']['sent']);
		$this->assertFalse($emails[2]['EmailQueue']['sent']);
	}

	public function testMainAllWin() {
		$email = $this->getMock('CakeEmail', array('to', 'template', 'viewVars', 'send', 'subject', 'emailFormat'));

		$this->Sender->params['template'] = 'other';
		$this->Sender->params['layout'] = 'custom';
		$this->Sender->params['config'] = 'something';

		$this->Sender->expects($this->exactly(3))->method('_newEmail')
			->with('something')
			->will($this->returnValue($email));

		$email->expects($this->exactly(3))->method('send')->will($this->returnValue(true));
		$email->expects($this->exactly(3))->method('to')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('subject')->with('Free dealz')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('emailFormat')->with('both')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('template')
			->with('other', 'custom')
			->will($this->returnSelf());

		$email->expects($this->exactly(3))->method('viewVars')
			->with(array('a' => 1, 'b' => 2))
			->will($this->returnSelf());
		$this->Sender->main();

		$emails = ClassRegistry::init('EmailQueue.EmailQueue')->find('all', array(
			'conditions' => array('id' => array('email-1', 'email-2', 'email-3')
		)));

		$this->assertEquals(1, $emails[0]['EmailQueue']['send_tries']);
		$this->assertEquals(2, $emails[1]['EmailQueue']['send_tries']);
		$this->assertEquals(3, $emails[2]['EmailQueue']['send_tries']);

		$this->assertFalse($emails[0]['EmailQueue']['locked']);
		$this->assertFalse($emails[1]['EmailQueue']['locked']);
		$this->assertFalse($emails[2]['EmailQueue']['locked']);

		$this->assertTrue($emails[0]['EmailQueue']['sent']);
		$this->assertTrue($emails[1]['EmailQueue']['sent']);
		$this->assertTrue($emails[2]['EmailQueue']['sent']);
	}

	public function testMainAllFailWithException() {
		$email = $this->getMock('CakeEmail', array('to', 'template', 'viewVars', 'send', 'subject', 'emailFormat'));

		$this->Sender->expects($this->exactly(3))->method('_newEmail')->with('default')->will($this->returnValue($email));

		$email->expects($this->exactly(3))->method('send')->will($this->throwException(new SocketException('fail')));

		$email->expects($this->exactly(3))->method('to')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('subject')->with('Free dealz')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('emailFormat')->with('both')->will($this->returnSelf());
		$email->expects($this->exactly(3))->method('template')
			->with('default', 'default')
			->will($this->returnSelf());

		$email->expects($this->exactly(3))->method('viewVars')
			->with(array('a' => 1, 'b' => 2))
			->will($this->returnSelf());
		$this->Sender->main();

		$emails = ClassRegistry::init('EmailQueue.EmailQueue')->find('all', array(
			'conditions' => array('id' => array('email-1', 'email-2', 'email-3')
		)));
		$this->assertEquals(2, $emails[0]['EmailQueue']['send_tries']);
		$this->assertEquals(3, $emails[1]['EmailQueue']['send_tries']);
		$this->assertEquals(4, $emails[2]['EmailQueue']['send_tries']);

		$this->assertFalse($emails[0]['EmailQueue']['locked']);
		$this->assertFalse($emails[1]['EmailQueue']['locked']);
		$this->assertFalse($emails[2]['EmailQueue']['locked']);

		$this->assertFalse($emails[0]['EmailQueue']['sent']);
		$this->assertFalse($emails[1]['EmailQueue']['sent']);
		$this->assertFalse($emails[2]['EmailQueue']['sent']);
	}

	public function testClearLocks() {
		ClassRegistry::init('EmailQueue.EmailQueue')->getBatch();
		$this->Sender->clearLocks();
		$this->assertEmpty(ClassRegistry::init('EmailQueue.EmailQueue')->findByLocked(true));
	}

}
