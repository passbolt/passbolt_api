<?php
App::uses('AppModel', 'Model');

/**
 * EmailQueue model
 *
 */
class EmailQueue extends AppModel {

/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'EmailQueue';

/**
 * Database table used
 *
 * @var string
 * @access public
 */
	public $useTable = 'email_queue';

/**
 * Stores a new email message in the queue
 *
 * @param mixed $to email or array of emails as recipients
 * @param array $data associative array of variables to be passed to the email template
 * @param array $options list of options for email sending. Possible keys:
 *
 * - subject : Email's subject
 * - send_at : date time sting representing the time this email should be sent at (in UTC)
 * - template :  the name of the element to use as template for the email message
 * - layout : the name of the layout to be used to wrap email message
 * - format: Type of template to use (html, text or both)
 * - config : the name of the email config to be used for sending
 *
 * @return void
 */
	public function enqueue($to, array $data, $options = array()) {
		$defaults = array(
			'subject' => '',
			'send_at' => gmdate('Y-m-d H:i:s'),
			'template' => 'default',
			'layout' => 'default',
			'format' => 'both',
			'headers' => array(),
			'template_vars' => $data,
			'config' => 'default'
		);

		$email = $options + $defaults;
		if (!is_array($to)) {
			$to = array($to);
		}

		foreach ($to as $t) {
			$email['to'] = $t;
			$this->create();
			$this->save($email);
		}
	}

/**
 * Returns a list of queued emails that needs to be sent
 *
 * @param integer $size, number of unset emails to return
 * @return array list of unsent emails
 * @access public
 */
	public function getBatch($size = 10) {
		$this->getDataSource()->begin();

		$emails = $this->find('all', array(
			'limit' => $size,
			'conditions' => array(
				'EmailQueue.sent' => false,
				'EmailQueue.send_tries <=' => 3,
				'EmailQueue.send_at <=' => gmdate('Y-m-d H:i:s'),
				'EmailQueue.locked' => false
			),
			'order' => array('EmailQueue.created' => 'ASC')
		));

		if (!empty($emails)) {
			$ids =  Set::extract('{n}.EmailQueue.id', $emails);
			$this->updateAll(array('locked' => true), array('EmailQueue.id' => $ids));
		}

		$this->getDataSource()->commit();
		return $emails;
	}

/**
 * Releases locks for all emails in $ids
 *
 * @return void
 **/
	public function releaseLocks($ids) {
		$this->updateAll(array('locked' => false), array('EmailQueue.id' => $ids));
	}

/**
 * Releases locks for all emails in queue, useful for recovering from crashes
 *
 * @return void
 **/
	public function clearLocks() {
		$this->updateAll(array('locked' => false));
	}

/**
 * Marks an email from the queue as sent
 *
 * @param string $id, queued email id
 * @return boolean
 * @access public
 */
	public function success($id) {
		$this->id = $id;
		return $this->saveField('sent', true);
	}

/**
 * Marks an email from the queue as failed, and increments the number of tries
 *
 * @param string $id, queued email id
 * @return boolean
 * @access public
 */
	public function fail($id) {
		$this->id = $id;
		$tries = $this->field('send_tries');
		return $this->saveField('send_tries', $tries + 1);
	}

/**
 * Converts array data for template vars into a json serialized string
 *
 * @param array $options
 * @return boolean
 **/
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['template_vars'])) {
			$this->data[$this->alias]['template_vars'] = json_encode($this->data[$this->alias]['template_vars']);
		}
		if (isset($this->data[$this->alias]['headers'])) {
			$this->data[$this->alias]['headers'] = json_encode($this->data[$this->alias]['headers']);
		}

		return parent::beforeSave($options);
	}

/**
 * Converts template_vars back into a php array
 *
 * @param array $results
 * @param boolean $primary
 * @return array
 **/
	public function afterFind($results, $primary = false) {
		if (!$primary) {
			return parent::afterFind($results, $primary);
		}

		$first = current($results);
		if (!isset($first[$this->alias]['template_vars']) && !isset($first[$this->alias]['headers'])) {
				return $results;
		}

		foreach ($results as &$r) {
			$r[$this->alias]['template_vars'] = json_decode($r[$this->alias]['template_vars'], true);
			$r[$this->alias]['headers'] = json_decode($r[$this->alias]['headers'], true);
		}

		return $results;
	}

}
