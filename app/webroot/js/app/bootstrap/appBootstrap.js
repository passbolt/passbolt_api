steal(
    MAD_ROOT,
	'app/helper/errorHandler.js'
)
.then( function () {

	mad.bootstrap.AppBootstrap.extend('passbolt.bootstrap.AppBootstrap', {

		'init': function (options) {
			this._super(options);
		}
	});
});