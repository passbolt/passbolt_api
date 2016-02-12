<?php
/**
 * EmailNotification Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
		$options = array(
			'template' => $template,
			'subject' => $subject,
			'format' => 'html',
			'config' => 'default'
		);
		ClassRegistry::init('EmailQueue.EmailQueue')->enqueue($to, $data, $options);
	}
}
