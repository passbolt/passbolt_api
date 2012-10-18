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
	mad.view.component.Tree.extend('mad.view.component.tree.Jstree', /** @static */ {

		listenTo: ['contextmenu.jstree', 'select_node.jstree']

	}, /** @prototype */ {

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
		 * Insert an item in the tree
		 * @param {mad.model.Model} item The item to insert
		 * @param {string} refItemId The reference item id. By default the tree view object
		 * will choose the root as reference element.
		 * @param {string} position The position of the newly created node. This can 
		 * be a zero based index to position the element at a specific point among 
		 * the current children. You can also pass in one of those strings: "before", 
		 * "after", "inside", "first", "last". By dhe default value is set to last.
		 * @throw mad.error.CallAbstractFunction
		 * @return {JQuery} The created node
		 * @todo does not require a map in this case
		 */
		'insertItem': function (item, refItemId, position) {
			position = position || 'last';
			var $ref = refItemId ? this.element.find('#' + refItemId) : this.element;
			// map the jmvc model objects into the desired format
			var mappedItem = this.map.mapObject(item);
			var node = this.jstreeInstance.create_node($ref, position, mappedItem);
			for (var i in item.children) {
				this.insertItem(item.children[i], mappedItem.attr.id, 'last');
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

			// change the component status in ready
			// @todo ca craint ce changement de status a la main comme ca, a voir
			this.status = 'ready';
		},
		
		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */
		
		/**
		 * An item has been right selected
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'contextmenu.jstree' : function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			var $li = $(event.originalEvent.target).parent('li');
			this.itemRightSelected($li[0].id, $li, event);
		},

		/**
		 * An item has been selected
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @param {mixed} data The event data
		 * @return {void}
		 */
		'select_node.jstree' : function (element, event, data) {
			var itemId = data.rslt.obj.attr("id");
			this.itemSelected(itemId, data.rslt.obj);
		}

	});
});