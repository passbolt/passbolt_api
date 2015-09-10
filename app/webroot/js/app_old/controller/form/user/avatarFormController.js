steal(
	'mad/form/formController.js',
	'app/view/template/form/user/avatarForm.ejs'
).then(function () {

		/**
		 * @class passbolt.controller.form.user.AvatarFormController
		 * @inherits {mad.form.FormController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciate a User Avatar Form Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.form.user.AvatarFormController}
		 */
		mad.form.FormController.extend('passbolt.controller.form.user.AvatarFormController', /** @static */ {
			'defaults': {
				'templateBased': true
			}
		}, /** @prototype */ {

			/**
			 * After start hook.
			 * Create the form elements
			 *
			 * @return {void}
			 */
			'afterStart': function () {

				// Rebind controller events
				this.on();
			}

		});
	});