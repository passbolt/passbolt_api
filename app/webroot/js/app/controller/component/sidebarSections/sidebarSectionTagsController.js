steal(
	'app/view/component/sidebarSection/sidebarSectionTags.js',
	'app/controller/component/sidebarSectionController.js',
	'app/controller/component/tagsController.js'
).then(function () {

		/*
		 * @class passbolt.controller.SidebarSectionTagsController
		 * @inherits mad.controller.component.SidebarSectionController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Sidebar Section Tags Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.SidebarSectionTagsController}
		 */
		passbolt.controller.component.SidebarSectionController.extend('passbolt.controller.component.SidebarSection.SidebarSectionTagsController', /** @static */ {

			'defaults': {
				'label': 'Sidebar Section Tags Controller',
				'viewClass': passbolt.view.component.SidebarSection.SidebarSectionTags,
				// the resource to bind the component on
				'resource': null
			}

		}, /** @prototype */ {

			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			/**
			 * Hook After Start
			 * Will basically launch a generic tagsController
			 */
			'afterStart': function () {
				this.myState = 'ready';
				var self = this;

				// Instantiate the comments List controller
				// It will take care of listing the comments
				this.tagsController = new passbolt.controller.component.TagsController($('#js_rs_details_tags_wrapper', this.element), {
					'resource'			: this.options.resource,
					'foreignModel'		: this.options.foreignModel,
					'foreignId'			: this.options.foreignId,
					'wrapperController' : self
				});
				this.tagsController.start();
			},

			/**
			 * Toggle the edit mode
			 */
            'toggleEdit': function() {
				if(this.myState == 'ready') {
					this.myState = "edit";
					this.setState('edit');

				}
				else{
					this.myState = "ready";
					this.setState('ready');
				}
            },

			/**
			 * State edit catcher
			 */
			'stateEdit':function() {
				this.tagsController.setState("edit");
			},

			/**
			 * State ready catcher
			 */
			'stateReady':function() {
				this.tagsController.setState("ready");
			}

		});

	});