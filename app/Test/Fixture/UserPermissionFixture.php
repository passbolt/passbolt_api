<?php
/**
 * UserPermission Fixture
 *
 * Set of users to test the permissions. reflects the scenario that is described in the google document 
 * https://docs.google.com/a/passbolt.com/document/d/19nm_VS0aBr_8dqr0YcG-l5vthf6HBH4YX20Fhmt5hnw/edit
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.UserPermissionFixture
 * @since       version 2.12.11
 */
App::uses('User', 'Model');

class UserPermissionFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'User';

	public function init() {
		$this->records = array(
			array('id' => '50bda571-1e18-4695-aa20-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'remy.bertot@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-1e30-4342-b358-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'b-user1@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-1f90-4078-acc3-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'b-user2@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-2cb0-4224-b1de-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'a-user2@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-2eb0-47e0-80cf-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'a-user1@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-3534-4e98-864a-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'jean-rene@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-5874-4f95-9427-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'kevin.muller@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-7f4c-42d1-ab2c-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'adminsys@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-905c-427a-ba25-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'ramesh.kumar@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-9c78-4106-b089-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'cedric.alfonsi@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-c4f0-4fa5-b4cc-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'aurelie.gerhards@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-db84-4a61-9b0e-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'dark.vador@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-f0b8-4c5f-aa54-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'ismail.guennouni@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-fdf4-4240-a0a4-a7c58cebc04d','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'myriam.djerouni@test.com','password' => '$2a$04$79b58df64165554f7ec93u7fp3n2umaTuK6lU3.3kiAQ6zmDHO1/a','active' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c','username' => 'anonymous@passbolt.com','password' => '$2a$04$79b58df64165554f7ec93ujxNGKAH26vrXfyhnrDdzoKPamfKtyLO','active' => '1','created' => '2012-07-04 13:39:25','modified' => '2012-07-04 13:39:25','created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c','modified_by' => ''),
			array('id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e','role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c','username' => 'test@passbolt.com','password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda','active' => '1','created' => '2012-07-04 13:39:25','modified' => '2012-07-04 13:39:25','created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}