steal(
	MAD_ROOT + '/view/component/tree.js'
).then(function ($) {

	/*
	 * @class mad.view.component.tree.List
	 * @inherits mad.view.component.Tree
	 * 
	 * Our representation of the list tree view
	 * 
	 * @constructor
	 * Instanciate a new list view
	 * @return {mad.view.component.tree.List}
	 */
	mad.view.component.Tree.extend('mad.view.component.tree.List', /** @static */ { }, /** @prototype */ {

		/**
		 * Insert a node in the tree
		 * @param {mixed} jsonNode The node to insert
		 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
		 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
		 * @return {JQuery} The created node
		 */
		'insertNode': function (jsonNode, position, ref) {
			position = (typeof position != 'undefined') ? position : 'last';
			ref = (typeof ref != 'undefined') ? ref : this.element;
			//var node = this.jstreeInstance.create_node(ref, position, jsonNode);
			var $child = $('<li id="' + jsonNode.id + '">' + jsonNode.label + '</li>').appendTo(ref);

			for (var i in jsonNode.children) {
				var $children = $('<ul/>').appendTo($child);
				this.insertNode(jsonNode.children[i], 'last', $children);
			}
		},

		/**
		 * Render the jstree component
		 * @return {void}
		 */
		'render': function () {
			var self = this;
			this._super();

			this.element.jstree({
				"json_data": {
					"data": []
				},
				"plugins": ["json_data", "ui"]

			});
			this.jstreeInstance = $.jstree._reference(this.controller.getId());

			// pas propre mode, mais on deroule

			// change the component status in ready
			this.status = 'ready';
			// @todo ca craint ce changement de status a la main comme ca, a voir
			
			// bind jstree events 
			// (this kind of wrinting event.eventcomplement is not supported by JMVC)
			this.element.bind('select_node.jstree', function (event, data) {
				var itemId = data.rslt.obj.attr("id");
				self.itemSelected(itemId, data.rslt.obj);
			});
		},
		
		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'li click': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			this.itemSelected(element[0].id, element);
		},

		/**
		 * An item has been selected
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'li contextmenu': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			if(event.which == 3){
				this.itemRightSelected(element[0].id, element, event);
			}
			return false;
		},

		/**
		 * An item has been hovered
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'li hover': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			this.itemHovered(element[0].id, element);
			return false;
		}

	});
});