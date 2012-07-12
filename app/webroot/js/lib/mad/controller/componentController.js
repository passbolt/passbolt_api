steal( 
    'jquery/view/ejs',
    MAD_ROOT+'/controller/controller.js',
    MAD_ROOT+'/helper/controllerHelper.js',
	'//'+MAD_ROOT+'/view/template/component/decorator/box.ejs'
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
        mad.controller.Controller.extend('mad.controller.ComponentController', {
            'defaults' : {
                'label': 'ComponentController'				// Label of the component
                , 'icon': null                              // @notUsedYet
//                , 'template': null                        // the default template, the last default override other defaults !!! check about that clearly
            }
        }
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
            
            // Class Constructor
            'init': function(el, options)
            {
                // feedback the user about the component loading
                // bon, est ce que c'est bien sa place ?
//                this.loading(true);
                
                this._super();
				
                // set the template variable, this variable will be used by the function render
                if(typeof this.options.template != 'undefined'){
					this.setTemplate(this.options.template);
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
            'getTemplate': function()
            {
                var returnValue = '';
                
                // template has been defined
                if(typeof this.template != null){
                    returnValue = this.template;
                }
                // define the template functions of the class name
                else{
                    returnValue = mad.helper.controllerHelper.getViewPath(this.Class);
                }
                
                return returnValue;
            },
            
			/**
             * Set the component's template.
             * @return {void}
             */
			'setTemplate': function(template)
			{
				this.template = template;
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
             * @param {string} name the variable's name
             * @param {mixed} value the variable's value
             * @return {void}
             */
            'setViewData': function(name, value)
            {
                this.viewData[name] = value;
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
             * @param {boolean} Return true if the method does not encountered troubles else
             * return false
             */
            'render': function(options)
            {
                var returnValue = false;
                var options = typeof options == 'undefined' ? {} : options;
                // display the rendering if true and return a boolean, else return the rendering code
                var display = typeof options.display == 'undefined' ? true : options.display;
                
				// render the template
                var renderingTemplate = $.View(this.getTemplate(), {});
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
