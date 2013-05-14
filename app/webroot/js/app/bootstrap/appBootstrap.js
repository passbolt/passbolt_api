steal(
	'mad/bootstrap/appBootstrap.js',
	'app/helper/errorHandler.js'
).then( function () {

	mad.bootstrap.AppBootstrap.extend('passbolt.bootstrap.AppBootstrap', {});

});