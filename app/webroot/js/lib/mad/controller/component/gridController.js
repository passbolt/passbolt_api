steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/view/component/grid.js',
	 MAD_ROOT+'/object/map.js'
)
.then(
	function($){
        
		/*
        * @class mad.controller.component.GridController
        * @inherits mad.controller.ComponentController
        * @parent index
		* @see {mad.view.component.Grid}
        * 
        * The Grid class Controller is our implementation of the UI component grid.
		* The common view works upon the jQgrid plugin.
		* 
        * @constructor
        * Creates a new Grid Controller Component
		* @param {array} options Optional parameters
        * @return {mad.controller.component.GridController}
        */
		mad.controller.ComponentController.extend('mad.controller.component.GridController', 
		/** @static */
		{
			'defaults': {
				'label':			'Grid Component',
				'viewClass':		mad.view.component.Grid,
				'templateUri':		'//'+MAD_ROOT+'/view/template/component/grid.ejs'
			}
		},
		/** @prototype */
		{
			
			/**
             * The map to transform JMVC model object into jqgrid understable format
             * @type {mad.object.Map}
             */
			'map': null,
			
			// Construcor
			'init': function(el, options)
			{
				this._super(el, options);
				this.map = this.options.map;
				this.setViewData('items', []);
			},
			
			/**
			 * 
			 */
			'load': function(items)
			{
				this.setViewData('items', items);
				// map the jmvc model objects into the desired format
				var mappedData = this.map.mapObjects(items);				
				this.view.load(mappedData);
			}
		});
        
	}
);
