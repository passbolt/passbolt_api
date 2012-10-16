steal(
	MAD_ROOT + '/view/component/tree/jstree.js'
).then(function ($) {

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
	mad.controller.component.TreeController.extend('passbolt.controller.component.CategoryChooserController', /** @static */ {

		'defaults': {
			'label': 'Category Chooser',
			'viewClass': mad.view.component.tree.Jstree,
			'templateUri': '//' + MAD_ROOT + '/view/template/component/tree.ejs',
			// The map to use to make jstree working with our category model
			'map': new mad.object.Map({
				'attr.id': 'Category.id',
				'data': 'Category.name',
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			})
		}

	}, /** @prototype */ {

		/**
		 * Show the contextual menu
		 * @param {string} itemId The identifier of the selected item
		 * @param {string} x The x position where the menu will be rendered
		 * @param {string} y The y position where the menu will be rendered
		 * @return {void}
		 */
		'showContextualMenu': function (itemId, x, y) {
			var menuItems = [
				{ 'MenuItem': new mad.model.MenuItem({
					'id': uuid(),
					'label': 'open',
					'action': function () {
						passbolt.eventBus.trigger('category_selected', {'id': itemId});
					}
				}) }, { 'MenuItem': new mad.model.MenuItem({
					'id': uuid(),
					'label': 'create',
					'action': function () {
						console.log('Menu Create');
					}
				}), 'children': [
					{ 'MenuItem': new mad.model.MenuItem({
						'id': uuid(),
						'label': 'secret',
						'action': function () {
							passbolt.eventBus.trigger('request_resource_creation', {'categoryId': itemId});
						}
					}) }, { 'MenuItem': new mad.model.MenuItem({
						'id': uuid(),
						'label': 'category',
						'action': function () {
							passbolt.eventBus.trigger('request_category_creation', {'id': itemId});
						}
					})
				}]
			}];

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
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @param {Event} srcEvent The jQuery source event
		 * @return {void}
		 */
		'item_selected': function (element, event, itemId, srcEvent) {
			passbolt.eventBus.trigger('category_selected', {
				'id': itemId
			});
		},

		/**
		 * An item has been right selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @param {Event} srcEvent The jQuery source event
		 * @return {void}
		 */
		'item_right_selected': function (element, event, itemId, srcEvent) {
			this._super(element, event, itemId);
			this.showContextualMenu(itemId, srcEvent.pageX, srcEvent.pageY);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when a category is inserted
		 * 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.model.Model} category The inserted category
		 * @return {void}
		 */
		'{passbolt.eventBus} category_created': function (el, event, category) {
			this.insertItem(category, category.Category.parent_id, 'last');
		},

		/**
		 * Observe when the application is ready and load the tree with the roots
		 * categories
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'{passbolt.eventBus} app_ready': function (ui, event) {
			var self = this;
			//load categories function of the selected database
			this.setState('loading');
			passbolt.model.Category.getRoots({
				children: false
			}, function (request, response, categories) {
				// load the tree with the categories
				self.load(categories);
				self.setState('ready');
			});
		}

	});

});