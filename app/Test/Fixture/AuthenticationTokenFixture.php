<?php
/**
 * AuthenticationTokenFixture
 *
 */
class AuthenticationTokenFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'token' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '55c7593f-0ce8-43ef-8606-4a2fc0a80121',
			'token' => '55c7593f-d184-46e3-834d-4a2fc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:44:31',
			'modified' => '2015-08-09 15:44:31',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7599e-20c0-4134-8c8a-4a32c0a80121',
			'token' => '55c7599e-7190-42fd-b0b1-4a32c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:46:06',
			'modified' => '2015-08-09 15:46:06',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75a20-77ac-4718-a5b4-4a33c0a80121',
			'token' => '55c75a20-0aa8-4cc5-b1f1-4a33c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:48:16',
			'modified' => '2015-08-09 15:48:16',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75a20-e620-4645-8d92-5aedc0a80121',
			'token' => '55c75a20-e570-4d8a-974f-5aedc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:48:16',
			'modified' => '2015-08-09 15:48:16',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75b19-7368-4a89-b4b1-5b1cc0a80121',
			'token' => '55c75b19-6d68-456f-af68-5b1cc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:52:25',
			'modified' => '2015-08-09 15:52:25',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75b19-7b04-48f7-88b0-4a37c0a80121',
			'token' => '55c75b19-cfb8-4dfd-b9a4-4a37c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:52:25',
			'modified' => '2015-08-09 15:52:25',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75b1b-02a0-467a-b74e-4a31c0a80121',
			'token' => '55c75b1b-6748-4842-9629-4a31c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:52:27',
			'modified' => '2015-08-09 15:52:27',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75ba6-bef4-49b8-a8eb-5b1cc0a80121',
			'token' => '55c75ba6-0fc0-4f8e-9174-5b1cc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:54:46',
			'modified' => '2015-08-09 15:54:46',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75ba7-4cd8-4d6b-a92d-4a2fc0a80121',
			'token' => '55c75ba7-70d4-4f38-a566-4a2fc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:54:47',
			'modified' => '2015-08-09 15:54:47',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75baa-edb0-4029-a429-4a33c0a80121',
			'token' => '55c75baa-db50-4dfe-a911-4a33c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:54:50',
			'modified' => '2015-08-09 15:54:50',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75bdf-dd9c-4adb-b009-4a2fc0a80121',
			'token' => '55c75bdf-b1a8-44f3-9f6f-4a2fc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:55:43',
			'modified' => '2015-08-09 15:55:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75bdf-eb44-4a79-9c82-4a31c0a80121',
			'token' => '55c75bdf-bd50-46a2-be6a-4a31c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:55:43',
			'modified' => '2015-08-09 15:55:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75be3-5acc-4298-98f0-4a32c0a80121',
			'token' => '55c75be3-a6bc-4236-93f3-4a32c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 15:55:47',
			'modified' => '2015-08-09 15:55:47',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75d43-5bf8-435a-a3b1-4a30c0a80121',
			'token' => '55c75d43-f540-47a4-ab71-4a30c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 16:01:39',
			'modified' => '2015-08-09 16:01:39',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75d43-dd74-49cb-8ded-5b1cc0a80121',
			'token' => '55c75d43-eb60-411f-90d1-5b1cc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 16:01:39',
			'modified' => '2015-08-09 16:01:39',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75d4d-8084-4cbe-9f29-4a37c0a80121',
			'token' => '55c75d4d-dad4-4728-8b2c-4a37c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 16:01:49',
			'modified' => '2015-08-09 16:01:49',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75da9-2acc-440c-a91d-4a30c0a80121',
			'token' => '55c75da9-b6f8-4f49-b9fa-4a30c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 16:03:21',
			'modified' => '2015-08-09 16:03:21',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75daa-12e8-424a-9f0d-5aedc0a80121',
			'token' => '55c75daa-9f08-435f-8dd3-5aedc0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 16:03:22',
			'modified' => '2015-08-09 16:03:22',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c75db3-dcd4-454e-a527-4a33c0a80121',
			'token' => '55c75db3-ab94-4118-b4e7-4a33c0a8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 16:03:31',
			'modified' => '2015-08-09 16:03:31',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c775cb-47b0-403b-96db-4a31c0a80121',
			'token' => 'd1340e75-5507-49b2-a8ef-f6db4031',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:46:19',
			'modified' => '2015-08-09 17:46:19',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c775cb-a7d8-4842-b035-4a33c0a80121',
			'token' => '97c7a9f8-f7f6-4e89-aa75-1e4cc5d8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:46:19',
			'modified' => '2015-08-09 17:46:19',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c775cf-ff28-443c-a18d-5b1cc0a80121',
			'token' => 'd229e71c-4fdb-450d-a6eb-bcb30d07',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:46:23',
			'modified' => '2015-08-09 17:46:23',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7768c-489c-4bd5-9720-4a33c0a80121',
			'token' => 'c95f16f6-fa75-4d12-ac4d-612c6dd3',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:49:32',
			'modified' => '2015-08-09 17:49:32',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7768d-a70c-4f2b-968a-4a31c0a80121',
			'token' => '22e7450f-dcc6-4b6d-a7f6-6a744733',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:49:33',
			'modified' => '2015-08-09 17:49:33',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77691-18b4-4e5a-a45f-5b1cc0a80121',
			'token' => '8b2bd12d-e3e3-47a0-aa1a-649665e4',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:49:37',
			'modified' => '2015-08-09 17:49:37',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77735-103c-496e-a8fd-4a31c0a80121',
			'token' => '7d6fea81-febc-4a12-a119-4ca6d9a5',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:52:21',
			'modified' => '2015-08-09 17:52:21',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77735-1520-4428-8f6d-4a33c0a80121',
			'token' => '65032ce3-3827-4827-a74d-fe4ed5da',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:52:21',
			'modified' => '2015-08-09 17:52:21',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77739-6ee8-46c9-af85-5b1cc0a80121',
			'token' => '4f633d6d-9ab7-495f-a95d-e8580629',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:52:25',
			'modified' => '2015-08-09 17:52:25',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77786-a2c4-4c76-9d83-4a31c0a80121',
			'token' => '354c4d35-c859-4344-a441-f97e7c45',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:53:42',
			'modified' => '2015-08-09 17:53:42',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77786-e4d4-4a8d-a848-4a33c0a80121',
			'token' => 'a08711ce-b478-4caf-a10f-d7c3a9a6',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:53:42',
			'modified' => '2015-08-09 17:53:42',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7778b-8660-4eda-b30b-5b1cc0a80121',
			'token' => 'cdbb1680-5744-4260-ab21-b1b3f12f',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:53:47',
			'modified' => '2015-08-09 17:53:47',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c777e2-c47c-4c02-b345-4a37c0a80121',
			'token' => 'e525fc30-7e7c-4fcd-a52c-2b8ead90',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:55:14',
			'modified' => '2015-08-09 17:55:14',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c777e3-2970-4e2b-84ee-6580c0a80121',
			'token' => '58f7744d-a546-490c-a211-36a85053',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:55:15',
			'modified' => '2015-08-09 17:55:15',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c777e8-3a9c-429c-9ca1-4a31c0a80121',
			'token' => '3da675d6-8bf2-466f-a36d-01e1c262',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:55:20',
			'modified' => '2015-08-09 17:55:20',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77828-07ac-4dd9-9093-4a37c0a80121',
			'token' => '3cd9f852-b859-415c-a0d6-557de256',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:56:24',
			'modified' => '2015-08-09 17:56:24',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77828-ed78-4029-9739-6580c0a80121',
			'token' => '49e6a800-3711-4091-add7-1ca36c43',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:56:24',
			'modified' => '2015-08-09 17:56:24',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7782e-9c58-4ad1-b528-4a31c0a80121',
			'token' => 'd9906af4-b0e4-4948-a7b7-f481e0ee',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:56:30',
			'modified' => '2015-08-09 17:56:30',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7785e-374c-4791-91ed-4a37c0a80121',
			'token' => 'd56f433e-ff09-4451-ac5d-3b65b576',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:57:18',
			'modified' => '2015-08-09 17:57:18',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7785e-adc0-46be-be9e-6580c0a80121',
			'token' => '16711790-c929-4492-ae4d-1bc1c6fd',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:57:18',
			'modified' => '2015-08-09 17:57:18',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77863-5734-450a-b5b7-4a31c0a80121',
			'token' => 'fe1f46de-5690-43a5-aef9-6f72f44f',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:57:23',
			'modified' => '2015-08-09 17:57:23',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77885-054c-417f-a240-4a37c0a80121',
			'token' => 'd8339529-a951-4400-a2b3-9370a8c2',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:57:57',
			'modified' => '2015-08-09 17:57:57',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77885-ee08-4100-986a-6580c0a80121',
			'token' => '32305b80-d5ad-46cc-ad25-6cc81d50',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:57:57',
			'modified' => '2015-08-09 17:57:57',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7788b-9948-4c05-81f3-4a31c0a80121',
			'token' => '811ce7dd-81dc-454f-a80c-30035c22',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 17:58:03',
			'modified' => '2015-08-09 17:58:03',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77abd-c7f4-45e1-87b6-4a30c0a80121',
			'token' => '4b5c3a01-1a32-40e6-aeff-8aeb2ded',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:07:25',
			'modified' => '2015-08-09 18:07:25',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77abe-c488-4014-ac51-4a32c0a80121',
			'token' => '2ff0b99e-4ec9-4def-aed7-82740760',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:07:26',
			'modified' => '2015-08-09 18:07:26',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77ac7-3960-4df2-80a4-4a37c0a80121',
			'token' => '53447d1a-ba97-448e-a841-b97701e9',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:07:35',
			'modified' => '2015-08-09 18:07:35',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77ae7-ced4-431c-b1e9-4a30c0a80121',
			'token' => '7f666ff0-99bc-4cdc-a6ce-b6e3996c',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:08:07',
			'modified' => '2015-08-09 18:08:07',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77ae8-9084-4560-b17c-4a32c0a80121',
			'token' => 'c929b4c8-88e6-48a6-ab88-320dadad',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:08:08',
			'modified' => '2015-08-09 18:08:08',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77af1-4944-40db-bcdb-4a37c0a80121',
			'token' => 'eeb5ef78-2720-46e4-a3c3-eb4fa715',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:08:17',
			'modified' => '2015-08-09 18:08:17',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77b75-1c20-49aa-80c8-6580c0a80121',
			'token' => '83ed9398-b678-4ad9-a587-890a9163',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:10:29',
			'modified' => '2015-08-09 18:10:29',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77b76-74e4-40a4-8903-4a2fc0a80121',
			'token' => '0755d928-1b9d-4756-ac9e-6a48533b',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:10:30',
			'modified' => '2015-08-09 18:10:30',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77b7f-4368-4b99-880e-4a32c0a80121',
			'token' => '0c2fc2c9-6b09-4e0e-ab1f-66d8a45c',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:10:39',
			'modified' => '2015-08-09 18:10:39',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77d1b-19f0-4ee3-a98d-6580c0a80121',
			'token' => '14eca5c1-4d9f-43f1-a2f5-a9933f8e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:17:31',
			'modified' => '2015-08-09 18:17:31',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77d1b-b95c-45e5-9681-4a2fc0a80121',
			'token' => '067a1b06-4c5c-4dc0-a1fc-8d1f09b8',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:17:31',
			'modified' => '2015-08-09 18:17:31',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77d1f-4454-491a-95ad-4a32c0a80121',
			'token' => '9dd10b85-8e5c-4c60-ae77-536784f6',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:17:35',
			'modified' => '2015-08-09 18:17:35',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77e05-7ce0-4fbd-9e82-4a30c0a80121',
			'token' => 'affaffa3-52ce-4e69-ace6-eedeaddf',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:21:25',
			'modified' => '2015-08-09 18:21:25',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77e05-8c7c-4fd2-8776-4a2fc0a80121',
			'token' => '4e2f709a-3c81-4b2c-a4e7-d28aa613',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:21:25',
			'modified' => '2015-08-09 18:21:25',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77e08-4d40-4b0c-92a2-4a37c0a80121',
			'token' => '0d24ee80-fe79-49de-a415-4504307d',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:21:28',
			'modified' => '2015-08-09 18:21:28',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77f82-c538-4603-a990-4a30c0a80121',
			'token' => '2ce82c41-31c7-4ddb-a0ac-6e97c6cc',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:27:46',
			'modified' => '2015-08-09 18:27:46',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77f83-ff40-4744-b053-4a32c0a80121',
			'token' => '16445cb9-9254-4720-aeb1-12540c05',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:27:47',
			'modified' => '2015-08-09 18:27:47',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77f87-62c4-4fbd-af79-4a33c0a80121',
			'token' => '58a2d8b1-c675-4f6e-a767-1d18437e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:27:51',
			'modified' => '2015-08-09 18:27:51',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77fb9-2444-49a6-973f-4a37c0a80121',
			'token' => '4da789fd-65d5-40bc-a5f6-cf4899fa',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:28:41',
			'modified' => '2015-08-09 18:28:41',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77fb9-2aa0-4e2e-b80a-4a32c0a80121',
			'token' => '19a552d2-b16b-4043-a987-901b647b',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:28:41',
			'modified' => '2015-08-09 18:28:41',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77fbd-7138-49bb-bde2-5b1cc0a80121',
			'token' => 'b2036c7f-f1cc-4087-aa4f-2279fe1f',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:28:45',
			'modified' => '2015-08-09 18:28:45',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77fd0-85f0-4a42-9339-4a33c0a80121',
			'token' => 'a9ebaff5-2987-4e87-a55e-5e047553',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:29:04',
			'modified' => '2015-08-09 18:29:04',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77fd0-c4fc-4d9b-b48c-4a37c0a80121',
			'token' => '0794e57a-8dc7-4f34-af8b-c5b2776d',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:29:04',
			'modified' => '2015-08-09 18:29:04',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c77fd4-8174-4b30-994b-4a31c0a80121',
			'token' => '57cb2ea0-0c7f-416e-acc2-bccce3ab',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:29:08',
			'modified' => '2015-08-09 18:29:08',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78030-1cc0-470d-962b-4a33c0a80121',
			'token' => 'a9778def-54f2-461a-adf1-32557a0a',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:30:40',
			'modified' => '2015-08-09 18:30:40',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78031-885c-4dfc-a732-5b1cc0a80121',
			'token' => '97ccf7bc-88df-4da8-aad1-83b73c2b',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:30:41',
			'modified' => '2015-08-09 18:30:41',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78035-f468-4fe5-8db4-5aedc0a80121',
			'token' => 'c950514a-e68e-4c60-a6e3-27137d8b',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:30:45',
			'modified' => '2015-08-09 18:30:45',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c781d4-819c-45ee-bc9b-5b1cc0a80121',
			'token' => 'f8af7035-038b-457e-abc2-837ea6ad',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:37:40',
			'modified' => '2015-08-09 18:37:40',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c781d5-371c-48d9-aabb-4a31c0a80121',
			'token' => '04b97050-ee59-455e-a4c6-a17b3635',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:37:41',
			'modified' => '2015-08-09 18:37:41',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c781d9-6ea0-48d5-9b87-6580c0a80121',
			'token' => 'ddada224-58cd-4f99-ac02-802a7d3a',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:37:45',
			'modified' => '2015-08-09 18:37:45',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78257-2020-4886-90c2-6580c0a80121',
			'token' => 'b47fb9bf-f386-4a45-af94-62081d93',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:39:51',
			'modified' => '2015-08-09 18:39:51',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7825a-05d0-470c-8d1b-4a2fc0a80121',
			'token' => '1a54b579-f734-484d-a0c4-94ae0ca4',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:39:54',
			'modified' => '2015-08-09 18:39:54',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7825a-8b0c-40a3-a4bc-5b1cc0a80121',
			'token' => '398b2cbd-17b9-49e0-a866-b209b22a',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:39:54',
			'modified' => '2015-08-09 18:39:54',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c785f1-ab24-4ad2-87bd-5aedc0a80121',
			'token' => 'bbd325eb-a9c4-41d7-a2bc-b3870aae',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:55:12',
			'modified' => '2015-08-09 18:55:12',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78608-4ff8-40a0-b988-4a2fc0a80121',
			'token' => '12f5a836-66c7-461b-a622-11288d2d',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:55:36',
			'modified' => '2015-08-09 18:55:36',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78617-4f8c-4f40-bfbb-4a33c0a80121',
			'token' => 'e0c4b208-0be7-4364-a249-591dc96c',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:55:51',
			'modified' => '2015-08-09 18:55:51',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78628-257c-4c77-bdff-4a37c0a80121',
			'token' => 'e49ad01e-993e-48d0-a8b2-3bef7839',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:56:08',
			'modified' => '2015-08-09 18:56:08',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78642-01bc-46ec-9015-7887c0a80121',
			'token' => '2a19cf3f-d6d8-4eb1-ac85-9dbdc714',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 18:56:34',
			'modified' => '2015-08-09 18:56:34',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78716-a43c-412e-a7a5-4a33c0a80121',
			'token' => '7579012d-efba-4be6-ad7f-065ec5ae',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 19:00:06',
			'modified' => '2015-08-09 19:00:06',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c7875d-9c40-4473-9dff-4a37c0a80121',
			'token' => '478eb33f-0174-4ae3-a1ff-ade75cb7',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 19:01:17',
			'modified' => '2015-08-09 19:01:17',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78770-4dec-4e61-bc4d-5aedc0a80121',
			'token' => '2968bc09-1c3f-4df9-a1d2-cc8e29b4',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 19:01:36',
			'modified' => '2015-08-09 19:01:36',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c78787-c744-4446-b683-4a33c0a80121',
			'token' => '7d7eaa0c-d7a2-4f30-a7d7-04dfd6a5',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 19:01:59',
			'modified' => '2015-08-09 19:01:59',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c787a0-1e90-4c54-8138-4a37c0a80121',
			'token' => 'cf2e1eb7-4f1a-418e-ae27-e8d87a66',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 19:02:24',
			'modified' => '2015-08-09 19:02:24',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '55c787a2-ee98-4651-b699-6580c0a80121',
			'token' => '81348a2f-3b05-4871-afe6-b5035ecd',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'active' => '1',
			'created' => '2015-08-09 19:02:26',
			'modified' => '2015-08-09 19:02:26',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}
