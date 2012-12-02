steal(
	'mad/controller/component/dynamicTreeController.js'
).then(function () {

	/*
	 * @class passbolt.controller.CategoryChooserController
	 * @inherits mad.controller.component.TreeController
	 * @parent index 
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
			'viewClass': mad.view.component.tree.List,
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
		 * Show the contextual menu
		 * @param {passbolt.model.Category} item The item to show the contextual menu for
		 * @param {string} x The x position where the menu will be rendered
		 * @param {string} y The y position where the menu will be rendered
		 * @return {void}
		 */
		'showContextualMenu': function (item, x, y) {
			var menuItems = mad.model.Action.models([
				{ 'id': uuid(), 'label': 'open',
					'action': function (menu) {
						mad.bus.trigger('category_selected', item);
						menu.remove();
					}},
				{ 'id': uuid(), 'label': 'create',
					'action': function (menu) {
						console.log('Menu Create');
					},
					'children': [
						{ 'id': uuid(), 'label': 'secret',
							'action': function (menu) {
								mad.bus.trigger('request_resource_creation', item);
								menu.remove();
							}},
						{ 'id': uuid(), 'label': 'category',
							'action': function (menu) {
								mad.bus.trigger('request_category_creation', item);
								menu.remove();
							}}
					]},
				{ 'id': uuid(), 'label': 'rename...',
					'action': function (menu) {
						mad.bus.trigger('category_renamed', item);
						menu.remove();
					}},
				{ 'id': uuid(), 'label': 'remove',
					'action': function (menu) {
						mad.bus.trigger('request_category_deletion', item);
						menu.remove();
					}}
			]);

			// Instanciate the menu controller
			// @todo An html helper to insert component should be solve the view problem is this code part
			if ($('#js_category_chooser_contextual_menu').length) {
				$('#js_category_chooser_contextual_menu').remove();
			}

			var $menu = $('<ul id="js_category_chooser_contextual_menu" class="contextual_menu"/>');
			mad.app.element.prepend($menu);
			var contextualMenu = new mad.controller.component.ContextualMenuController($menu, {
				'mouseX': x,
				'mouseY': y
			});
			contextualMenu.render();
			contextualMenu.load(menuItems);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} item The selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			mad.bus.trigger('category_selected', item);
		},

		/**
		 * An item has been right selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} item The right selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_right_selected': function (el, ev, item, srcEvent) {
			this._super(el, event, ev);
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

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the application is ready and load the tree with the roots
		 * categories
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'{mad.bus} app_ready': function (ui, event) {
			var self = this;
			// load categories function of the selected database
			this.setState('loading');
			passbolt.model.Category.findAll({
				'children': true
			}, function (categories, response, request) {
				// load the tree with the categories
				self.load(categories);
				self.setState('ready');
			}, function (response) { });
		}

	});

});