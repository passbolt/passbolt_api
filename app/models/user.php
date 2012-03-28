<?php
class User extends AppModel {
	var $name = 'User';
	 var $hasAndBelongsToMany = array(
        'Group' =>
            array(
                'className'              => 'Group',
                'joinTable'              => 'groups_users',
                'foreignKey'             => 'user_id',
                'associationForeignKey'  => 'group_id',
                'unique'                 => true,
                'conditions'             => '',
                'fields'                 => '',
                'order'                  => '',
                'limit'                  => '',
                'offset'                 => '',
                'finderQuery'            => '',
                'deleteQuery'            => '',
                'insertQuery'            => ''
            )
    );
    var $validate = array(
		'name' => array(
			'rule' => array('minLength', '3'),
			'message' => 'Please enter a title that contains at least 3 characters'
		),
		'email' => array(
			'rule' => array('minLength', '3'),
			'message' => 'Please enter a title that contains at least 3 characters'
		),
		'password' => array(
			'rule' => array('minLength', '4'),
			'message' => 'Mimimum 8 characters long'
		),
		'passwordRepeat' => array(
			'rule' => array('minLength', '4'),
			'message' => 'Mimimum 8 characters long'
		)
	);
}
