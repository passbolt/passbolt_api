<?php
App::uses('EmailQueue', 'EmailQueue.Model');

/**
 * EmailQueue Test Case
 *
 */
class EmailQueueTest extends CakeTestCase {

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
		$this->EmailQueue = ClassRegistry::init('EmailQueue.EmailQueue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailQueue);
		parent::tearDown();
	}

/**
 * testEnqueue method
 *
 * @return void
 */
	public function testEnqueue() {
		$count = $this->EmailQueue->find('count');
		$this->EmailQueue->enqueue('someone@domain.com', array('a' => 'variable', 'some' => 'thing'), array(
			'subject' => 'Hey!',
			'headers' => array('X-FOO' => 'bar', 'X-BAZ' => 'thing')
		));
		$id = $this->EmailQueue->id;
		$this->assertEquals(++$count, $this->EmailQueue->find('count'));

		$result = $this->EmailQueue->read(null, $id);
		$expected = array(
			'to' => 'someone@domain.com',
			'subject' => 'Hey!',
			'template' => 'default',
			'layout' => 'default',
			'format' => 'both',
			'template_vars' => array('a' => 'variable', 'some' => 'thing'),
			'sent' => false,
			'locked' => false,
			'send_tries' => '0',
			'config' => 'default',
			'headers' => array('X-FOO' => 'bar', 'X-BAZ' => 'thing')
		);
		$result = $result['EmailQueue'];
		$sendAt = new DateTime($result['send_at']);
		unset($result['id'], $result['created'], $result['modified'], $result['send_at']);
		$this->assertEquals($expected, $result);
		$this->assertEquals(gmdate('Y-m-d H'), $sendAt->format('Y-m-d H'));

		$date = gmdate('Y-m-d H:i:s');
		$this->EmailQueue->enqueue(array('a@example.com', 'b@example.com'), array('a' => 'b'), array('send_at' => $date, 'subject' => 'Hey!'));
		$this->assertEquals($count + 2, $this->EmailQueue->find('count'));

		$email = $this->EmailQueue->find('first', array(
			'conditions' => array('to' => 'a@example.com')
		));
		$this->assertEquals(array('a' => 'b'), $email['EmailQueue']['template_vars']);
		$this->assertEquals($date, $email['EmailQueue']['send_at']);

		$email = $this->EmailQueue->find('first', array(
			'conditions' => array('to' => 'b@example.com')
		));
		$this->assertEquals(array('a' => 'b'), $email['EmailQueue']['template_vars']);
		$this->assertEquals($date, $email['EmailQueue']['send_at']);

		$this->EmailQueue->enqueue('c@example.com', array('a' => 'c'), array('subject' => 'Hey', 'send_at' => $date, 'config' => 'other', 'template' => 'custom', 'layout' => 'email'));
		$email = $this->EmailQueue->read();
		$this->assertEquals(array('a' => 'c'), $email['EmailQueue']['template_vars']);
		$this->assertEquals($date, $email['EmailQueue']['send_at']);
		$this->assertEquals('other', $email['EmailQueue']['config']);
		$this->assertEquals('custom', $email['EmailQueue']['template']);
		$this->assertEquals('email', $email['EmailQueue']['layout']);
	}

/**
 * testGetBatch method
 *
 * @return void
 */
	public function testGetBatch() {
		$batch = $this->EmailQueue->getBatch();
		$this->assertEquals(array('email-1', 'email-2', 'email-3'), Set::extract('{n}.EmailQueue.id', $batch));

		//At this point previous batch should be locked and next call should return an empty set
		$batch = $this->EmailQueue->getBatch();
		$this->assertEmpty($batch);

		//Let's change send_at date for email-6 to get it on a batch
		$this->EmailQueue->save(array('id' => 'email-6', 'send_at' => '2011-01-01 00:00'));
		$batch = $this->EmailQueue->getBatch();
		$this->assertEquals(array('email-6'), Set::extract('{n}.EmailQueue.id', $batch));
	}

/**
 * testReleaseLocks method
 *
 * @return void
 */
	public function testReleaseLocks() {
		$batch = $this->EmailQueue->getBatch();
		$this->assertNotEmpty($batch);
		$this->assertEmpty($this->EmailQueue->getBatch());
		$this->EmailQueue->releaseLocks(Set::extract('{n}.EmailQueue.id', $batch));
		$this->assertEquals($batch, $this->EmailQueue->getBatch());
	}

/**
 * testClearLocks method
 *
 * @return void
 */
	public function testClearLocks() {
		$batch = $this->EmailQueue->getBatch();
		$this->assertNotEmpty($batch);
		$this->assertEmpty($this->EmailQueue->getBatch());
		$this->EmailQueue->clearLocks();
		$batch = $this->EmailQueue->getBatch();
		$this->assertEquals(array('email-1', 'email-2', 'email-3', 'email-5'), Set::extract('{n}.EmailQueue.id', $batch));
	}

/**
 * testSuccess method
 *
 * @return void
 */
	public function testSuccess() {
		$this->EmailQueue->success('email-1');
		$this->assertEquals(1, $this->EmailQueue->field('sent', array('id' => 'email-1')));
	}

/**
 * testFail method
 *
 * @return void
 */
	public function testFail() {
		$this->EmailQueue->fail('email-1');
		$this->assertEquals(2, $this->EmailQueue->field('send_tries', array('id' => 'email-1')));

		$this->EmailQueue->fail('email-1');
		$this->assertEquals(3, $this->EmailQueue->field('send_tries', array('id' => 'email-1')));
	}

}
