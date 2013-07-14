steal(
	'jquery/controller',
	'mad/controller/componentController.js',
	'mad/view/component/tree.js',
	'mad/object/map.js',
	'mad/error/exception.js',
	'mad/view/template/component/tree.ejs',
	'mad/view/template/component/tree/treeItem.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.TreeController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * @see mad.view.component.Tree
	 * 
	 * The Tree class Controller is our implementation of the UI component tree.
	 * The common view works upon the jstree plugin.
	 * 
	 * ## Example
	 * @demo lib/mad/demo/controller/component/tree_controller.html
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
			'viewClass': mad.view.component.Tree,
			'templateUri': 'mad/view/template/component/tree.ejs',
			'itemTemplateUri': 'mad/view/template/component/tree/treeItem.ejs',
			'cssClasses': ['tree'],

			// the itemClass which represents the items managed by the component
			'itemClass': null,
			// the associated map, which will be used to map the model data to the
		  	// expected view format
			'map': null,
			// the top tag of the grid
			'tag': 'ul',
			// callbacks associated to the events which could occured
			'callbacks': {
				'item_selected': null,
				'item_right_selected': null,
				'item_hovered': null
			}
		}

	}, /** @prototype */ {

		/**
		 * Insert an item in the tree
		 * @param {mad.model.Model} item The item to insert
		 * @param {string} refItemId The reference item id. By default the grid view object
		 * will choose the root as reference element.
		 * @param {string} position The position of the newly created item. You can pass in one
		 * of those strings: "before", "after", "inside", "first", "last". By dhe default value 
		 * is set to last.
		 * @return {void}
		 */
		'insertItem': function (item, refItemId, position) {
			if (this.getItemClass() == null) {
				throw new mad.error.Exception('The associated itemClass can not be null');
			}
			if (!(item instanceof this.getItemClass())) {
				throw new mad.error.WrongParametersException('item', this.getItemClass().fullName);
			}
			this.view.insertItem(item, refItemId, position);
		},

		/**
		 * Remove an item from the tree
		 * @param {mad.model.Model} item The target item to remove
		 * @return {void}
		 */
		'removeItem': function (item) {
			this.view.removeItem(item);
		},

		/**
		 * Reset the component by removing all items
		 * @return {void}
		 */
		'reset': function () {
			this.view.reset();
		},

		/**
		 * Load the tree with the given items
		 * @param {mad.model.Model.List} items The list of items to load into the tree
		 * @return {void}
		 */
		'load': function (items) {
			var self = this;
			can.each(items, function (item, i) {
				self.insertItem(item);
			});
		},

		/**
		 * Get the itemClass which represents the items managed by the component
		 * @return {mad.model.Model}
		 */
		'getItemClass': function () {
			return this.options.itemClass;
		},

		/**
		 * Set the itemClass which represents the items managed by the component
		 * @param {mad.model.Model} itemClass The item class
		 * @return {void}
		 */
		'setItemClass': function (itemClass) {
			this.options.itemClass = itemClass;
		},

		/**
		 * Get the associated map, which will be used to map the model data to the
		 * expected view format
		 * @return {mad.object.Map}
		 */
		'getMap': function (map) {
			return this.options.map;
		},

		/**
		 * Set the associated map, which will be used to map the model data to the
		 * expected view format
		 * @param {mad.object.Map} map The map
		 * @return {void}
		 */
		'setMap': function (map) {
			this.options.map = map;
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemSelected) {
				this.options.callbacks.itemSelected(el, ev, item, srcEvent);
			}
		},

		/**
		 * An item has been right selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The right selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_right_selected': function (el, ev, item, srcEvent) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemRightSelected) {
				this.options.callbacks.itemRightSelected(el, ev, item, srcEvent);
			}
		},

		/**
		 * An item has been hovered
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The hovered item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_hovered': function (el, ev, item, srcEvent) {
			// override this function, call _super if you want the default behavior processed
			if (this.options.callbacks.itemHovered) {
				this.options.callbacks.itemHovered(el, ev, item, srcEvent);
			}
		}

	});

});