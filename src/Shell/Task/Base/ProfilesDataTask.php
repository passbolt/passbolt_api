<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace PassboltTestData\Shell\Task\Base;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class ProfilesDataTask extends DataTask
{
    public $entityName = 'Profiles';

    /**
     * Get the profile data
     *
     * @return array
     */
    public function getData()
    {
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.admin'),
            'user_id' => UuidFactory::uuid('user.id.admin'),
            'gender' => 'm',
            'date_of_birth' => '1970-01-01',
            'title' => 'Mr',
            'first_name' => 'Admin',
            'last_name' => 'User'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.anonymous'),
            'user_id' => UuidFactory::uuid('user.id.anonymous'),
            'gender' => 'm',
            'date_of_birth' => '1980-12-10',
            'title' => 'Mr',
            'first_name' => 'Anonymous',
            'last_name' => 'User'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.ada'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'gender' => 'f',
            'date_of_birth' => '1815-12-10',
            'title' => 'Ms',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.betty'),
            'user_id' => UuidFactory::uuid('user.id.betty'),
            'gender' => 'f',
            'date_of_birth' => '1917-03-07',
            'title' => 'Ms',
            'first_name' => 'Betty',
            'last_name' => 'Holberton'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.carol'),
            'user_id' => UuidFactory::uuid('user.id.carol'),
            'gender' => 'f',
            'date_of_birth' => '1955-01-01',
            'title' => 'Ms',
            'first_name' => 'Carol',
            'last_name' => 'Shaw'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.dame'),
            'user_id' => UuidFactory::uuid('user.id.dame'),
            'gender' => 'f',
            'date_of_birth' => '1933-09-16',
            'title' => 'Ms',
            'first_name' => 'Dame Steve',
            'last_name' => 'Shirley'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.edith'),
            'user_id' => UuidFactory::uuid('user.id.edith'),
            'gender' => 'f',
            'date_of_birth' => '1983-10-29',
            'title' => 'Ms',
            'first_name' => 'Edith',
            'last_name' => 'Clarke'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.frances'),
            'user_id' => UuidFactory::uuid('user.id.frances'),
            'gender' => 'f',
            'date_of_birth' => '1932-08-04',
            'title' => 'Ms',
            'first_name' => 'Frances',
            'last_name' => 'Allen'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.grace'),
            'user_id' => UuidFactory::uuid('user.id.grace'),
            'gender' => 'f',
            'date_of_birth' => '1906-12-09',
            'title' => 'Ms',
            'first_name' => 'Grace',
            'last_name' => 'Hopper'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.hedy'),
            'user_id' => UuidFactory::uuid('user.id.hedy'),
            'gender' => 'f',
            'date_of_birth' => '1980-12-14',
            'title' => 'Ms',
            'first_name' => 'Hedy',
            'last_name' => 'Lamarr'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.irene'),
            'user_id' => UuidFactory::uuid('user.id.irene'),
            'gender' => 'f',
            'date_of_birth' => '1980-12-14',
            'title' => 'Ms',
            'first_name' => 'Irene',
            'last_name' => 'Greif'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.jean'),
            'user_id' => UuidFactory::uuid('user.id.jean'),
            'gender' => 'f',
            'date_of_birth' => '1924-12-27',
            'title' => 'Ms',
            'first_name' => 'Jean',
            'last_name' => 'Bartik'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.joan'),
            'user_id' => UuidFactory::uuid('user.id.joan'),
            'gender' => 'f',
            'date_of_birth' => '1917-06-29',
            'title' => 'Ms',
            'first_name' => 'Joan',
            'last_name' => 'Clarke'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.kathleen'),
            'user_id' => UuidFactory::uuid('user.id.kathleen'),
            'gender' => 'f',
            'date_of_birth' => '1921-02-12',
            'title' => 'Ms',
            'first_name' => 'Kathleen',
            'last_name' => 'Antonelli'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.lynne'),
            'user_id' => UuidFactory::uuid('user.id.lynne'),
            'gender' => 'f',
            'date_of_birth' => '1961-06-30',
            'title' => 'Ms',
            'first_name' => 'Lynne',
            'last_name' => 'Jolitz'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.margaret'),
            'user_id' => UuidFactory::uuid('user.id.margaret'),
            'gender' => 'f',
            'date_of_birth' => '1936-08-17',
            'title' => 'Ms',
            'first_name' => 'Margaret',
            'last_name' => 'Hamilton'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.marlyn'),
            'user_id' => UuidFactory::uuid('user.id.marlyn'),
            'gender' => 'f',
            'date_of_birth' => '1922-01-01',
            'title' => 'Ms',
            'first_name' => 'Marlyn',
            'last_name' => 'Wescoff'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.nancy'),
            'user_id' => UuidFactory::uuid('user.id.nancy'),
            'gender' => 'f',
            'date_of_birth' => '1986-07-14',
            'title' => 'Ms',
            'first_name' => 'Nancy',
            'last_name' => 'Leveson'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.orna'),
            'user_id' => UuidFactory::uuid('user.id.orna'),
            'gender' => 'f',
            'date_of_birth' => '1949-12-19',
            'title' => 'Ms',
            'first_name' => 'Orna',
            'last_name' => 'Berry'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.ping'),
            'user_id' => UuidFactory::uuid('user.id.ping'),
            'gender' => 'f',
            'date_of_birth' => '1958-12-01',
            'title' => 'Ms',
            'first_name' => 'Ping',
            'last_name' => 'Fu'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.ruth'),
            'user_id' => UuidFactory::uuid('user.id.ruth'),
            'gender' => 'f',
            'date_of_birth' => '1924-01-01',
            'title' => 'Ms',
            'first_name' => 'Ruth',
            'last_name' => 'Teitelbaum'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.sofia'),
            'user_id' => UuidFactory::uuid('user.id.sofia'),
            'gender' => 'f',
            'date_of_birth' => '1850-02-10',
            'title' => 'Ms',
            'first_name' => 'Sofia',
            'last_name' => 'Kovalevskaya'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.thelma'),
            'user_id' => UuidFactory::uuid('user.id.thelma'),
            'gender' => 'f',
            'date_of_birth' => '1924-02-21',
            'title' => 'Ms',
            'first_name' => 'Thelma',
            'last_name' => 'Estrin'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.ursula'),
            'user_id' => UuidFactory::uuid('user.id.ursula'),
            'gender' => 'f',
            'date_of_birth' => '1924-02-21',
            'title' => 'Ms',
            'first_name' => 'Ursula',
            'last_name' => 'Martin'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.wang'),
            'user_id' => UuidFactory::uuid('user.id.wang'),
            'gender' => 'f',
            'date_of_birth' => '1966-01-01',
            'title' => 'Ms',
            'first_name' => 'Wang',
            'last_name' => 'Xiaoyun'
        ];
        $profiles[] = [
            'id' => UuidFactory::uuid('profile.id.yvonne'),
            'user_id' => UuidFactory::uuid('user.id.yvonne'),
            'gender' => 'f',
            'date_of_birth' => '1923-12-29',
            'title' => 'Ms',
            'first_name' => 'Yvonne',
            'last_name' => 'Choquet-Bruhat'
        ];

        return $profiles;
    }
}
