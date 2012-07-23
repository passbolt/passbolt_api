steal( 
    MAD_ROOT+'/controller/controller.js',
    MAD_ROOT+'/helper/controllerHelper.js'
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
         * @return {mad.controller.ComponentController}
        */
        mad.controller.Controller.extend('mad.controller.ComponentController', 
		/** @static */
		{
            'defaults' : {
                'label': 'ComponentController'				// Label of the component
                , 'icon': null                              // @notUsedYet
//                , 'template': null                        // the default template, the last default override other defaults !!! check about that clearly
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
             * The associated template uri. If null, the templateUri will be defined on the Class name
             * @type {string}
             * @private
             * @hide
             */
            'templateUri': null,
			
			/**
			 * The reference class to user to find the template. The template class reference is used to
			 * define the template uri if this one is null.
			 * the template.
			 * @type {string}
			 * @private
			 * @hide
			 */
			'TemplateClassReference': null,
            
            // Class Constructor
            'init': function(el, options)
            {
                // feedback the user about the component loading
                // bon, est ce que c'est bien sa place ?
//                this.loading(true);
                
                this._super();
				
                // set the component's template if a specific template is given, else the template will be defined based on the Class name
                if(typeof this.options.templateUri != 'undefined'){
					this.setTemplateUri(this.options.templateUri);
				}
				// set the template class reference
				if(typeof this.options.templateClassReference != 'undefined'){
					this.setTemplateClassReference(this.options.templateClassReference);
				}
				
                // add the controller to the data to pass to the view
                this.setViewData('controller', this);
                
                // stop the feedback loading
//                this.loading(false);
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
				// the template class reference defined
				else if(this.templateClassReference){
					returnValue = mad.helper.controllerHelper.getViewPath(window[this.templateClassReference]);
				}
                // define the template functions of the class name
                else{
                    returnValue = mad.helper.controllerHelper.getViewPath(this.Class);
                }
                
                return returnValue;
            },
            
			/**
             * Set the component's template uri
			 * @param {string} templateUri The template uri
             * @return {void}
             */
			'setTemplateUri': function(templateUri)
			{
				this.templateUri = templateUri;
			},
            
			/**
             * Set the component's template class reference
			 * @param {string} templateUri The template template class reference
             * @return {void}
             */
			'setTemplateClassReference': function(templateClassReference)
			{
				this.templateClassReference = templateClassReference;
			},
			
            /** 
             * Display a loading feedback to the user
             * @param {Boolean} enable show If true show the feedback else hide it 
             * @return {void}
            */
            'loading': function(enable)
            {
//                if(enable){
//                    steal.dev.log('the component ('+this.element[0].id+') is loading');
//                } else {
//                    steal.dev.log('the component ('+this.element[0].id+') is finish loading');
//                }
            },
            
            /**
             * The set method allows developper to set data to the view
             * @param {mixed} name the variable's name or the array of data to add to the view
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
             * The render method renders the component based on its template.
             * Either the template url is passed during the component instanciation either
             * the template url is defined by the Class' function getTemplate
             * @see {getTemplate}
             * @param {array} options Associative array of options
             * @param {boolean} options.display Display the rendered component. If true
             * the rendered component will be push in the DOM else the rendered component
             * will be stored in the instance's variable renderedView
             * @return {boolean} Return true if the method does not encountered troubles else
             * return false
             */
            'render': function(options)
            {
                var returnValue = false,
					options = options || {},
					display = options.display || true;
                
				// render the template
                var renderingTemplate = $.View(this.getTemplate(), this.viewData);
                if(display){
                    this.element.html(renderingTemplate);
                    returnValue = true;
                }
                else{
                    this.renderedView = renderingTemplate;
                    returnValue = true;
                }
                
                return returnValue;
            }
            
        });
        
    }
);
