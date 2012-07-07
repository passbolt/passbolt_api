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
				this._super(elt, options);
				
				// Jstree themes path is incorrect, set it to the right path
				$.jstree._themes = APP_URL+'/js/lib/jstree/themes/';
				// Store the map which will be used to map the JMVC Model object into jstree nodes object
				// This params is compulsory
                if(typeof this.options.map == 'undefined'){
                    throw new mad.error.MissingOption('map', 'mad.controller.component.TreeController');
                }
				this.map = this.options.map;
			},
			
			/**
			 * Load the tree with an additionnal node at the specific position (ref + position)
			 * @param {object} data The data which represent the node
			 * @return {void}
			 */
			'load': function(data)
			{
				if($.isArray(data)){
					var mappedData = mapObjects(data, this.map);
					for(var i in mappedData){
						this.create(mappedData[i], 'last', this.element);
					}
				}
				else{
					var mappedData = mapObject(data, this.map);
					this.create(mappedData, 'last', this.element);
				}
				
			},
			
			/**
			 * Create node
			 * @param {jstreeNode} jsonNode The node to insert
			 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
			 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
			 * @return {JQuery} The created node
			 */
			'create': function(jsonNode, position, ref)
			{
				var inst = jQuery.jstree._reference(this.getId());
				var node = inst.create_node(ref, position, jsonNode);
				
				for(var i in jsonNode.children){
					this.create(jsonNode.children[i], 'last', node);
				}
				
				return node;
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
