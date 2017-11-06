<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResourcesFixture
 *
 */
class ResourcesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'username' => ['type' => 'string', 'length' => 64, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'uri' => ['type' => 'string', 'length' => 1024, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
            'name' => 'april',
            'username' => 'support',
            'uri' => 'https://www.april.org/',
            'description' => 'L\'association pionnière du logiciel libre en France',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
            'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
        ],
        [
            'id' => '17c66127-0c5e-3510-a497-2e6a105109db',
            'name' => 'Inkscape',
            'username' => 'vector',
            'uri' => 'https://inkscape.org/',
            'description' => 'Inkscape is a professional vector graphics editor. It is free and open source.',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
            'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
        ],
        [
            'id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
            'name' => 'apache',
            'username' => 'www-data',
            'uri' => 'http://www.apache.org/',
            'description' => 'Apache is the world\'s most used web server software.',
            'deleted' => false,
            'created' => '2017-11-01 18:17:05',
            'modified' => '2017-11-02 18:17:05',
            'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
            'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
        ],
        [
            'id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
            'name' => 'Enlightenment',
            'username' => 'efl',
            'uri' => 'https://www.enlightenment.org/',
            'description' => 'Party like it\'s 1996.',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
            'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
        ],
        [
            'id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
            'name' => 'free software foundation europe',
            'username' => 'fsfe',
            'uri' => 'https://fsfe.org/index.en.html',
            'description' => 'Free Software Foundation Europe is a charity that empowers users to control technology.',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
            'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
        ],
        [
            'id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
            'name' => 'bower',
            'username' => 'bower',
            'uri' => 'bower.io',
            'description' => 'A package manager for the web!',
            'deleted' => false,
            'created' => '2015-11-03 18:17:05',
            'modified' => '2016-11-03 18:17:05',
            'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
            'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
        ],
        [
            'id' => '45efd622-6d25-3144-af33-43b8480b166a',
            'name' => 'chai',
            'username' => 'masala',
            'uri' => 'http://chaijs.com/',
            'description' => 'Chai is a BDD / TDD assertion library for node and the browser',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
            'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
        ],
        [
            'id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
            'name' => 'ftp',
            'username' => 'user',
            'uri' => 'ftp://192.168.1.1',
            'description' => 'ftp test',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
            'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
        ],
        [
            'id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
            'name' => 'cakephp',
            'username' => 'cake',
            'uri' => 'cakephp.org',
            'description' => 'The rapid and tasty php development framework',
            'deleted' => false,
            'created' => '2017-11-03 16:17:05',
            'modified' => '2017-11-03 17:17:05',
            'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
            'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
        ],
        [
            'id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
            'name' => 'FOSDEM',
            'username' => 'fodem',
            'uri' => 'fosdem.org',
            'description' => 'FOSDEM is a free event for software developers to meet, share ideas and collaborate.',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
            'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
        ],
        [
            'id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
            'name' => 'Docker',
            'username' => 'docker',
            'uri' => 'https://www.docker.com/',
            'description' => 'An open platform for distributed applications for developers and sysadmins',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
            'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
        ],
        [
            'id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
            'name' => 'Grogle',
            'username' => 'grd',
            'uri' => 'http://fr.groland.wikia.com/wiki/Grogle',
            'description' => '',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
            'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
        ],
        [
            'id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
            'name' => 'Grunt',
            'username' => 'grunt',
            'uri' => 'gruntjs.com',
            'description' => 'The javascript taskrunner',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
            'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
        ],
        [
            'id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
            'name' => 'Canjs',
            'username' => 'yeswecan',
            'uri' => 'canjs.com',
            'description' => 'CanJS is a JavaScript library that makes developing complex applications simple and fast.',
            'deleted' => false,
            'created' => '2017-10-20 18:17:05',
            'modified' => '2017-10-27 18:17:05',
            'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
            'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
        ],
        [
            'id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
            'name' => 'Debian',
            'username' => 'jessy',
            'uri' => 'passbolt.dev',
            'description' => 'The universal operating system',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
            'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
        ],
        [
            'id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
            'name' => 'centos',
            'username' => 'root',
            'uri' => 'centos.org',
            'description' => 'The CentOS Linux distribution is a platform derived from Red Hat Enterprise Linux (RHEL).',
            'deleted' => false,
            'created' => '2017-09-03 18:17:05',
            'modified' => '2017-10-03 18:17:05',
            'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
            'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
        ],
        [
            'id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
            'name' => 'framasoft',
            'username' => 'framasoft',
            'uri' => 'https://soutenir.framasoft.org/',
            'description' => 'Parce que libre ne veut pas dire gratuit!',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
            'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
        ],
        [
            'id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
            'name' => 'Gnupg',
            'username' => 'gpg',
            'uri' => 'gnupg.org',
            'description' => 'GnuPG is a complete and free implementation of the OpenPGP standard as defined by RFC4880',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
            'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
        ],
        [
            'id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
            'name' => 'composer',
            'username' => 'getcomposer',
            'uri' => 'getcomposer.org',
            'description' => 'Dependency Manager for PHP',
            'deleted' => false,
            'created' => '2017-11-03 18:15:05',
            'modified' => '2017-11-03 18:16:05',
            'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
            'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
        ],
        [
            'id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
            'name' => 'Git',
            'username' => 'git',
            'uri' => 'git-scm.com',
            'description' => 'Git is a free and open source distributed version control system.',
            'deleted' => false,
            'created' => '2017-11-03 18:17:05',
            'modified' => '2017-11-03 18:17:05',
            'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
            'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
        ],
    ];
}
