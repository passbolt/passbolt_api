steal( 
    'jquery/class',
	'lib/jstree/jquery.jstree.js'
)
.then( 
    function($){
        
        /*
        * @class mad.controller.component.tree.Strategy
        * @parent index
        * @inherits mad.core.Class
		* 
        * Our implementation of the strategy jstree, this strategy is used by
		* a mad.controller.component.TreeController object to manipulate jQuery
		* Tree based on the well know jstree plugin
        * 
        * @constructor
        * Instanciate the strategy
		* @params {mad.object.Map} map The map used to map JMVC model object into
		* jstree node objects
        * @return {mad.core.Singleton}
        */
        $.Class('mad.controller.component.tree.JstreeStrategy', 
                
        /** @static */
        { },
        
        /** @prototype */
        {
			
			/**
			 * The jstree instance
			 * @type {jstree}
			 */
            'jstreeInstance': null,
			
			/**
			 * The tree controller which use this strategy
			 * @type {mad.controller.component.TreeController}
			 */
			'treeController': null,
			
            // Class Constructor
            'init': function(treeController)
            {
                // Jstree themes path is incorrect, set it to the right path
				$.jstree._themes = APP_URL+'/js/lib/jstree/themes/';
				this.treeController = treeController;
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
			 * Render the jstree component
			 */
			'render': function()
			{
				this.treeController.element.jstree({
					"json_data":	{"data":	[]},
					"plugins":		[ "themes", "json_data", "ui" ]

				});
				this.jstreeInstance = $.jstree._reference(this.treeController.getId());
			}
			
            
        });
        
    }
);
