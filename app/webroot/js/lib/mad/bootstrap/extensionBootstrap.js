steal('mad/bootstrap/bootstrapInterface.js').then(function () {

	mad.bootstrap.BootstrapInterface.extend('mad.Bootstrap.ExtensionBootstrap', {}, {

		'init': function (el, options) {
			this._super(el, options);
		}
	});
});