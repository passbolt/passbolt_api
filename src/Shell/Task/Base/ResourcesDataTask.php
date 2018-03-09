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

class ResourcesDataTask extends DataTask
{
    public $entityName = 'Resources';

    /**
     * Get the resource data
     *
     * @return array
     */
    public function getData()
    {
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.apache'),
            'name' => 'apache',
            'username' => 'www-data',
            'uri' => 'http://www.apache.org/',
            'description' => 'Apache is the world\'s most used web server software.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada'),
            'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.april'),
            'name' => 'april',
            'username' => 'support',
            'uri' => 'https://www.april.org/',
            'description' => 'L\'association pionnière du logiciel libre en France',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.betty'),
            'modified_by' => UuidFactory::uuid('user.id.betty'),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.bower'),
            'name' => 'bower',
            'username' => 'bower',
            'uri' => 'bower.io',
            'description' => 'A package manager for the web!',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.carol'),
            'modified_by' => UuidFactory::uuid('user.id.carol'),
            'created' => date('Y-m-d H:i:s', strtotime('-2 years')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 years')),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.cakephp'),
            'name' => 'cakephp',
            'username' => 'cake',
            'uri' => 'cakephp.org',
            'description' => 'The rapid and tasty php development framework',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada'),
            'created' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 hours')),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.canjs'),
            'name' => 'Canjs',
            'username' => 'yeswecan',
            'uri' => 'canjs.com',
            'description' => 'CanJS is a JavaScript library that makes developing complex applications simple and fast.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.edith'),
            'modified_by' => UuidFactory::uuid('user.id.edith'),
            'created' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 weeks')),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.centos'),
            'name' => 'centos',
            'username' => 'root',
            'uri' => 'centos.org',
            'description' => 'The CentOS Linux distribution is a platform derived from Red Hat Enterprise Linux (RHEL).',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.dame'),
            'modified_by' => UuidFactory::uuid('user.id.dame'),
            'created' => date('Y-m-d H:i:s', strtotime('-2 months')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 months')),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.chai'),
            'name' => 'chai',
            'username' => 'masala',
            'uri' => 'http://chaijs.com/',
            'description' => 'Chai is a BDD / TDD assertion library for node and the browser',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.betty'),
            'modified_by' => UuidFactory::uuid('user.id.betty')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.composer'),
            'name' => 'composer',
            'username' => 'getcomposer',
            'uri' => 'getcomposer.org',
            'description' => 'Dependency Manager for PHP',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.carol'),
            'modified_by' => UuidFactory::uuid('user.id.carol'),
            'created' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 minutes')),
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.debian'),
            'name' => 'Debian',
            'username' => 'jessy',
            'uri' => 'passbolt.dev',
            'description' => 'The universal operating system',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.dame'),
            'modified_by' => UuidFactory::uuid('user.id.dame')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.docker'),
            'name' => 'Docker',
            'username' => 'docker',
            'uri' => 'https://www.docker.com/',
            'description' => 'An open platform for distributed applications for developers and sysadmins',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.edith'),
            'modified_by' => UuidFactory::uuid('user.id.edith')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.enlightenment'),
            'name' => 'Enlightenment',
            'username' => 'efl',
            'uri' => 'https://www.enlightenment.org/',
            'description' => 'Party like it\'s 1996.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.fosdem'),
            'name' => 'FOSDEM',
            'username' => 'fodem',
            'uri' => 'fosdem.org',
            'description' => 'FOSDEM is a free event for software developers to meet, share ideas and collaborate.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.betty'),
            'modified_by' => UuidFactory::uuid('user.id.betty')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.framasoft'),
            'name' => 'framasoft',
            'username' => 'framasoft',
            'uri' => 'https://soutenir.framasoft.org/',
            'description' => 'Parce que libre ne veut pas dire gratuit!',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.carol'),
            'modified_by' => UuidFactory::uuid('user.id.carol')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.fsfe'),
            'name' => 'free software foundation europe',
            'username' => 'fsfe',
            'uri' => 'https://fsfe.org/index.en.html',
            'description' => 'Free Software Foundation Europe is a charity that empowers users to control technology.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.dame'),
            'modified_by' => UuidFactory::uuid('user.id.dame')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.ftp'),
            'name' => 'ftp',
            'username' => 'user',
            'uri' => 'ftp://192.168.1.1',
            'description' => 'ftp test',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.edith'),
            'modified_by' => UuidFactory::uuid('user.id.edith')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.git'),
            'name' => 'Git',
            'username' => 'git',
            'uri' => 'git-scm.com',
            'description' => 'Git is a free and open source distributed version control system.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.dame'),
            'modified_by' => UuidFactory::uuid('user.id.dame')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.gnupg'),
            'name' => 'Gnupg',
            'username' => 'gpg',
            'uri' => 'gnupg.org',
            'description' => 'GnuPG is a complete and free implementation of the OpenPGP standard as defined by RFC4880',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.carol'),
            'modified_by' => UuidFactory::uuid('user.id.carol')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.grogle'),
            'name' => 'Grogle',
            'username' => 'grd',
            'uri' => 'http://fr.groland.wikia.com/wiki/Grogle',
            'description' => '',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.grunt'),
            'name' => 'Grunt',
            'username' => 'grunt',
            'uri' => 'gruntjs.com',
            'description' => 'The javascript taskrunner',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.betty'),
            'modified_by' => UuidFactory::uuid('user.id.betty')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.inkscape'),
            'name' => 'Inkscape',
            'username' => 'vector',
            'uri' => 'https://inkscape.org/',
            'description' => 'Inkscape is a professional vector graphics editor. It is free and open source.',
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.edith'),
            'modified_by' => UuidFactory::uuid('user.id.edith')
        ];
        $resources[] = [
            'id' => UuidFactory::uuid('resource.id.jquery'),
            'name' => 'Jquery',
            'username' => 'jquery',
            'uri' => 'jquery.com',
            'description' => 'jQuery is a cross-platform JavaScript library designed to simplify the client-side scripting of HTML.',
            'deleted' => 1,
            'created_by' => UuidFactory::uuid('user.id.dame'),
            'modified_by' => UuidFactory::uuid('user.id.dame')
        ];

        return $resources;
    }
}
