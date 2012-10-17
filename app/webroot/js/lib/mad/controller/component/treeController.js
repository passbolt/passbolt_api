steal(
	'jquery/controller',
	MAD_ROOT + '/controller/componentController.js',
	MAD_ROOT + '/view/component/tree/list.js',
	MAD_ROOT + '/object/map.js'
).then(function ($) {

	/*
	 * @class mad.controller.component.TreeController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * @see mad.view.component.Tree
	 * 
	 * The Tree class Controller is our implementation of the UI component tree.
	 * The common view works upon the jstree plugin.
	 * 
	 * @constructor
	 * Creates a new Tree Controller Component
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @param {mad.object.Map} options.map The mapping object used to map data from the JMVC
	 * model to the understood format.
	 * @return {mad.controller.component.TreeController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.TreeController', /** @static */	{

		'defaults': {
			'label': 'Tree Component',
			'viewClass': mad.view.component.tree.List,
			'templateUri': '//' + MAD_ROOT + '/view/template/component/tree.ejs',
			'map': null,
			'callbacks': {
				'item_selected': null,
				'item_right_selected': null,
				'item_hovered': null
			}
		},

		// listen to the following custom view event
		'listensTo': [
			'item_selected',
			'item_right_selected',
			'item_hovered'
		]

	}, /** @prototype */ {

		// Construcor
		'init': function (el, options) {
			this._super(el, options);

			// Store the map which will be used to map JMVC Model objects into jstree node objects
			// This parameter is mandatory
			if (typeof this.options.map == 'undefined') {
				throw new mad.error.MissingOption('map', 'mad.controller.component.TreeController');
			}

			// @todo find a way to manage the view variables/options
			this.view.map = this.options.map;
		},

		/**
		 * Load the tree with an additionnal node at the specific position (ref + position)
		 * @param {object|array} data The data which represent the node
		 * @return {void}
		 */
		'load': function (data) {
			var returnValue = null;
			this.state.data = data;
			this.view.load(this.state.data);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_selected': function (element, event, itemId) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemSelected) {
				this.options.callbacks.itemSelected(element, event, itemId);
			}
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
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemRightSelected) {
				this.options.callbacks.itemRightSelected(element, event, itemId, srcEvent);
			}
		},

		/**
		 * An item has been hovered
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_hovered': function (element, event, itemId) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemHovered) {
				this.options.callbacks.itemHovered(element, event, itemId);
			}
		}

	});

});