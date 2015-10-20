steal(
	'mad/controller/component/dynamicTreeController.js',
	'app/view/component/categoryChooser.js',
	'app/model/category.js',
	'app/model/categoryResource.js'
).then(function () {

	/*
	 * @class passbolt.controller.CategoryChooserController
	 * @inherits mad.controller.component.DynamicTreeController
	 * @parent index
	 *
	 * Our category chooser component.
	 * It will allow the user to select a category.
	 *
	 * @constructor
	 * Creates a new Category Chooser Controller.
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {passbolt.controller.CategoryChooserController}
	 */
	mad.controller.component.DynamicTreeController.extend('passbolt.controller.component.CategoryChooserController', /** @static */ {

		'defaults': {
			'label': 'Category Chooser',
			'viewClass': passbolt.view.component.categoryChooser,
			'itemClass': passbolt.model.Category,
			'templateUri': 'mad/view/template/component/tree.ejs',
			// The map to use to make jstree working with our category model
			'map': new mad.object.Map({
				'id': 'id',
				'label': 'name',
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			})
		}

	}, /** @prototype */ {

		/**
		 * Called right after the start function
		 * @return {void}
		 * @see {mad.controller.ComponentController}
		 */
		'afterStart': function() {
			var self = this;

			// Load categories.
			this.setState('loading');
			passbolt.model.Category.findAll({
				'children': true
			}, function (categories, response, request) {
				// load the tree with the categories
				self.load(categories);
				self.setState('ready');
			}, function (response) { });
		},

		/**
		 * Show the contextual menu
		 * @param {passbolt.model.Category} item The item to show the contextual menu for
		 * @param {string} x The x position where the menu will be rendered
		 * @param {string} y The y position where the menu will be rendered
		 * @return {void}
		 */
		'showContextualMenu': function (item, x, y) {
			// Get the offset position of the clicked item.
			var $item = $('a span', '#' + item.id);
			var item_offset = $item.offset();

			// Instantiate the contextual menu menu.
			var contextualMenu = new mad.controller.component.ContextualMenuController(null, {
				'state': 'hidden',
				'source': $item[0],
				'coordinates': {
					x: '180',
					y: item_offset.top
				}
			});
			contextualMenu.start();

			// get the permission on the category.
			var canCreate = passbolt.model.Permission.isAllowedTo(item, passbolt.CREATE),
				canUpdate = passbolt.model.Permission.isAllowedTo(item, passbolt.UPDATE),
				canAdmin = passbolt.model.Permission.isAllowedTo(item, passbolt.ADMIN);

			// Add open action.
			var action = new mad.model.Action({
				'id': uuid(),
				'label': 'Open',
				'cssClasses': ['separator-after'],
				'action': function (menu) {
					mad.bus.trigger('category_selected', item);
					menu.remove();
				}
			});
			contextualMenu.insertItem(action);
			// Add Create resource action.
			action = new mad.model.Action({
				'id': uuid(),
				'label': 'Create resource',
				'initial_state': !canCreate ? 'disabled' : 'ready',
				'action': function (menu) {
					mad.bus.trigger('request_resource_creation', item);
					menu.remove();
				}
			});
			contextualMenu.insertItem(action);
			// Add Create category action.
			action = new mad.model.Action({
				'id': uuid(),
				'label': 'Create category',
				'cssClasses': ['separator-after'],
				'initial_state': !canCreate ? 'disabled' : 'ready',
				'action': function (menu) {
					mad.bus.trigger('request_category_creation', item);
					menu.remove();
				}
			});
			contextualMenu.insertItem(action);
			// Add Rename action.
			action = new mad.model.Action({
				'id': uuid(),
				'label': 'Rename...',
				'initial_state': !canUpdate ? 'disabled' : 'ready',
				'action': function (menu) {
					mad.bus.trigger('request_category_edition', item);
					menu.remove();
				}
			});
			contextualMenu.insertItem(action);
			// Add Share action.
			action = new mad.model.Action({
				'id': uuid(),
				'label': 'Share',
				'initial_state': !canAdmin ? 'disabled' : 'ready',
				'action': function (menu) {
					mad.bus.trigger('request_category_sharing', item);
					menu.remove();
				}
			});
			contextualMenu.insertItem(action);
			// Add Remove action.
			action = new mad.model.Action({
				'id': uuid(),
				'label': 'Remove',
				'initial_state': !canUpdate ? 'disabled' : 'ready',
				'action': function (menu) {
					mad.bus.trigger('request_category_deletion', item);
					menu.remove();
				}
			});
			contextualMenu.insertItem(action);

			// Display the menu.
			contextualMenu.setState('ready');
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} item The selected item instance or its id
		 * @param {HTMLEvent} srcEvent The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			mad.bus.trigger('category_selected', item);
		},

		/**
		 * A category has been dropped over
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {jQuery.Drop} drop The drop object
		 * @param {jQuery.Drag} drag The drag object
		 * @param {HTMLEvent} srcEvent
		 */
		' category_dropover': function(el, ev, drop, drag, srcEvent) {

		},

		/**
		 * A category has been dropped out
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {jQuery.Drop} drop The drop object
		 * @param {jQuery.Drag} drag The drag object
		 * @param {HTMLEvent} srcEvent
		 */
		' category_dropout': function(el, ev, drop, drag, srcEvent) {

		},

		/**
		 * A resource has been dragged and dropped on a category
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {jQuery.Drop} drop The drop object
		 * @param {jQuery.Drag} drag The drag object
		 * @param {HTMLEvent} srcEvent
		 */
		' category_dropon': function(el, ev, drop, drag, srcEvent) {
			var categoryId = drop.element.parent().attr("id");
			var resourceId = drag.element.attr("id");
			new passbolt.model.CategoryResource({
					category_id : categoryId,
					resource_id : resourceId
				})
				.save();
			// TODO : get result and show notification
		},

		/**
		 * An item has been right selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} item The right selected item instance or its id
		 * @param {HTMLEvent} srcEvent The source event which occured
		 * @return {void}
		 */
		' item_right_selected': function (el, ev, item, srcEvent) {
			this._super(el, ev, item, srcEvent);
			this.showContextualMenu(item, srcEvent.pageX, srcEvent.pageY);
		},

		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when a category is created
		 * @param {HTMLElement} el The el the event occured on
		 * @param {HTMLEvent} ev The ev which occured
		 * @param {mad.model.Model} category The inserted category
		 * @return {void}
		 */
		'{passbolt.model.Category} created': function (el, ev, category) {
			this.insertItem(category, category.parent_id, 'last');
		},

		/**
		 * Observe when a category is destroyed
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The ev which occured
		 * @param {mad.model.Model} category The inserted category
		 * @return {void}
		 */
		'{passbolt.model.Category} destroyed': function (model, ev, category) {
			this.removeItem(category);
		},

		/**
		 * Observe when a category is updated.
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The updated category
		 * @return {void}
		 */
		'{passbolt.model.Category} updated': function (model, ev, category) {
			this.refreshItem(category);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the browser filter
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {passbolt.model.Filter} filter The filter to apply
		 * @return {void}
		 */
		'{mad.bus} filter_resources_browser': function (element, evt, filter) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			if (filter.type != passbolt.model.Filter.FOREIGN_MODEL) {
				this.unselectAll();
			}
			else {
				var categories = filter.getForeignModels('Category');
				this.selectItem(categories[0]);
			}
		}
	});
});
