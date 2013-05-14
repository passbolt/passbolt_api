APP_URL = 'http://passbolt.local';
APP_NS_ID = 'mad_test_appNs';
var APP_CTL_ID = 'mad_test_appCtlId';
var APP_EVENTBUS_ID = 'mad_test_eventBusId';

steal(
	'mad',
	'funcunit',
	'mad/test/testing.js',
	'funcunit/qunit/qunit.css'
).then(
	'app/test/controller/component/resourceActionsTabController.js'
);