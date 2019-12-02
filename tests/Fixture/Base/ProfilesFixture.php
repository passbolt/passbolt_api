<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProfilesFixture
 */
class ProfilesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'first_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'last_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
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
                'id' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'first_name' => 'Edith',
                'last_name' => 'Clarke',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '0c39d45d-5355-53d8-ab10-8375ce3425da',
                'user_id' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'first_name' => 'Nancy',
                'last_name' => 'Leveson',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '12265c99-7d79-5b69-b63d-bb28cd29c6bd',
                'user_id' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'first_name' => 'Lynne',
                'last_name' => 'Jolitz',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '1fcdc377-3759-5dff-8b91-3b5d00cec999',
                'user_id' => '92946500-2940-54ff-889a-3da69afe5078',
                'first_name' => 'Joan',
                'last_name' => 'Clarke',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'first_name' => 'Dame Steve',
                'last_name' => 'Shirley',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '403c7bdf-068d-585a-8fc0-2049a131f8e6',
                'user_id' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'first_name' => 'Hedy',
                'last_name' => 'Lamarr',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'first_name' => 'Carol',
                'last_name' => 'Shaw',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '543865d0-5f9b-598d-928b-2811f3cae77f',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'first_name' => 'Frances',
                'last_name' => 'Allen',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '5984642b-1be7-539a-850f-749c752bd610',
                'user_id' => '8d038399-ecac-55b4-8ad3-b7f0650de2a2',
                'first_name' => 'Orna',
                'last_name' => 'Berry',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '5ab1b8a0-6ef9-5d49-81c2-ae1de848b629',
                'user_id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'first_name' => 'Ping',
                'last_name' => 'Fu',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '5d3858cc-73e2-5b0f-9757-4ce9fecb7b6c',
                'user_id' => '742554b6-2940-5b7d-a8e7-b03a19f78b8e',
                'first_name' => 'Margaret',
                'last_name' => 'Hamilton',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'first_name' => 'Kathleen',
                'last_name' => 'Antonelli',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '92ccfd1b-6eb8-5e1c-a022-cf22463e8361',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '95af8ec1-ff13-5ebc-a9ea-f8b8ad2ee46e',
                'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
                'first_name' => 'Sofia',
                'last_name' => 'Kovalevskaya',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'first_name' => 'Ada',
                'last_name' => 'Lovelace',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'a2143312-d2f3-5ab5-a790-29a1f5d0217d',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'first_name' => 'Wang',
                'last_name' => 'Xiaoyun',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'b10a6f64-668c-5947-9602-29ccbbc26ece',
                'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
                'first_name' => 'Ruth',
                'last_name' => 'Teitelbaum',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'c1c1552b-486a-504f-a317-7efa0973384d',
                'user_id' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'first_name' => 'Marlyn',
                'last_name' => 'Wescoff',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'c219edf1-e104-55dc-ac80-cefdaffc943a',
                'user_id' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190',
                'first_name' => 'Adele',
                'last_name' => 'Goldstine',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'c551fc12-59b4-51ad-ae73-1659812e9ba5',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'first_name' => 'Irene',
                'last_name' => 'Greif',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'c6b23ff3-b8e3-52b8-bf76-2cd57e8c701d',
                'user_id' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'first_name' => 'Thelma',
                'last_name' => 'Estrin',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'c94371fe-8fcc-5b77-b50e-2db38646a673',
                'user_id' => '6aabffc9-f788-58f8-9bc9-f4c102ad2f53',
                'first_name' => 'Anonymous',
                'last_name' => 'User',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'first_name' => 'Betty',
                'last_name' => 'Holberton',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'd12b4113-9368-5923-9e86-deea9fdca094',
                'user_id' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'first_name' => 'Grace',
                'last_name' => 'Hopper',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'd15ca284-74b3-56ef-a9f4-02816113797f',
                'user_id' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'first_name' => 'Jean',
                'last_name' => 'Bartik',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'f6177e5b-ef9e-53c6-a4de-a5be4117d646',
                'user_id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'first_name' => 'Ursula',
                'last_name' => 'Martin',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
            [
                'id' => 'f80586be-369b-5732-9184-8bb7db74d750',
                'user_id' => 'a0559bb5-050b-50a3-ad39-c6756a46dbb7',
                'first_name' => 'Yvonne',
                'last_name' => 'Choquet-Bruhat',
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41'
            ],
        ];
        parent::init();
    }
}
