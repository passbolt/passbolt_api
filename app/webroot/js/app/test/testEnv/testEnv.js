steal(
	'mad/mad.js'
).then(function () {

	loadEnv = function () {
		var deferred = $.Deferred();
		//load the bootstrap of the application
		var boot = new mad.bootstrap.AppBootstrap({
			'config': ['mad/test/testEnv/config.json'],
			'callbacks' : {
				'ready': function () {
					deferred.resolveWith(this, []);
				}
			}
		});
		return deferred;
	};

	cleanEnv = function () {
		console.log('clean environment');
	};

});
