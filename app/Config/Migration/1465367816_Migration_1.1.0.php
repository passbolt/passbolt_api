<?php
/**
 * v1.1.0 Migration script
 * see. https://www.passbolt.com/release/notes#v1.1.0
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class Migration_1_1_0 extends CakeMigration {

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = [
		'up' => [
			'drop_field' => [
				'users' => [
					'indexes' => [
						'username'
					]
				],
				'roles' => [
					'created_by',
					'modified_by'
				],
				'profiles' => [
					'created_by',
					'modified_by',
					'avatar'
				]
			],
			'alter_field' => [
				'controller_logs' => [
					'scope' => [
						'null' => true
					]
				],
				'email_queue' => [
					'from_name' => [
						'null' => true
					],
					'from_email' => [
						'null' => true
					],
					'sent' => [
						'default' => 0
					],
					'send_tries' => [
						'default' => 0
					]
				],
				'gpgkeys' => [
					'key' => [
						'type' => 'text',
						'null' => false,
						'default' => null
					],
				],
				'file_storage' => [
					'filename' => [
						'null' => true
					],
					'mime_type' => [
						'size' => 128
					],
					'model' => [
						'size' => 128
					],
					'path' => [
						'null' => true
					]
				],
				'profiles' => [
					'title' => [
						'null' => true
					],
					'timezone' => [
						'null' => true
					],
					'locale' => [
						'null' => true
					]
				]
			],
			'create_field' => [
				'gpgkeys' => [
					'indexes' => [
						'fingerprint' => [
							'column' => 'fingerprint'
						]
					]
				]
			]
		]
	];

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down]
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down]
 * @return bool Should process continue
 */
	public function after($direction) {
		// Make sure that Gpg uid is sanitized in the db.
		$Gpgkey = Common::getModel('Gpgkey');
		$gpgkeys = $Gpgkey->find('all');

		foreach ($gpgkeys as $gpgkey) {
			// Encode in case of up migration
			// Decode in cade of down migration.
			$sanitizedUid = $direction == 'up' ?
				htmlentities($gpgkey['Gpgkey']['uid']) :
				html_entity_decode($gpgkey['Gpgkey']['uid']);

			// Save key with new data.
			$Gpgkey->id = $gpgkey['Gpgkey']['id'];
			$Gpgkey->saveField('uid', $sanitizedUid);
		}

		return true;
	}
}
