steal(
	'mad/controller/componentController.js',
	'mad/helper/controllerHelper.js'
).then(function () {

	/*
	 * @class mad.controller.CompositeController
	 * @inherits mad.controller.componentController
	 * @parent mad.controller.component
	 *
	 * The class Composite controller is our representation of controllers which take
	 * care of UI Components.
	 * <br/>
	 * The class Component controller is associated to its own view which takes to
	 * display data to users.
	 *
	 * @constructor
	 * Creates a new Composite Controller
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.ResourceActionsTabController}
	 */
	mad.controller.Controller.extend('mad.controller.CompositeController', /** @static */ {

		'defaults': {
			// the default component label
			'label': 'CompositeController'
		}

	}, /** @prototype */ {

		// Constructor like
		'init': function (el, options) {
			this._super(el, options);
		}

	});

});