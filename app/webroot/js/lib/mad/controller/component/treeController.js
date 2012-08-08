steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/view/component/tree.js',
	 MAD_ROOT+'/object/map.js'
)
.then(
	function($){
        
		/*
        * @class mad.controller.component.TreeController
        * @inherits {mad.controller.ComponentController}
        * @parent index
		* @see {mad.view.component.Tree}
        * 
        * The Tree class Controller is our implementation of the UI component tree.
		* The common view works upon the jstree plugin.
		* 
        * @constructor
        * Creates a new Tree Controller Component
		* @param {array} options Optional parameters
		* @param {mad.object.Map} map The mapping object used to map data from the JMVC
		* model to the understood format.
        * @return {mad.controller.component.TreeController}
        */
		mad.controller.ComponentController.extend('mad.controller.component.TreeController', 
		/** @static */
		{
			'defaults': {
				'label':			'Tree Component',
				'viewClass':		mad.view.component.Tree,
				'templateUri':		'//'+MAD_ROOT+'/view/template/component/tree.ejs'
			}
		},
		/** @prototype */
		{
			
			/**
             * The map to transform JMVC model object into jstree node
             * @type {mad.object.Map}
             */
			'map': null,
			
			// Construcor
			'init': function(el, options)
			{
				this._super(el, options);
				
				// Store the map which will be used to map JMVC Model objects into jstree node objects
				// This parameter is mandatory
                if(typeof this.options.map == 'undefined'){
                    throw new mad.error.MissingOption('map', 'mad.controller.component.TreeController');
                }
				this.map = this.options.map;
			},
			
			/**
			 * Load the tree with an additionnal node at the specific position (ref + position)
			 * @param {object|array} data The data which represent the node
			 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
			 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
			 * @return {jQuery} The created node(s)
			 * @todo the mapping could be done in the view ?
			 */
			'load': function(data, position, ref)
			{
				var returnValue = null;

				position = (typeof position != 'undefined') ? position : 'last';
				ref = (typeof ref != 'undefined') ? ref : this.element;
				
				if($.isArray(data)){
					returnValue = [];
					// map the jmvc model objects into the desired format
					var mappedData = this.map.mapObjects(data);
					for(var i in mappedData){
						returnValue.push(this.insertNode(mappedData[i], position, ref));
					}
				}
				else{
					// map the jmvs model objects into the desired format
					var mappedData = mad.object.Map.mapObject(data, this.map);
					returnValue = this.insertNode(mappedData, position, ref);
				}
				
				return returnValue;
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
				return this.view.insertNode(jsonNode, position, ref);
			}
		});
        
	}
);
