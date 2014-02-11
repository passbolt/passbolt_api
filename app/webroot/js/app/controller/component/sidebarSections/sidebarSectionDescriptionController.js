steal(
	'app/controller/component/sidebarSectionController.js',
	'app/controller/form/resource/editDescriptionFormController.js',
	'app/view/component/sidebarSection/sidebarSectionDescription.js',
	'app/model/resource.js'

).then(function () {
		/*
		 * @class passbolt.controller.sidebarSection.SidebarSectionDescriptionController
		 * @inherits mad.controller.component.SidebarSectionController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Sidebar Section Description Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.sidebarSection.SidebarSectionDescriptionController}
		 */
		passbolt.controller.component.SidebarSectionController.extend('passbolt.controller.component.sidebarSection.SidebarSectionDescriptionController', /** @static */ {

			'defaults' : {
				'label' : 'Sidebar Section Description Controller',
				'viewClass' : passbolt.view.component.sidebarSection.SidebarSectionDescription,
				// Current resource instance.
				'resource' : null,
				// Form to edit the description.
				'editDescriptionFormCtrl' : null
			}

		}, /** @prototype */ {

			/**
			 * Defines whether we are in edition mode or read mode.
			 */
			'edition' : false,

			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			/**
			 * Hook After Start
			 * Will basically instantiate the form to edit the description
			 */
			'afterStart' : function() {
				var self = this;

				// create a form to edit the description
				this.options.editDescriptionFormCtrl = new passbolt.controller.form.resource.EditDescriptionFormController($('#js_rs_details_edit_description', this.element), {
					'templateBased': true,
					'templateUri': 'app/view/template/form/resource/editDescriptionForm.ejs',
					'resource': this.options.resource,
					'data': {
						'Resource': this.options.resource
					},
					'callbacks': {
						'submit': function (data) {
							// TODO : validate
							passbolt.model.Resource.update(
								data['passbolt.model.Resource'].id,
								data['passbolt.model.Resource'],
								function(data) {
									self.setState('ready');
								}
							);
						}
					}
				}).start();
				this.options.editDescriptionFormCtrl.setState("hidden");
			},

			/**
			 * Switch to edit mode
			 * @param {boolean} go Go or leave the state
			 */
			'stateReady': function(go) {
				if (go) {
					this.edition = false;
				}
			},

			/**
			 * Listen to changes in resource and update the view.
			 *
			 * @param {passbolt.model.Resource} resource
			 */
			'{resource} change': function(resource) {
				this.setViewData('resource', resource);
				this.refresh();
			},

			/**
			 * Switch to edit mode
			 * @param {boolean} go Go or leave the state
			 */
			'stateEdit': function(go) {
				if (go) {
					this.options.editDescriptionFormCtrl.setState('ready');
					this.view.showDescription(false);
					this.edition = true;
				}
				else {
					this.options.editDescriptionFormCtrl.setState('hidden');
					this.view.showDescription(true);
					this.edition = false;
				}
			},

			/**
			 * Observe when the user want to edit the instance's resource description
			 * @param {HTMLElement} el The element
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			' request_resource_description_edit' : function(el, ev) {
				if(this.edition == false) {
					this.setState('edit');
				}
				else {
					this.setState('ready');
					this.stateEdit(false);
				}
			}

		});

	});