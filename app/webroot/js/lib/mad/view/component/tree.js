steal( 
    MAD_ROOT+'/view',
	'lib/jstree/jquery.jstree.js'
)
.then( 
    function($){
        		
        /*
         * @class mad.view.component.Tree
		 * @inherits mad.view.View
         * @parent index
		 * 
         * @constructor
         * 
         * @return {mad.view.component.Tree}
        */
        mad.view.View.extend('mad.view.component.Tree', 
		/** @static */
        { }
		
		/** @prototype */
		, {  
			
			/**
			 * The jstree instance
			 * @type {jstree}
			 */
            'jstreeInstance': null,
			
			// Constructor like
			'init':function(controller, options)
			{
				this._super(controller, options);
				
				// Jstree themes path is incorrect, set it to the right path
				$.jstree._themes = APP_URL+'/js/lib/jstree/themes/';
			},
			
			/**
			 * Insert a node in the tree
			 * @param {mixed} jsonNode The node to insert
			 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
			 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
			 * @return {JQuery} The created node
			 */
			'insertNode': function(jsonNode, position, ref)
			{
				var node = this.jstreeInstance.create_node(ref, position, jsonNode);
				
				for(var i in jsonNode.children){
					this.insertNode(jsonNode.children[i], 'last', node);
				}
			},

			/**
			 * An item has been selected
			 * @return {void}
			 */
			'itemSelected': function(itemId, obj)
			{
				this.element.trigger('item_selected', itemId);
			},

			/**
			 * Render the jstree component
			 * @return {void}
			 */
			'render': function()
			{
				var self = this;
				this._super();
				
				this.element.jstree({
					"json_data":	{"data":	[]},
					"plugins":		[ "themes", "json_data", "ui" ]

				});
				this.jstreeInstance = $.jstree._reference(this.controller.getId());
				
				// pas propre mode, mais on deroule
				
				// change the component status in ready
				this.status = 'ready';
				// bind jstree events 
				// (this kind of wrinting event.eventcomplement is not supported by JMVC)
				this.element.bind('select_node.jstree', function (event, data) {
					var itemId = data.rslt.obj.attr("id");
					self.itemSelected(itemId, data.rslt.obj);
				});
			}
		});
	}	
);