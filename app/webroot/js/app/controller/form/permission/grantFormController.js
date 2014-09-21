steal(
	'mad/form/formController.js'
).then(function () {

	/**
	 * @class passbolt.controller.form.permission.grantFormController
	 * @inherits {mad.form.FormController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a Permission Grant Form Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.form.permission.GrantFormController}
	 */
	mad.form.FormController.extend('passbolt.controller.form.permission.GrantFormController', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Init the form elements
		 * @return {void}
		 */
		'initFormElement': function () {
			// temporary for update demonstration
			this.options.data.Resource = this.options.data.Resource || {};
		},

		/**
		 * Render the form and init the form elements
		 * @return {void}
		 * @todo Think about a common form controller function initFormElement
		 */
		'render': function () {
			this._super();
			this.initFormElement();
			this.load(this.options.data);
		}

	});
});