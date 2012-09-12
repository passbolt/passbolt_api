steal(
	MAD_ROOT + '/controller/controller.js'
).then(function ($) {

	/*
	 * @class passbolt.controller.CategoryController
	 * @inherits mad.controller.Controller
	 * @parent index
	 * 
	 * @constructor
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.CategoryController}
	 */
	mad.controller.Controller.extend('passbolt.controller.CategoryController', /** @static */ {

		'create': function () {
			steal.dev.log('add new password');
		},

		'get': function (options, callback) {
			var options = options || {};
			passbolt.model.Category.get(options, function (category) {
				callback(category);
			});
		},

		'update': function () {
			steal.dev.log('update password');
		},

		'delete': function () {
			steal.dev.log('delete password');
		}

	}, /** @prototype */  {});
});