<?php
class UserFixture extends CakeTestFixture {
  public $useDbConfig = 'test';
  public $import = 'User';
  public $records = array(
    array(
      'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
      'username' => 'Anonymous',
      'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', // see default role.sql
      'password' => 'blablabla123',
      'active' => 1,
      'created' => '2012-07-04 13:45:11', 
      'modified' => '2012-07-04 13:45:14',
      'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
      'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
    ),
    array(
      'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4s',
      'username' => 'Admin',
      'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c', // see default role.sql
      'password' => 'blablabla123',
      'active' => 1,
      'created' => '2012-07-04 13:45:11', 
      'modified' => '2012-07-04 13:45:14',
      'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
      'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
    )
  );
}
