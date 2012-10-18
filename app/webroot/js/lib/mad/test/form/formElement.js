steal('funcunit').then(function () {

	var testEnv = null;
	module("mad.form", {
		// runs before each test
		setup: function () {
			stop();

			S.open('//' + MAD_ROOT + '/test/testEnv/app.html', function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				// when the app is ready continue the tests
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		},
		// runs after each test
		teardown: function () {}
	});

});