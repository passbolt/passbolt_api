steal(
    MAD_ROOT+'/controller/controller.js',
    MAD_ROOT+'/helper/controllerHelper.js',
    MAD_ROOT+'/view/view.js'
)
.then(
    function($){
        		
        /*
         * @class mad.controller.ComponentController
		 * @inherits mad.controller.Controller
         * @parent index
		 * 
         * The component controller represents a graphical component controller 
         * in the application. 
		 * 
         * @constructor
         * Creates a new Component Controller
		 * @param {array} options Optional parameters
		 * @param {string} options.label Label of the component
		 * @param {boolean} options.loading Auto loading prompt
		 * @param {Class} options.viewClass Defined the associated view class. If it is not defined, the system will try to figure out
		 * the view class from the class name. If no view class found, it will use the default mad.view.View
		 * the template.
         * @return {mad.controller.ComponentController}
        */
        mad.controller.Controller.extend('mad.controller.ComponentController', 
		/** @static */
		{
            'defaults' : {
                'label':			'ComponentController'			// Label of the component
                , 'icon':			null                            // @todo
				, 'loading':		false							// auto loading prompt
				, 'templateUri':	null							// the template which will used by the view to render the component
				, 'viewClass':		mad.view.View					// associated view will be an instance of this viewClass
            }
        }
		/** @prototype */
        , {    
            
            /**
             * Data to pass to the view
             * @type {array}
             * @private
             * @hide
             */
            'viewData': [],
            
            /**
             * The rendered view, if not displayed will be stored in this variable
             * @type {string}
             * @private
             * @hide
             */
            'renderedView': '',
			
			/**
			 * 
			 * @type {string}
			 * @private
			 * @hide
			 */
			'viewClass': null,
			
            // Class Constructor
            'init': function(el, options)
            {
                // feedback the user about the component loading
                // bon, est ce que c'est bien sa place ?
                this.loading(true);
				
                this._super(el, options);
				
				// initialize the view
				this.view = new this.options.viewClass(this, {
					'templateUri': this.options.templateUri
				});
				
                // add the controller to the data to pass to the view when it will render
                this.setViewData('controller', this);
                
                // stop the feedback loading
                this.loading(false);
            },
			
			/**
			 * 
			 */
			'loading': function(loading)
			{
				
			},
            
			/**
             * Set the view template uri
			 * @param {string} templateUri The template uri
             * @return {void}
             */
			'setTemplateUri': function(templateUri)
			{
				this.view.setTemplateUri(templateUri);
			},
            
            /**
             * The set method allows developper to set data to the view
             * @param {string} name the variable's name or the array of data to add to the view
             * @param {mixed} value the variable's value or null if the name is an array
             * @return {void}
             */
            'setViewData': function(name, value)
            {
				if(typeof name == 'object'){
					var viewDataZ = name;
					for (var i in viewDataZ){
						this.setViewData(i, viewDataZ[i]);
					}
				}
				else{
					this.viewData[name] = value;
				}
            },
            
            /**
             * Render the component
             * @see {mad.view.View}
             * @param {array} options Associative array of options
             * @param {boolean} options.display Display the rendered component. If true
             * the rendered component will be push in the DOM else the rendered component
             * will be stored in the instance's variable renderedView
             * @return {mixed} Return true if the method does not encountered troubles else
             * return false. If the option display is set to false, return the rendered view
             */
            'render': function(options)
            {
                var returnValue = false,
					options = options || {},
					display = options.display || true;
					
				returnValue = this.view.render(options);
                return returnValue;
            }
            
        });
        
    }
);
