<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'role_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'username' => ['type' => 'string', 'length' => 254, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
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
            'id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'frances@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '1df25f7c-acf6-30fd-abb0-39e6a7462328',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'sofia@passbolt.com',
            'active' => true,
            'deleted' => true,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'kathleen@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '22e38bf3-6505-34c9-a6aa-55a906f18476',
            'role_id' => '857760a6-4f9d-3f1b-a292-95b630bcf03f',
            'username' => 'root@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'carol@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-01 18:17:04',
            'modified' => '2017-11-02 18:17:04'
        ],
        [
            'id' => '27102e91-6880-3312-a4c5-db00c228820e',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'nancy@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'role_id' => '23d941d5-3676-3443-afdb-aaf2456f3b49',
            'username' => 'admin@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2015-11-03 18:17:04',
            'modified' => '2016-11-03 18:17:04'
        ],
        [
            'id' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'wang@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '51925e16-17a8-38f8-a923-6b1f75337d2a',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'ruth@passbolt.com',
            'active' => false,
            'deleted' => true,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'hedy@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'jean@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '8a53f107-7ffc-3047-ad08-75effc419d21',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'ursula@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'marlyn@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'irene@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'ping@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'bfb648f5-9f37-343b-a616-7f3704d13d84',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'thelma@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
            'role_id' => '49aad81e-4f70-3380-a92e-12292597409f',
            'username' => 'anonymous@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'grace@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'edith@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:15:04',
            'modified' => '2017-11-03 18:16:04'
        ],
        [
            'id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'ada@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-09-03 18:17:04',
            'modified' => '2017-10-03 18:17:04'
        ],
        [
            'id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'betty@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-10-20 18:17:04',
            'modified' => '2017-10-27 18:17:04'
        ],
        [
            'id' => 'debaf643-1874-3466-abdd-ae64ed4e2ced',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'orna@passbolt.com',
            'active' => false,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'user@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'lynne@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 18:17:04',
            'modified' => '2017-11-03 18:17:04'
        ],
        [
            'id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
            'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'username' => 'dame@passbolt.com',
            'active' => true,
            'deleted' => false,
            'created' => '2017-11-03 16:17:04',
            'modified' => '2017-11-03 17:17:04'
        ],
    ];
}
