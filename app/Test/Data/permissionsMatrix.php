<?php
return array(
	'Group' => array(
		'Category' => array(
			array(
				'aconame' => 'administration',
				'aroname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'accounts',
				'aroname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'developers',
				'result' => null
			),
			array(
				'aconame' => 'projects',
				'aroname' => 'developers team leads',
				'result' => PermissionType::UPDATE
			)
		),
		'Resource' => array(
			array(
				'aconame' => 'bank password',
				'aroname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'salesforce account',
				'aroname' => 'developers',
				'result' => null
			),
			// Test direct permission DENY
			array(
				'aconame' => 'facebook account',
				'aroname' => 'human resources',
				'result' => PermissionType::DENY
			),
			// Test direct permission Update
			array(
				'aconame' => 'salesforce account',
				'aroname' => 'human resources',
				'result' => PermissionType::UPDATE
			),
			// Test multi parents for resource
			array(
				'aconame' => 'shared resource',
				'aroname' => 'company a',
				'result' => PermissionType::UPDATE
			)
		)
	),
	'User' => array(
		'Category' => array(
			array(
				'aconame' => 'marketing',
				'aroname' => 'dark.vador@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'd-project2',
				'aroname' => 'cedric@passbolt.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'remy@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'manager.nogroup@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project2',
				'aroname' => 'remy@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'marketing',
				'aroname' => 'cedric@passbolt.com',
				'result' => null
			),
			array(
				'aconame' => 'cp-project2',
				'aroname' => 'frank@passbolt.com',
				'result' => PermissionType::DENY
			),
			array(
				'aconame' => 'pv-jean_rene',
				'aroname' => 'jean-rene@test.com',
				'result' => PermissionType::CREATE
			),
			array(
				'aconame' => 'Bolt Softwares Pvt. Ltd.',
				'aroname' => 'jean-rene@test.com',
				'result' => null
			),
		),
		'Resource' => array(
			array(
				'aconame' => 'facebook account',
				'aroname' => 'dark.vador@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'facebook account',
				'aroname' => 'myriam@passbolt.com',
				'result' => PermissionType::DENY
			),
			array(
				'aconame' => 'dp1-pwd1',
				'aroname' => 'cedric@passbolt.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'aroname' => 'remy@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'aroname' => 'manager.nogroup@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cpp2-pwd1',
				'aroname' => 'remy@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'shared resource',
				'aroname' => 'a-usr1@companya.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'dp2-pwd1',
				'aroname' => 'cedric@passbolt.com',
				'result' => PermissionType::DENY
			)
		)
	)
);