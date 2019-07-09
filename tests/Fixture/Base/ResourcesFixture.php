<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResourcesFixture
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
        'name' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'username' => ['type' => 'string', 'length' => 64, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'uri' => ['type' => 'string', 'length' => 1024, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'deleted' => ['type' => 'index', 'columns' => ['deleted'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'name' => 'bower',
                'username' => 'bower',
                'uri' => 'bower.io',
                'description' => 'A package manager for the web!',
                'deleted' => false,
                'created' => '2017-07-02 18:51:47',
                'modified' => '2018-07-02 18:51:47',
                'created_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'modified_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74'
            ],
            [
                'id' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'name' => 'chai',
                'username' => 'masala',
                'uri' => 'http://chaijs.com/',
                'description' => 'Chai is a BDD / TDD assertion library for node and the browser',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'modified_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46'
            ],
            [
                'id' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'name' => 'Debian',
                'username' => 'jessy',
                'uri' => 'passbolt.dev',
                'description' => 'The universal operating system',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'modified_by' => '54c6278e-f824-5fda-91ff-3e946b18d994'
            ],
            [
                'id' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
                'name' => 'KDE',
                'username' => 'community',
                'uri' => 'kde.org',
                'description' => 'The Plasma Desktop is one of the most recognized projects of KDE',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'modified_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46'
            ],
            [
                'id' => '46c07495-6fa2-5ac7-a315-9b36e3969a21',
                'name' => 'OpenGPGJS',
                'username' => 'openpgpjs',
                'uri' => 'openpgpjs.org/',
                'description' => 'This project aims to provide an Open Source OpenPGP library in JavaScript so it can be used on virtually every device.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:48',
                'modified' => '2019-07-02 18:51:48',
                'created_by' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'modified_by' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf'
            ],
            [
                'id' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'name' => 'framasoft',
                'username' => 'framasoft',
                'uri' => 'https://soutenir.framasoft.org/',
                'description' => 'Parce que libre ne veut pas dire gratuit!',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'modified_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74'
            ],
            [
                'id' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'name' => 'free software foundation europe',
                'username' => 'fsfe',
                'uri' => 'https://fsfe.org/index.en.html',
                'description' => 'Free Software Foundation Europe is a charity that empowers users to control technology.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'modified_by' => '54c6278e-f824-5fda-91ff-3e946b18d994'
            ],
            [
                'id' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'name' => 'Enlightenment',
                'username' => 'efl',
                'uri' => 'https://www.enlightenment.org/',
                'description' => 'Party like it\'s 1996.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'modified_by' => 'f848277c-5398-58f8-a82a-72397af2d450'
            ],
            [
                'id' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'name' => 'centos',
                'username' => 'root',
                'uri' => 'centos.org',
                'description' => 'The CentOS Linux distribution is a platform derived from Red Hat Enterprise Linux (RHEL).',
                'deleted' => false,
                'created' => '2019-05-02 18:51:47',
                'modified' => '2019-06-02 18:51:47',
                'created_by' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'modified_by' => '54c6278e-f824-5fda-91ff-3e946b18d994'
            ],
            [
                'id' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
                'name' => 'Jquery',
                'username' => 'jquery',
                'uri' => 'jquery.com',
                'description' => 'jQuery is a cross-platform JavaScript library designed to simplify the client-side scripting of HTML.',
                'deleted' => true,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'modified_by' => '54c6278e-f824-5fda-91ff-3e946b18d994'
            ],
            [
                'id' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'name' => 'Gnupg',
                'username' => 'gpg',
                'uri' => 'gnupg.org',
                'description' => 'GnuPG is a complete and free implementation of the OpenPGP standard as defined by RFC4880',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'modified_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74'
            ],
            [
                'id' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'name' => 'Inkscape',
                'username' => 'vector',
                'uri' => 'https://inkscape.org/',
                'description' => 'Inkscape is a professional vector graphics editor. It is free and open source.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'modified_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd'
            ],
            [
                'id' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'name' => 'april',
                'username' => 'support',
                'uri' => 'https://www.april.org/',
                'description' => 'L\'association pionnière du logiciel libre en France',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'modified_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46'
            ],
            [
                'id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'name' => 'apache',
                'username' => 'www-data',
                'uri' => 'http://www.apache.org/',
                'description' => 'Apache is the world\'s most used web server software.',
                'deleted' => false,
                'created' => '2019-06-30 18:51:47',
                'modified' => '2019-07-01 18:51:47',
                'created_by' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'modified_by' => 'f848277c-5398-58f8-a82a-72397af2d450'
            ],
            [
                'id' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'name' => 'ftp',
                'username' => 'user',
                'uri' => 'ftp://192.168.1.1',
                'description' => 'ftp test',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'modified_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd'
            ],
            [
                'id' => '9568770f-59b5-524a-b61b-22526e7ef7c6',
                'name' => 'Mocha',
                'username' => 'svp',
                'uri' => 'mochajs.org',
                'description' => 'Mocha is a feature-rich JavaScript test framework running on Node.js and in the browser, making asynchronous testing simple and fun.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'modified_by' => '32d29702-85e2-539d-98ac-6abfa7aadf01'
            ],
            [
                'id' => '97fdaf32-27e7-5549-9255-aa928ddd57b0',
                'name' => 'QGIS',
                'username' => 'qgis',
                'uri' => 'phpunit.de',
                'description' => 'QGIS is a free and open-source cross-platform desktop geographic information system (GIS) application that supports viewing, editing, and analysis of geospatial data.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:48',
                'modified' => '2019-07-02 18:51:48',
                'created_by' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'modified_by' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08'
            ],
            [
                'id' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'name' => 'Docker',
                'username' => 'docker',
                'uri' => 'https://www.docker.com/',
                'description' => 'An open platform for distributed applications for developers and sysadmins',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'modified_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd'
            ],
            [
                'id' => 'ad0d80e0-0441-5388-b679-13c41a693442',
                'name' => 'Nodejs',
                'username' => 'npm',
                'uri' => 'nodejs.org',
                'description' => 'Node.js® is a JavaScript runtime built on Chrome\'s V8 JavaScript engine.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'modified_by' => 'e1ebc592-b90d-5e22-9f40-50e52911673b'
            ],
            [
                'id' => 'bafcb992-c2bb-5d21-b6bd-3637fed2299f',
                'name' => 'Virtualbox',
                'username' => 'virtualbox',
                'uri' => 'virtualbox.org',
                'description' => 'VirtualBox is a powerful x86 and AMD64/Intel64 virtualization product for enterprise as well as home use.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:48',
                'modified' => '2019-07-02 18:51:48',
                'created_by' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190',
                'modified_by' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190'
            ],
            [
                'id' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'name' => 'Canjs',
                'username' => 'yeswecan',
                'uri' => 'canjs.com',
                'description' => 'CanJS is a JavaScript library that makes developing complex applications simple and fast.',
                'deleted' => false,
                'created' => '2019-06-18 18:51:47',
                'modified' => '2019-06-25 18:51:47',
                'created_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'modified_by' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd'
            ],
            [
                'id' => 'd1d240e1-9809-5ee3-9b59-2e1232d3faf0',
                'name' => 'StealJs',
                'username' => 'stealjs',
                'uri' => 'stealjs.com',
                'description' => 'Futuristic JavaScript dependency loader and builder.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:48',
                'modified' => '2019-07-02 18:51:48',
                'created_by' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190',
                'modified_by' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190'
            ],
            [
                'id' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'name' => 'Git',
                'username' => 'git',
                'uri' => 'git-scm.com',
                'description' => 'Git is a free and open source distributed version control system.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'modified_by' => '54c6278e-f824-5fda-91ff-3e946b18d994'
            ],
            [
                'id' => 'd5c11891-a5c6-5475-ae14-4d607960d622',
                'name' => 'Linux',
                'username' => 'linus',
                'uri' => 'kernel.org',
                'description' => 'The Linux kernel is an open-source monolithic Unix-like computer operating system kernel.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'modified_by' => '8d04cf98-716b-5f6d-9fe8-c130f8992646'
            ],
            [
                'id' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'name' => 'cakephp',
                'username' => 'cake',
                'uri' => 'cakephp.org',
                'description' => 'The rapid and tasty php development framework',
                'deleted' => false,
                'created' => '2019-07-02 16:51:47',
                'modified' => '2019-07-02 17:51:47',
                'created_by' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'modified_by' => 'f848277c-5398-58f8-a82a-72397af2d450'
            ],
            [
                'id' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'name' => 'Grunt',
                'username' => 'grunt',
                'uri' => 'gruntjs.com',
                'description' => 'The javascript taskrunner',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'modified_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46'
            ],
            [
                'id' => 'eb3c4800-aa75-5d84-bb88-99247486a8c5',
                'name' => 'Mailvelope',
                'username' => 'thomas',
                'uri' => 'mailvelope.com',
                'description' => 'Mailvelope is a browser extension that enables the exchange of encrypted emails following the OpenPGP encryption standard.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'modified_by' => '0da907bd-5c57-5acc-ba39-c6ebe091f613'
            ],
            [
                'id' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'name' => 'Grogle',
                'username' => 'grd',
                'uri' => 'http://fr.groland.wikia.com/wiki/Grogle',
                'description' => '',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'modified_by' => 'f848277c-5398-58f8-a82a-72397af2d450'
            ],
            [
                'id' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'name' => 'composer',
                'username' => 'getcomposer',
                'uri' => 'getcomposer.org',
                'description' => 'Dependency Manager for PHP',
                'deleted' => false,
                'created' => '2019-07-02 18:49:47',
                'modified' => '2019-07-02 18:50:47',
                'created_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'modified_by' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74'
            ],
            [
                'id' => 'fa63515f-453c-522a-bcc6-3bea185638f0',
                'name' => 'PHPunit',
                'username' => 'phpunit',
                'uri' => 'phpunit.de',
                'description' => 'PHPUnit is a programmer-oriented testing framework for PHP.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:48',
                'modified' => '2019-07-02 18:51:48',
                'created_by' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'modified_by' => '5302c3cb-5d33-53b1-82cd-57df36e13acc'
            ],
            [
                'id' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'name' => 'FOSDEM',
                'username' => 'fodem',
                'uri' => 'fosdem.org',
                'description' => 'FOSDEM is a free event for software developers to meet, share ideas and collaborate.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:47',
                'modified' => '2019-07-02 18:51:47',
                'created_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'modified_by' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46'
            ],
            [
                'id' => 'ff3ee3f2-435f-5383-93dc-fea804460936',
                'name' => 'Selenium',
                'username' => 'selenium',
                'uri' => 'docs.seleniumhq.org',
                'description' => 'Selenium automates browsers.',
                'deleted' => false,
                'created' => '2019-07-02 18:51:48',
                'modified' => '2019-07-02 18:51:48',
                'created_by' => 'a0559bb5-050b-50a3-ad39-c6756a46dbb7',
                'modified_by' => 'a0559bb5-050b-50a3-ad39-c6756a46dbb7'
            ],
        ];
        parent::init();
    }
}
