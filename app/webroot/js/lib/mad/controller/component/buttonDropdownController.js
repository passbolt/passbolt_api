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
			'viewClass': mad.view.component.ButtonDropdown,
			// The menu which is piloted by the component.
			'menu' : null,
			// The menu items.
			'items': null,
			// Customize the element which will carry the dropdown content
			'contentElement': null
		}

	}, /** @prototype */ {

		'afterStart': function() {
			var $dropdownElement = null;

			// @todo This container should be inserted following a different way
			// If the dropdown content element hasn't been customized, inject one in DOM.
			if (this.options.contentElement == null) {
				$dropdownElement = $('<ul class="dropdown-content"></div>').insertAfter(this.element);
			}
			else {
				$dropdownElement = $(this.options.contentElement);
			}

			// Create and render dropdown content.
			this.options.menu = new mad.controller.component.MenuController($dropdownElement);
			this.options.menu.start();
			this.options.menu.load(this.options.items);
		},

		/**
		 * Set the item state.
		 * @param id The item id.
		 * @param stateName The state to set.
		 */
		'setItemState': function(id, stateName) {
			this.options.menu.setItemState(id, stateName);
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