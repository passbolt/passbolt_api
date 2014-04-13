steal(
	'mad/form/formController.js',
	'app/model/resource.js',
	'app/view/template/form/resource/editDescriptionForm.ejs'
).then(function() {

		/**
		 * @class passbolt.controller.form.resource.EditDescriptionFormController
		 * @inherits {mad.form.FormController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciate a Edit Resource Description Form Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.form.resource.EditDescriptionFormController}
		 */
		mad.form.FormController.extend('passbolt.controller.form.resource.EditDescriptionFormController', /** @static */ {

			'defaults' : {
				'templateBased' : true,
				// The current resource
				'resource': null,
				// Description field.
				'descriptionField': null
			}

		}, /** @prototype */ {

			/**
			 * After start hook.
			 * Create the form elements
			 *
			 * @return {void}
			 */
			'afterStart' : function() {
				// id hidden field
				this.addElement(new mad.form.element.TextboxController($('.js_resource_id', this.element), {
					modelReference : 'passbolt.model.Resource.id'
				}).start().setValue(this.options.resource.id));

				// this textbox will contain the list of tags separated with a comma
				this.options.descriptionField = this.addElement(
					new mad.form.element.TextboxController($('.js_resource_description', this.element), {
						modelReference : 'passbolt.model.Resource.description'
					}).start(),
					new mad.form.FeedbackController($('.js_resource_description_feedback', this.element), {}).start()
				);

				// Update the resource description with current value
				this.options.descriptionField.setValue(this.options.resource.description);

				// Force event submit event (not thrown by default)
				// TODO : understand why we need to do that... weird
				$('.button.resource-submit').click(function(){
					$(this).trigger('submit');
				});
			}
		});
	});