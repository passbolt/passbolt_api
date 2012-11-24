steal(
    'mad/controller/componentController.js',
    'app/view/component/appFilter.js',
    'app/view/template/component/appFilter.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.AppFilterController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 * 
	 * 
	 * @constructor
	 * Instanciate the application filter controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.AppFilterController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.AppFilterController', /** @static */ {

	}, /** @prototype */ {

		'init': function (el, options) {
			this._super(el, options);
		}
	});
});