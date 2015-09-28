<?php
return array(
	'Group' => array(
		'Category' => array(
			array(
				'aconame' => 'administration',
				'aroname' => 'management',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'accounts',
				'aroname' => 'management',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'management',
				'result' => PermissionType::OWNER
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
				'result' => PermissionType::OWNER
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
				'aroname' => 'dame@passbolt.com',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'd-project2',
				'aroname' => 'carol@passbolt.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'ada@passbolt.com',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'cp-project1',
				'aroname' => 'lynne@passbolt.com',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'cp-project2',
				'aroname' => 'ada@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'marketing',
				'aroname' => 'carol@passbolt.com',
				'result' => null
			),
			array(
				'aconame' => 'cp-project2',
				'aroname' => 'frances@passbolt.com',
				'result' => PermissionType::DENY
			),
			array(
				'aconame' => 'pv-jean_bartik',
				'aroname' => 'jean@passbolt.com',
				'result' => PermissionType::CREATE
			),
			array(
				'aconame' => 'Bolt Softwares Pvt. Ltd.',
				'aroname' => 'jean@passbolt.com',
				'result' => null
			),
		),
		'Resource' => array(
			array(
				'aconame' => 'facebook account',
				'aroname' => 'dame@passbolt.com',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'facebook account',
				'aroname' => 'marlyn@passbolt.com',
				'result' => PermissionType::DENY
			),
			array(
				'aconame' => 'dp1-pwd1',
				'aroname' => 'carol@passbolt.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'aroname' => 'ada@passbolt.com',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'aroname' => 'lynne@passbolt.com',
				'result' => PermissionType::OWNER
			),
			array(
				'aconame' => 'cpp2-pwd1',
				'aroname' => 'ada@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'shared resource',
				'aroname' => 'hedy@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'dp2-pwd1',
				'aroname' => 'carol@passbolt.com',
				'result' => PermissionType::DENY
			)
		)
	)
);