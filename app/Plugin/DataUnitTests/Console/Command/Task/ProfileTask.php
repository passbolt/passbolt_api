<?php
/**
 * Insert Comment Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataExtras.Console.Command.Task.ProfileTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Profile', 'Model');

class ProfileTask extends ModelTask {

	public $model = 'Profile';

	protected function getData() {
		// Admin
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.admin'),
			'user_id' => Common::uuid('user.id.admin'),
			'gender' => 'm',
			'date_of_birth' => '1980-12-13',
			'title' => 'Mr',
			'first_name' => 'Admin',
			'last_name' => 'User'
		));
		// Anonymous user / default for non logged-in user
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.anonymous'),
			'user_id' => Common::uuid('user.id.anonymous'),
			'gender' => 'm',
			'date_of_birth' => '1980-12-10',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User'
		));

		// One user per role
//		$c[] = array('Profile' => array(
//			'id' => Common::uuid('profile.id.guest'),
//			'user_id' => Common::uuid('user.id.guest'),
//			'gender' => 'm',
//			'date_of_birth' => '1980-12-11',
//			'title' => 'Mr',
//			'first_name' => 'Guest',
//			'last_name' => 'User'
//		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.user'),
			'user_id' => Common::uuid('user.id.user'),
			'gender' => 'm',
			'date_of_birth' => '1980-12-12',
			'title' => 'Mr',
			'first_name' => 'Default',
			'last_name' => 'User'
		));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.admin'),
            'user_id' => Common::uuid('user.id.admin'),
            'gender' => 'm',
            'date_of_birth' => '1980-12-13',
            'title' => 'Mr',
            'first_name' => 'Admin',
            'last_name' => 'User'
        ));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.root'),
			'user_id' => Common::uuid('user.id.root'),
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Root',
			'last_name' => 'User'
		));

		// famous scientists
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.ada'),
			'user_id' => Common::uuid('user.id.ada'),
			'gender' => 'f',
			'date_of_birth' => '1815-12-10',
			'title' => 'Ms',
			'first_name' => 'Ada',
			'last_name' => 'Lovelace'
		));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.betty'),
            'user_id' => Common::uuid('user.id.betty'),
            'gender' => 'f',
            'date_of_birth' => '1917-03-07',
            'title' => 'Ms',
            'first_name' => 'Betty',
            'last_name' => 'Holberton'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.carol'),
            'user_id' => Common::uuid('user.id.carol'),
            'gender' => 'f',
            'date_of_birth' => '1955-01-01',
            'title' => 'Ms',
            'first_name' => 'Carol',
            'last_name' => 'Shaw'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.dame'),
            'user_id' => Common::uuid('user.id.dame'),
            'gender' => 'f',
            'date_of_birth' => '1933-09-16',
            'title' => 'Ms',
            'first_name' => 'Dame Steve',
            'last_name' => 'Shirley'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.edith'),
            'user_id' => Common::uuid('user.id.edith'),
            'gender' => 'f',
            'date_of_birth' => '1983-10-29',
            'title' => 'Ms',
            'first_name' => 'Edith',
            'last_name' => 'Clarke'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.frances'),
            'user_id' => Common::uuid('user.id.frances'),
            'gender' => 'f',
            'date_of_birth' => '1932-08-04',
            'title' => 'Ms',
            'first_name' => 'Frances',
            'last_name' => 'Allen'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.grace'),
            'user_id' => Common::uuid('user.id.grace'),
            'gender' => 'f',
            'date_of_birth' => '1906-12-09',
            'title' => 'Ms',
            'first_name' => 'Grace',
            'last_name' => 'Hopper'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.hedy'),
            'user_id' => Common::uuid('user.id.hedy'),
            'gender' => 'f',
            'date_of_birth' => '1980-12-14',
            'title' => 'Ms',
            'first_name' => 'Hedy',
            'last_name' => 'Lamarr'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.irene'),
            'user_id' => Common::uuid('user.id.irene'),
            'gender' => 'f',
            'date_of_birth' => '1980-12-14',
            'title' => 'Ms',
            'first_name' => 'Irene',
            'last_name' => 'Greif'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.jean'),
            'user_id' => Common::uuid('user.id.jean'),
            'gender' => 'f',
            'date_of_birth' => '1924-12-27',
            'title' => 'Ms',
            'first_name' => 'Jean',
            'last_name' => 'Bartik'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.kathleen'),
            'user_id' => Common::uuid('user.id.kathleen'),
            'gender' => 'f',
            'date_of_birth' => '1921-02-12',
            'title' => 'Ms',
            'first_name' => 'Kathleen',
            'last_name' => 'Antonelli'
        ));
        $c[] = array('Profile' => array(
            'id' => Common::uuid('profile.id.lynne'),
            'user_id' => Common::uuid('user.id.lynne'),
            'gender' => 'f',
            'date_of_birth' => '1961-06-30',
            'title' => 'Ms',
            'first_name' => 'Lynne',
            'last_name' => 'Jolitz'
        ));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.marlyn'),
			'user_id' => Common::uuid('user.id.marlyn'),
			'gender' => 'f',
			'date_of_birth' => '1922-01-01',
			'title' => 'Ms',
			'first_name' => 'Marlyn',
			'last_name' => 'Wescoff'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.nancy'),
			'user_id' => Common::uuid('user.id.nancy'),
			'gender' => 'f',
			'date_of_birth' => '1986-07-14',
			'title' => 'Ms',
			'first_name' => 'Nancy',
			'last_name' => 'Leveson'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.orna'),
			'user_id' => Common::uuid('user.id.orna'),
			'gender' => 'f',
			'date_of_birth' => '1949-12-19',
			'title' => 'Ms',
			'first_name' => 'Orna',
			'last_name' => 'Berry'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.ping'),
			'user_id' => Common::uuid('user.id.ping'),
			'gender' => 'f',
			'date_of_birth' => '1958-12-01',
			'title' => 'Ms',
			'first_name' => 'Ping',
			'last_name' => 'Fu'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.ruth'),
			'user_id' => Common::uuid('user.id.ruth'),
			'gender' => 'f',
			'date_of_birth' => '1924-01-01',
			'title' => 'Ms',
			'first_name' => 'Ruth',
			'last_name' => 'Teitelbaum'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.sofia'),
			'user_id' => Common::uuid('user.id.sofia'),
			'gender' => 'f',
			'date_of_birth' => '1850-02-10',
			'title' => 'Ms',
			'first_name' => 'Sofia',
			'last_name' => 'Kovalevskaya'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.thelma'),
			'user_id' => Common::uuid('user.id.thelma'),
			'gender' => 'f',
			'date_of_birth' => '1924-02-21',
			'title' => 'Ms',
			'first_name' => 'Thelma',
			'last_name' => 'Estrin'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.ursula'),
			'user_id' => Common::uuid('user.id.ursula'),
			'gender' => 'f',
			'date_of_birth' => '1924-02-21',
			'title' => 'Ms',
			'first_name' => 'Ursula',
			'last_name' => 'Martin'
		));
		$c[] = array('Profile' => array(
			'id' => Common::uuid('profile.id.wang'),
			'user_id' => Common::uuid('user.id.wang'),
			'gender' => 'f',
			'date_of_birth' => '1966-01-01',
			'title' => 'Ms',
			'first_name' => 'Wang',
			'last_name' => 'Xiaoyun'
		));
		return $c;
	}
}
