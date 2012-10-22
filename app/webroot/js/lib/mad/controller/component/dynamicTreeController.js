steal(
	'jquery/controller',
	MAD_ROOT + '/controller/component/treeController.js',
	MAD_ROOT + '/view/component/tree/dynamicTree.js',
	MAD_ROOT + '/object/map.js'
).then(function ($) {

	/*
	 * @class mad.controller.component.DynamicTreeController
	 * @inherits mad.controllerTreeController
	 * @parent mad.controller.component
	 * @see mad.view.component.DynamicTree
	 * 
	 * The Dynamic Tree class Controller is our implementation of the dynamic tree
	 * component ( such as jstree )
	 * 
	 * @constructor
	 * Creates a new Dynamic Tree Controller Component
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @param {mad.object.Map} options.map The mapping object used to map data from the JMVC
	 * model to the understood format.
	 * @return {mad.controller.component.DynamicTreeController}
	 */
	mad.controller.component.TreeController.extend('mad.controller.component.DynamicTreeController', /** @static */	{

		'defaults': {
			'label': 'Dynamix Tree Component',
			'viewClass': mad.view.component.tree.DynamicTree,
			'templateUri': '//' + MAD_ROOT + '/view/template/component/tree.ejs',
			'itemTemplateUri': '//' + MAD_ROOT + '/view/template/component/tree/dynamicTree.ejs',
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
			'item_hovered',
			'item_opened',
			'item_closed'
		]

	}, /** @prototype */ {

		/**
		 * Open an item
		 * @param {string} itemId The target item to open
		 * @return {void}
		 */
		'open': function (itemId) {
			this.view.open(itemId);
		},

		/**
		 * Close an item
		 * @param {string} itemId The target item to close
		 * @return {void}
		 */
		'close': function (itemId) {
			this.view.close(itemId);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been uncollapsed
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_opened': function (element, event, itemId) {
			this.open(itemId);
		},

		/**
		 * An item has been collapsed
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_closed': function (element, event, itemId) {
			this.close(itemId);
		}

	});

});