steal(
	'mad/form/formController.js',
	'app/controller/component/secretStrengthController.js',
	'app/controller/form/secret/createFormController.js',
	'app/view/template/form/resource/createForm.ejs'
).then(function () {

	/**
	 * @class passbolt.controller.form.resource.CreateFormController
	 * @inherits {mad.form.FormController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a Resource Create Form Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.form.resource.CreateFormController}
	 */
	mad.form.FormController.extend('passbolt.controller.form.resource.CreateFormController', /** @static */ {
		'defaults': {
			'templateBased': true,
			'secretField': null,
			// @todo should be dynamic functions of creation or update
			'action': 'create',
			'secretsForms': [],
			'resource': null
		}
	}, /** @prototype */ {

		/**
		 * Before render.
		 */
		'beforeRender': function() {
			this._super();
			this.setViewData('resource', this.options.data);
		},

		/**
		 * After start hook.
		 * Create the form elements
		 * 
		 * @return {void}
		 */
		'afterStart': function () {
			var self = this;
			// temporary for update demonstration
			this.options.data.Resource = this.options.data.Resource || {};

			// Add category id hidden field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_category_id'), {
					modelReference: 'passbolt.model.Resource.Category.id',
                    validate: false
				}).start()
			);
			// Add resource name field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {
					modelReference: 'passbolt.model.Resource.name'
				}).start(),
				new mad.form.FeedbackController($('#js_field_name_feedback'), {}).start()
			);
			// Add resource uri field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_uri'), {
					modelReference: 'passbolt.model.Resource.uri'
				}).start(),
				new mad.form.FeedbackController($('#js_field_uri_feedback'), {}).start()
			);
			// Add resource username field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_username'), {
					modelReference: 'passbolt.model.Resource.username'
				}).start(),
				new mad.form.FeedbackController($('#js_field_username_feedback'), {}).start()
			);
			// Add secrets forms.
			can.each(this.options.data.Secret, function (secret, i) {
				var form = new passbolt.controller.form.secret.CreateFormController('#js_secret_edit_' + i, {
					data: secret,
					secret_i: i
				});
				form.start();
				form.load(secret);
				self.options.secretsForms.push(form);
			});
			// Add resource description field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_description'), {
					modelReference: 'passbolt.model.Resource.description'
				}).start(),
				new mad.form.FeedbackController($('#js_field_description_feedback'), {}).start()
			);
			$('#js_field_name').focus();

			// Notify the plugin that the resource is ready to be edited.
			mad.bus.trigger('passbolt.plugin.resource_edition');
		},

		/**
		 * @See parent:: submit();
		 */
		' submit': function (el, ev) {
			ev.preventDefault();

			// Form data are valid
			if (this.validate()) {
				var usersIds = [];

				if (this.options.action == 'edit') {
					// Get the users to encrypt the resource for.
					// @todo #security move that checking into the plugin.
					passbolt.model.Permission.findAll({
						'aco': this.options.data.constructor.shortName,
						'aco_foreign_key': this.options.data.id
					}, function (permissions, response, request) {
						permissions.each(function(permission, i) {
							usersIds.push(permission.aro_foreign_key);
						});
						// ask the plugin to encrypt the secrets.
						// When the secrets are encrypted the addon will send back the event secret_edition_secret_encrypted.
						mad.bus.trigger('passbolt.secret_edition.encrypt', usersIds);
					});
				} else {
					usersIds.push(mad.Config.read('user.id'));
					// ask the plugin to encrypt the secrets.
					// When the secrets are encrypted the addon will send back the event secret_edition_secret_encrypted.
					mad.bus.trigger('passbolt.secret_edition.encrypt', usersIds);
				}
			}
			else {
				// Data are not valid
				// if an error callback is given, call it
				if (this.options.callbacks.error) {
					this.options.callbacks.error();
				}
			}
		},

		/**
		 * Listen when the plugin has encrypted the secrets.
		 * @todo #security #architecture refactor, check also permissionController.
		 */
		'{mad.bus} secret_edition_secret_encrypted': function(el, ev, armoreds) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			// @todo #BUG #JMVC The event is not unbound when the element is destroyed. Check that point when updating to canJS.
			if (!this.element) return;

			var data = this.getData();
			data['passbolt.model.Resource'].Secret = [];

			for (var userId in armoreds) {
				data['passbolt.model.Resource'].Secret.push({
					'user_id': userId,
					'data': armoreds[userId]
				});
			}

			// if a submit callback is given, call it
			if (this.options.callbacks.submit) {
				this.options.callbacks.submit(data);
			}
		}

	});
});
