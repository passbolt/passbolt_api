steal(
	MAD_ROOT + '/view'
).then(function ($) {

	/*
	 * @class mad.view.component.Tree
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('mad.view.component.Tree', /** @static */ {

	}, /** @prototype */ {

		/**
		 * The map to transform JMVC model object into jstree node
		 * @type {mad.object.Map}
		 */
		'map': null,

		// Constructor like
		'init': function (controller, options) {
			this._super(controller, options);
		},

		/**
		 * Insert a node in the tree
		 * @param {mixed} jsonNode The node to insert
		 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
		 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
		 * @throw mad.error.CallAbstractFunction
		 * @return {JQuery} The created node
		 */
		'insertNode': function (jsonNode, position, ref) {
			throw new mad.error.CallAbstractFunction();
		},

		/**
		 * An item has been selected
		 * @event item_selected
		 * @param {string} itemId The right selected item id
		 * @param {HTMLElement} element The element the event occured on
		 * @return {void}
		 */
		'itemSelected': function (itemId, element, srcEvent) {
			this.element.trigger('item_selected', [itemId, srcEvent]);
		},

		/**
		 * An item has been right selected
		 * @event item_right_selected
		 * @param {string} itemId The right selected item id
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} srcEvent The jQuery source event
		 * @return {void}
		 */
		'itemRightSelected': function (itemId, element, srcEvent) {
			element.trigger('item_right_selected', [itemId, srcEvent]);
		},

		/**
		 * An item has been hovered
		 * @event item_hovered
		 * @param {string} itemId The right selected item id
		 * @param {HTMLElement} element The element the event occured on
		 * @return {void}
		 */
		'itemHovered': function (itemId, element, srcEvent) {
			this.element.trigger('item_hovered', [itemId, srcEvent]);
		},

		/**
		 * Load the tree with an additionnal node at the specific position (ref + position)
		 * @param {object|array} data The data which represent the node
		 * @todo the mapping could be done in the view ?
		 */
		'load': function (data, position, ref) {
			var mappedData;

			if ($.isArray(data)) {
				returnValue = [];
				// map the jmvc model objects into the desired format
				mappedData = this.map.mapObjects(data);
				for(var i in mappedData) {
					returnValue.push(this.insertNode(mappedData[i]));
				}
			} else {
				// map the jmvc model objects into the desired format
				mappedData = mad.object.Map.mapObject(data, this.map);
				returnValue = this.insertNode(mappedData);
			}

			return returnValue;
		},

		/**
		 * Render the jstree component
		 * @return {void}
		 */
		'render': function () {
			this._super();
		}
		
		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

	});
});