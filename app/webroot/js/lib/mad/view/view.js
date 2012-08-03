steal( 
    'jquery/class',
    MAD_ROOT+'/event/eventable.js'
)
.then( 
    function($){
        		
        /*
         * @class mad.view.View
		 * @inherits jQuery.View
         * @parent index
		 * 
         * @constructor
         * 
         * @return {mad.view.View}
        */
        $.Class('mad.view.View', 
		/** @static */
        {
			'defaults':{
				'templateUri':null
			}
		}
		/** @prototype */
		, {              
			
            /**
             * The component controller which use this view
             * @type {mad.controller.ComponentController}
             * @private
             * @hide
             */
            'controller':null,
			
            /**
             * The dom node element to render the view
             * @type {jQuery}
             * @private
             * @hide
             */
            'element':null,
			
            /**
             * The associated template uri. If null, the templateUri will be defined on the Class name
             * @type {string}
             * @private
             * @hide
             */
            'templateUri': null,

			// Constructor like
			'init':function(controller, options)
			{
				this.controller = controller;
				this.element = controller.element;
				this.templateUri = options.templateUri;
			},
			
            /**
             * Get the component's template. If the options.template has been defined 
             * use this one. Else build the template uri functions of the component name.
             * @return {String} The component template uri
             */
            'getTemplate': function(options)
            {
                var returnValue = '';
                				
                // the template uri defined
                if(this.templateUri != null){
                    returnValue = this.templateUri;
				}
                // define the template functions of the class name
                else{
                    returnValue = mad.helper.controllerHelper.getViewPath(this.controller.Class);
                }
                
                return returnValue;
            },
			
            /**
             * The render method renders the view based on its template.
             * @see {getTemplate}
             * @param {array} options Optional parameters	
             * @param {boolean} options.display Display the rendered component. If true
             * the rendered component will be push in the DOM else the rendered component
             * will be stored in the instance's variable renderedView
             * @return {mixed} Return true if the method does not encountered troubles else
             * return false. If the option display is set to false, return the rendered view
             */
			'render': function(options)
			{
//				console.log('RENDER TEMPLATE '+this.getTemplate());
				var options = options || {};
				var display = options.display || true;
				
				// render the view
				var render = $.View(this.getTemplate(), this.controller.viewData);
				
				// display the rendered view
                if(display){
                    this.element.append(render);
                    returnValue = true;
                }
				// return the rendered view
                else{
                    this.renderedView = render;
                    returnValue = render;
                }
				
				return returnValue;
			},
			
			/**
             * Set the view's template uri
			 * @param {string} templateUri The template uri
             * @return {void}
             */
			'setTemplateUri': function(templateUri)
			{
				this.templateUri = templateUri;
			}
		});
		
		mad.view.View.augment('mad.event.Eventable');
	}	
);