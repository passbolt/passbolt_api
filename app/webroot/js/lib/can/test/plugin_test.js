steal('./setup.js').then(
	'can/construct/proxy/proxy_test.js'
	, 'can/construct/super/super_test.js'
	, 'can/observe/attributes/attributes_test.js'
	, 'can/observe/delegate/delegate_test.js'
	, 'can/observe/backup/backup_test.js'
	, 'can/observe/setter/setter_test.js'
	, 'can/observe/validations/validations_test.js'
	, 'can/observe/sort/sort_test.js'
	, 'can/control/route/route_test.js'
	, 'can/control/view/test/qunit/view_test.js'
	, 'can/util/fixture/fixture_test.js'
	// TODO	, 'can/control/modifier/modifier_test.js'
	, function() {
		if(window.jQuery) {
			steal('can/control/plugin/plugin_test.js', 'can/view/modifiers/modifiers_test.js');
		}
	}
)