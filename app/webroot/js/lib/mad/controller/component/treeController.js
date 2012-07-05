steal( 
	'jquery/controller'
	, MAD_ROOT+'/controller/componentController.js'
	,'lib/jstree/jquery.jstree.js'
	)
.then(
	function($){
        
		mad.controller.ComponentController.extend('mad.controller.component.TreeController', 
		/** @static */
		{
			'default': {
				'label': 'TreeController'
			}
		},
		/** @prototype */
		{
			// Construcor
			'init': function(elt, options)
			{
				// bon c'est un arbre de quoi en gros
				// le mapping please
				
				// send to the event bus the information about the component creation
				mad.eventBus.trigger(mad.APP_NS_ID+'_tree_released', {
					'component':this
				});
				this._super();
			},
			
			/**
			 * Load the tree with an additionnal node at the specific position (ref + position)
			 * @param {object} data The data which represent the node
			 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
			 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
			 */
			'load': function(data, position, ref)
			{
				var position = typeof position == 'undefined' ? 'last' : position;
				var ref = typeof ref == 'undefined' ? this.element : ref;

				var inst = jQuery.jstree._reference(this.getId());
				var node = inst.create_node(ref, position, data);
				for(var i in data.children){
					this.load(data.children[i], 'last', node);
				}
			},
			
			/*
             * Render the tree component
             * @return {void}
             */
			'render': function()
			{ 
				this.element.jstree({
					"json_data":	{"data":	[]},
					"plugins":		[ "themes", "json_data", "ui" ]

				});
			//                .bind("select_node.jstree", function (e, data) {
			//                    alert(data.rslt.obj.data("id"));
			//                });

			}
		});
        
	}
);
