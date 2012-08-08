steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/view/template/component/gridCell.ejs'
)
.then(
	function($){
        
		/*
        * @class mad.controller.component.gridCellController
        * @inherits {mad.controller.ComponentController}
        * @parent index
        * 
        * The Grid Cell class Controller is our implementation of the UI grid cell.
		* This component will help to manage value and interaction with the cell
		* 
        * @constructor
        * Creates a new Grid Cell Controller Component
		* @param {array} options Optional parameters
        * @return {mad.controller.component.gridCellController}
        */
		mad.controller.ComponentController.extend('mad.controller.component.gridCellController', 
		/** @static */
		{
			'defaults': {
				'label':			'Grid Cell Component'
			}
		},
		/** @prototype */
		{
			
			// Construcor
			'init': function(el, options)
			{
				this._super(el, options);
				this.setViewData('value', options.value);
			}	
		});
        
	}
);
