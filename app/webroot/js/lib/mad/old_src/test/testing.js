steal(
	'can/construct',
	'funcunit'
).then(function () {
	can.Construct('mad.test.testing', /** @static */{
		initEnv: function(url) {
			url = typeof(url) == 'undefined' ? '//lib/mad/test/testEnv/app.html' : url;
			stop();
			S.open(url, function () {
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		}
	}, /** @prototype */ { })

});
