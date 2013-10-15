steal(
	'app/controller/component/tagsListController.js'
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
		mad.controller.ComponentController.extend('passbolt.controller.component.TagsController', /** @static */ {

			'defaults': {
				'label': 'Tags Controller',
				//'viewClass': passbolt.view.component.SidebarSection.SidebarSectionTags,
				// the resource to bind the component on
				'resource'		: this.options.resource,
				'foreignModel' 	: null,
				'foreignId' 	: null
			}

		}, /** @prototype */ {



			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			'afterStart': function () {
				// Instantiate the comments List controller
				// It will take care of listing the comments
				this.tagsListController = new passbolt.controller.component.TagsListController($('ul.tags', this.element), {
					'resource'		: this.options.resource,
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				});
				this.tagsListController.start();
			}

		});

	});