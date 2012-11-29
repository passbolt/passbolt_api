APP_URL = 'http://passbolt.local';

steal(
	'mad/mad.js'
).then(
	// load the usefull models
	'mad/test/data/model/person.js',
	'mad/test/data/model/country.js'
).then(function () {

	$(document).ready(function () {

		//load the bootstrap of the application
		var boot = new mad.bootstrap.AppBootstrap({
			'config': [
				'mad/test/testEnv/config.json'
			],
			'callbacks' : {
				'ready': function () {
					$('body').addClass('mad_test_app_ready');
				}
			}
		});

	});

});