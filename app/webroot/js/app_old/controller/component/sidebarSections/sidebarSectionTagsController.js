steal(
	'app/view/component/sidebarSection/sidebarSectionTags.js',
	'app/controller/component/sidebarSectionController.js',
	'app/controller/component/tagsController.js'
).then(function () {
	/*
	 * @class passbolt.controller.sidebarSection.SidebarSectionTagsController
	 * @inherits mad.controller.component.SidebarSectionController
	 * @parent index
	 *
	 * @constructor
	 * Creates a new Sidebar Section Tags Controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {passbolt.controller.sidebarSection.SidebarSectionTagsController}
	 */
	passbolt.controller.component.SidebarSectionController.extend('passbolt.controller.component.sidebarSection.SidebarSectionTagsController', /** @static */ {

		'defaults': {
			'label': 'Sidebar Section Tags Controller',
			'viewClass': passbolt.view.component.sidebarSection.SidebarSectionTags,
			// the instance to bind the component on
			'instance': null,
			// sub-component tags controller
			'tagsController': null
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
			var self = this;

			// If the instance has no tag associated to it for now, and the server doesn't return any tags, the instance.ItemTag attribute will be undefined, fix this missing here for now
			if (typeof this.options.instance.ItemTag == 'undefined') {
				this.options.instance.ItemTag = passbolt.model.ItemTag.models();
			}

			// Instantiate the comments List controller
			// It will take care of listing the item tags.
			this.options.tagsController = new passbolt.controller.component.TagsController($('#js_rs_details_tags_wrapper', this.element), {
				'resource': this.options.resource,
				'instance': this.options.instance,
				'foreignModel': this.options.foreignModel,
				'foreignId': this.options.foreignId
			}).start();

			this.on();
		},

		/* ************************************************************** */
		/* LISTEN TO VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user want to edit the instance's tags list
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' request_tags_edit': function (el, ev) {
			this.setState('edit');
		},

		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when item tags list are updated onto the observed instance
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.ItemTag} itemTags The added item tags
		 * @return {void}
		 */
		'{instance.ItemTag} change': function (el, ev) {
			// If the instance is not tagged yet,
			if (this.options.instance.ItemTag != null && this.options.instance.ItemTag.length > 0) {
				this.setState('ready');
			} else {
				this.setState('edit');
			}
		},

		/* ************************************************************** */
		/* LISTEN TO STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Switch the tags component controllers to the ready mode
		 * @param {boolean} go Go or leave the state
		 */
		'stateReady': function (go) {
			var canUpdate = passbolt.model.Permission.isAllowedTo(this.options.instance, passbolt.UPDATE);
			if (go) {
				if (canUpdate) {
					$('.edit-action', this.element).show();
				}
			}
		},

		/**
		 * Switch the tags component controllers to the edit mode
		 * @param {boolean} go Go or leave the state
		 */
		'stateEdit': function (go) {
			if (go) {
				// Hide edit button
				$('.edit-action', this.element).hide();
				this.options.tagsController.setState('edit');
			} else {
				// Hide edit button
				$('.edit-action', this.element).show();
			}
		}

	});

});