<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
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
        'username' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
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
                'id' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'jean@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'lynne@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'edith@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:49:40',
                'modified' => '2019-07-02 18:50:40'
            ],
            [
                'id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'kathleen@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'ursula@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'dame@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 16:51:40',
                'modified' => '2019-07-02 17:51:40'
            ],
            [
                'id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'sofia@passbolt.com',
                'active' => true,
                'deleted' => true,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'grace@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'carol@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-06-30 18:51:40',
                'modified' => '2019-07-01 18:51:40'
            ],
            [
                'id' => '6aabffc9-f788-58f8-9bc9-f4c102ad2f53',
                'role_id' => '6f02b8d2-e24c-51fe-a452-5a027c26dbef',
                'username' => 'anonymous@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '742554b6-2940-5b7d-a8e7-b03a19f78b8e',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'margaret@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'frances@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '8d038399-ecac-55b4-8ad3-b7f0650de2a2',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'orna@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'hedy@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'irene@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '92946500-2940-54ff-889a-3da69afe5078',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'joan@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'ruth@passbolt.com',
                'active' => false,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'wang@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'a0559bb5-050b-50a3-ad39-c6756a46dbb7',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'yvonne@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'adele@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-05-02 18:51:40',
                'modified' => '2019-06-02 18:51:40'
            ],
            [
                'id' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'thelma@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'role_id' => '0d51c3a8-5e67-5e3d-882f-e1868966d817',
                'username' => 'admin@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'marlyn@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'nancy@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'betty@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-06-18 18:51:40',
                'modified' => '2019-06-25 18:51:40'
            ],
            [
                'id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'ping@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-07-02 18:51:40',
                'modified' => '2019-07-02 18:51:40'
            ],
            [
                'id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'role_id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'username' => 'ada@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => '2019-05-02 18:51:39',
                'modified' => '2019-06-02 18:51:39'
            ],
        ];
        parent::init();
    }
}
