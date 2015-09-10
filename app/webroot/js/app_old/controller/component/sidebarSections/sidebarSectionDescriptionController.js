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
		 * Creates a new Sidebar Section Description Controller.
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
			 * before start hook.
			 * @return {void}
			 */
			'beforeRender': function () {
				this._super();
				// pass the new resource to the view
				this.setViewData('resource', this.options.resource);
				this.setViewData('editable', passbolt.model.Permission.isAllowedTo(this.options.resource, passbolt.UPDATE));
			},

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
                    'state': 'hidden',
					'data': {
						'Resource': this.options.resource
					},
					'callbacks': {
						'submit': function (data) {
                            self.options.resource.update({
                                'description': data['passbolt.model.Resource']['description']
                            }, function () {
                                // No callback required, the parent controller will refresh itself,
                                // and its children components while the current resource is
                                // updated with success.
                            });
						}
					}
				}).start();
			},

            /**
             * Observe when the user want to edit the instance's resource description
             * @param {HTMLElement} el The element
             * @param {HTMLEvent} ev The event which occured
             * @return {void}
             */
            ' request_resource_description_edit' : function(el, ev) {
                if(!this.state.is('edit')) {
                    this.setState('edit');
                }
                else {
                    this.setState('ready');
                }
            },

            /* ************************************************************** */
            /* LISTEN TO THE STATE CHANGES */
            /* ************************************************************** */

			/**
			 * Switch to edit mode
			 * @param {boolean} go Go or leave the state
			 */
			'stateEdit': function(go) {
				if (go) {
					this.options.editDescriptionFormCtrl.setState('ready');
					this.view.showDescription(false);
				}
				else {
					this.options.editDescriptionFormCtrl.setState('hidden');
					this.view.showDescription(true);
				}
			}

		});

	});