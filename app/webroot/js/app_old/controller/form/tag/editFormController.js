steal(
	'mad/form/formController.js',
	'app/model/itemTag.js',
	'app/view/template/form/tag/editForm.ejs'
).then(function () {

		/**
		 * @class passbolt.controller.form.tag.EditFormController
		 * @inherits {mad.form.FormController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciate a Tag Edit Form Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 *    - foreignModel : name of the model this controller operates on (polymorphic behavior)
		 *    - foreignId : Id of the object this controller will perform operations on (polymorphic behavior)
		 * @return {passbolt.controller.form.tag.EditFormController}
		 */
		mad.form.FormController.extend('passbolt.controller.form.tag.EditFormController', /** @static */ {

			'defaults': {
				'templateBased': true,
				// The list of tags to take care
				'tags': null,
				// name of the model this controller operates on (polymorphic behavior)
				'foreignModel': null,
				// Id of the object this controller will perform operations on (polymorphic behavior)
				'foreignId': null,
				// Action
				'action': 'update'
			}

		}, /** @prototype */ {

			/**
			 * After start hook.
			 * Create the form elements
			 *
			 * @return {void}
			 */
			'afterStart': function () {
				// foreign_model hidden field
				this.addElement(new mad.form.element.TextboxController($('.js_tag_foreign_model', this.element), {
					modelReference: 'passbolt.model.ItemTag.foreign_model'
				}).start().setValue(this.options.foreignModel));

				// foreign_id hidden field
				this.addElement(new mad.form.element.TextboxController($('.js_tag_foreign_id', this.element), {
					modelReference: 'passbolt.model.ItemTag.foreign_id'
				}).start().setValue(this.options.foreignId));

				// this textbox will contain the list of tags separated with a comma
				this.tagList = this.addElement(
					new mad.form.element.TextboxController($('.js_tag_list', this.element), {
						modelReference: 'passbolt.model.ItemTag.tag_list',
						// Use a custom validation function.
						validateFunction: function(value, values) {
							var tagsName = value.split(',');
							for (var i in tagsName) {
								var validateTagName = passbolt.model.Tag.validateAttribute('name', tagsName[i], {});
								if (validateTagName !== true) {
									return validateTagName;
								}
							}
							return true;
						}
					}).start(),
					new mad.form.FeedbackController($('.js_field_tag_list_feedback', this.element), {}).start()
				);

				// Update the tag list textbox function of the given tags list
				this.updateTagListTxtbxValue();
			},

			/**
			 * Set the value of the textbox which will allow user to edit the tags list
			 */
			'updateTagListTxtbxValue': function () {
				var value = [];
				this.options.tags.each(function (itemTag, i) {
					value[i] = itemTag.Tag.name;
				});
				this.tagList.setValue(value.join(','));
			},

			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when item tags list are updated to the observed instance
			 * @param {mad.model.Model} model The model reference
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{tags} change': function (model, ev, itemTags) {
				// When the instance item tags list is update, refresh the value of the textbox
				// So when we save, it wil update the value => magic
				this.updateTagListTxtbxValue();
			}

		});
	});