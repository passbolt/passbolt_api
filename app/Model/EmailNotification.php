<?php

/**
 * EmailNotification Model
 *
 * @copyright         Copyright 2012, Passbolt.com
 * @license             http://www.passbolt.com/license
 * @package             app.Model.EmailNotification
 * @since            version 2.12.7
 */
class EmailNotification extends AppModel {

	// Doesn't use a table.
	public $useTable = false;

/**
 * Put a notification email in the queue.
 *
 * @param string $to
 *   the email to send the notification to
 * @param string $subject
 *   the subject
 * @param array $data
 *   variables to pass to the template
 * @param string $template
 *   name of the template to use
 */
	public function send($to, $subject, $data, $template) {
		$options = [
			'template' => $template,
			'subject' => $subject,
			'format' => 'html',
			'config' => 'default'
		];
		ClassRegistry::init('EmailQueue.EmailQueue')->enqueue($to, $data, $options);
	}
}
