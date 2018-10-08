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
namespace PassboltTestData\Shell\Task\Alt0;

use App\Model\Entity\Permission;
use App\Utility\UuidFactory;

class PermissionsDataTask extends \PassboltTestData\Shell\Task\Base\PermissionsDataTask
{
    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData()
    {
        $permissions = [];

        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.apache-ada'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.apache'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.april-ada'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.april-betty'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.bower-ada'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:06',
            'modified' => '2017-11-17 12:37:06'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.bower-betty'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:07',
            'modified' => '2017-11-17 12:37:07'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.bower-dame'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.dame'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:08',
            'modified' => '2017-11-17 12:37:08'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.bower-frances'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.frances'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:09',
            'modified' => '2017-11-17 12:37:09'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.centos-ada'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:10',
            'modified' => '2017-11-17 12:37:10'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.centos-betty'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:11',
            'modified' => '2017-11-17 12:37:11'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.canjs-ada'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:12',
            'modified' => '2017-11-17 12:37:12'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.canjs-betty'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:13',
            'modified' => '2017-11-17 12:37:13'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.canjs-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:14',
            'modified' => '2017-11-17 12:37:14'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.cakephp-accounting'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.cakephp'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.chai-board'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.chai'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.composer-creative'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.composer'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:16',
            'modified' => '2017-11-17 12:37:16'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.debian-developer'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.debian'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:17',
            'modified' => '2017-11-17 12:37:17'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.docker-ergonom'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:18',
            'modified' => '2017-11-17 12:37:18'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.docker-freelancer'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.freelancer'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:19',
            'modified' => '2017-11-17 12:37:19'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.enlightenment-accounting'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:20',
            'modified' => '2017-11-17 12:37:20'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.enlightenment-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:21',
            'modified' => '2017-11-17 12:37:21'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.fosdem-board'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:22',
            'modified' => '2017-11-17 12:37:22'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.fosdem-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:23',
            'modified' => '2017-11-17 12:37:23'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.framasoft-creative'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:24',
            'modified' => '2017-11-17 12:37:24'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.framasoft-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:25',
            'modified' => '2017-11-17 12:37:25'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.fsfe-developer'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:26',
            'modified' => '2017-11-17 12:37:26'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.fsfe-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:27',
            'modified' => '2017-11-17 12:37:27'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.ftp-ergonom'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:28',
            'modified' => '2017-11-17 12:37:28'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.ftp-betty'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:29',
            'modified' => '2017-11-17 12:37:29'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.grogle-accounting'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:30',
            'modified' => '2017-11-17 12:37:30'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.grogle-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:31',
            'modified' => '2017-11-17 12:37:31'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.grunt-board'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:32',
            'modified' => '2017-11-17 12:37:32'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.gunt-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:34',
            'modified' => '2017-11-17 12:37:34'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.gnupg-ergonom'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:35',
            'modified' => '2017-11-17 12:37:35'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.gnupg-accounting'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:36',
            'modified' => '2017-11-17 12:37:36'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.git-ergonom'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:37',
            'modified' => '2017-11-17 12:37:37'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.git-board'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:38',
            'modified' => '2017-11-17 12:37:38'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.inkscape-accounting'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:39',
            'modified' => '2017-11-17 12:37:39'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.inskape-creative'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:40',
            'modified' => '2017-11-17 12:37:40'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.inkscape-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:41',
            'modified' => '2017-11-17 12:37:41'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.jquery-carol'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:42',
            'modified' => '2017-11-17 12:37:42'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.jquery-ergonom'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:43',
            'modified' => '2017-11-17 12:37:43'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.jquery-board'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:44',
            'modified' => '2017-11-17 12:37:44'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.kde-accounting'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.kde'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.kde-board'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.kde'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.mailvelope-jean'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.mailvelope'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.jean'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.mocha-kathleen'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.mocha'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.kathleen'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.mocha-lynne'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.mocha'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.lynne'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.nodejs-marlyn'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.nodejs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.marlyn'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.nodejs-quality_assurance'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.nodejs'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.quality_assurance'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.openpgpjs-nancy'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.openpgpjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.nancy'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.openpgpjs-leadership_team'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.openpgpjs'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.leadership_team'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.linux-management'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.management'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.linux-orna'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.linux'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.orna'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.phpunit-thelma'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.phpunit'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.thelma'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.phpunit-network'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.phpunit'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.network'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.qgis-operations'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.qgis'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.operations'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.qgis-procurement'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.qgis'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.procurement'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.selenium-human_resource'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.selenium'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.human_resource'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.selenium-margaret'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.selenium'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.margaret'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid('permission.id.stealjs-resource_planning'),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.stealjs'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.resource_planning'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];

        return $permissions;
    }
}
