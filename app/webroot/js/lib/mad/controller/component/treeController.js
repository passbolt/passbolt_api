steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/controller/component/tree/jstreeStrategy.js',
	 MAD_ROOT+'/object/map.js'
	)
.then(
	function($){
        
		/*
        * @class mad.controller.component.TreeController
        * @inherits $.Controller
        * @parent index
        * 
        * The Tree class Controller is our implementation of the UI component tree.
		* This one is implemented upon the pattern strategy to allow developpers to
		* easily to switch on the desired tree component (By default jstree)
        * 
		* <br/>
		* 
		* The different strategies are located in the package mad.controller.component.tree
		* 
        * @constructor
        * Creates a new Tree Controller Component
        * @return {mad.controller.component.TreeController}
        */
		mad.controller.ComponentController.extend('mad.controller.component.TreeController', 
		/** @static */
		{
			'defaults': {
				'label':	'TreeController',
				'strategy': 'jstree'
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
			'init': function(elt, options)
			{
				this._super();
				
				// Store the map which will be used to map JMVC Model objects into jstree node objects
				// This parameter is mandatory
                if(typeof this.options.map == 'undefined'){
                    throw new mad.error.MissingOption('map', 'mad.controller.component.TreeController');
                }
				this.map = this.options.map;
				
				// Instanciate the tree strategy
				var strategyClass = mad.controller.component.tree[$.String.capitalize(this.options.strategy)+'Strategy'];
				if(typeof strategyClass == 'undefined'){
					throw new mad.error.WrongParameters('The strategy parameter is wrong, it should be one of the followin (jstree, ...)');
				}
				this.strategy = new strategyClass(this);
			},
			
			/**
			 * Load the tree with an additionnal node at the specific position (ref + position)
			 * @param {object|array} data The data which represent the node
			 * @param {string} position The position of the newly created node. This can be a zero based index to position the element at a specific point among the current children. You can also pass in one of those strings: "before", "after", "inside", "first", "last". The default value is last
			 * @param {mixed} ref This can be a DOM node, jQuery node or selector pointing to the element you want to create in (or next to). The default value is the root node element
			 * @return {jQuery} The created node(s)
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
				return this.strategy.insertNode(jsonNode, position, ref);
			},			
			
			/*
             * Render the tree component
             * @return {void}
             */
			'render': function()
			{ 
				this.strategy.render();

			}
		});
        
	}
);
