steal(
	'lib/jstree/jquery.jstree.js',
	MAD_ROOT + '/view/component/tree.js'
).then(function ($) {

	/*
	 * @class mad.view.component.tree.Jstree
	 * @inherits mad.view.component.Tree
	 * 
	 * Our representation of the tree view base upon the jQuery plugin jstree
	 * 
	 * @constructor
	 * Instanciate a new Jstree view
	 * @return {mad.view.component.tree.Jstree}
	 */
	mad.view.component.Tree.extend('mad.view.component.tree.Jstree', /** @static */ { }, /** @prototype */ {

		/**
		 * The jstree instance
		 * @type {jstree}
		 */
		'jstreeInstance': null,

		// Constructor like
		'init': function (controller, options) {
			this._super(controller, options);
			// Jstree themes path is incorrect, set it to the right path
			$.jstree._themes = APP_URL + '/js/lib/jstree/themes/';
		},

		/**
		 * Insert a node in the tree
		 * @param {mixed} jsonNode The node to insert
		 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
		 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
		 * @return {JQuery} The created node
		 */
		'insertNode': function (jsonNode, position, ref) {
			position = position || 'last';
			ref = ref || this.element;
			var node = this.jstreeInstance.create_node(ref, position, jsonNode);
			for (var i in jsonNode.children) {
				this.insertNode(jsonNode.children[i], 'last', node);
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
				"plugins": ["themes", "json_data", "ui"]

			});
			this.jstreeInstance = $.jstree._reference(this.controller.getId());

			// pas propre mode, mais on deroule

			// change the component status in ready
			this.status = 'ready';
			// @todo ca craint ce changement de status a la main comme ca, a voir
			
			// bind jstree events 
			// (this kind of wrinting event.eventcomplement is not supported by JMVC
			// @todo unbind le bazarre dans une fonction destroy
			this.element.bind('select_node.jstree', function (event, data) {
				var itemId = data.rslt.obj.attr("id");
				self.itemSelected(itemId, data.rslt.obj);
			});
			
			// bind jstree events 
			// (this kind of wrinting event.eventcomplement is not supported by JMVC
			// @todo unbind le bazarre dans une fonction destroy
			this.element.bind('contextmenu.jstree', function (event, data, b) {
			event.stopPropagation();
			event.preventDefault();
				var $item = $(event.originalEvent.target).parent('li');
				self.itemRightSelected($item[0].id, $item, event);
			});
		}
	});
});