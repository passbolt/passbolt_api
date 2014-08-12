steal(
	'mad/controller/component/buttonController.js',
	'mad/view/component/buttonDropdown.js',
	'mad/view/template/component/buttonDropdown.ejs'
).then(function () {

		/*
		 * @class mad.controller.component.ButtonDropdownController
		 * @inherits mad.controller.ButtonController
		 * @parent mad.controller.component
		 *
		 * The Button Dropdown class Controller is our implementation of the UI component Drop down button.
		 *
		 * @constructor
		 * Creates a new Button Drop down Controller Component
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {mad.controller.component.ButtonDropdownController}
		 */
		mad.controller.component.ButtonController.extend('mad.controller.component.ButtonDropdownController', /** @static */ {

			'defaults': {
				'label': 'Button Dropdown Component',
				'templateUri': 'mad/view/template/component/buttonDropdown.ejs',
				'templateBased': false,
				'viewClass': mad.view.component.ButtonDropdown,
				'value': null,
				'events': {
					'click': null
				},
				'tag': 'buttonMenu',
				// Menu Items inside the menu.
				'menuItems': null,
				'menu' : null
			}

		}, /** @prototype */ {

			'afterStart': function() {
				// Define id of container for menuItems.
				var menuItemsId = 'mb-ctn-' + uuid();
				// Inject container in dom.
				$('<ul id="' + menuItemsId + '" class="dropdown-content"></div>').insertAfter(this.element);
				// Create and render menu in the created container.
				this.options.menu = new mad.controller.component.MenuController('#' + menuItemsId);
				this.options.menu.start();
				this.options.menu.load(this.options.menuItems);
			},

			/**
			 * Set a menu item active status.
			 * @param string name the name of the item.
			 * @param bool active, the active status.
			 */
			'setItemActive': function(name, active) {
				for (i in this.options.menuItems) {
					if (this.options.menuItems[i].name == name) {
						this.options.menuItems[i].active = active;
					}
				}
				this.options.menu.reset();
				this.options.menu.load(this.options.menuItems);
			},

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */

			/**
			 * Listen to the change relative to the state Disabled
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'stateDisabled': function (go) {
				this._super(go);
				if (go) {
					this.view.close();
				}
			}
		});

	});