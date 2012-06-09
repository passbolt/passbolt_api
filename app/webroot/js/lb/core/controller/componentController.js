steal( 
    'lb/core/controller/controller.js',
    'lb/core/error/templateMissing.js',
    'lb/core/helper/controllerHelper.js'
)
.then( 
    function($){
        
        lb.core.controller.Controller.extend('lb.core.controller.ComponentController', {
            'defaults' : {
                'label': 'ContainerComponentController'
                , 'icon': null          // @notUsedYet
                , 'decorate': null      // decorate the component with a box system or wathever @notUsedYet
//                , 'template': null      // the default template, the last default override other defaults !!! check about that clearly
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
            
            // Class Constructor
            'init': function(el, options)
            {
                // feedback the user about the component loading
                // bon, est ce que c'est bien sa place ?
                this.loading(true);
                
                this._super();
                // add the controller to the data to pass to the view
                this.set('controller', this);
                // set the template variable, this variable will be used by the function render
                this.template = this.options.template
                
                // stop the feedback loading
                this.loading(false);
                
                // Notice the system than a new component has been released
                this.getEventBus().trigger('lb_component_released', {'component':this});
            },
            
            /**
             * Get the assosciated template. If the options.template has been defined use this one.
             * Else construct the template name function of the class name
             * @return {String} The associated template
             */
            'getTemplate': function()
            {
                var returnValue = '';
                
                // template is defined as a default option
                if(typeof this.options.template != 'undefined'){
                    returnValue = this.options.template;
                }
                // define the template functions of the class name
                else{
                    returnValue = lb.core.helper.controllerHelper.getViewPath(this.Class);
                }
                
                return returnValue;
            },
            
            /** 
            * Display a loading feedback to the user
            * @param {Boolean} enable show If true show the feedback else hide it 
            */
            'loading': function(enable)
            {
                if(enable){
                    steal.dev.log('the component ('+this.element[0].id+') is loading');
                } else {
                    steal.dev.log('the component ('+this.element[0].id+') is ready');
                }
            },
            
            /**
             * The set method allows developper to set data to the view
             * @param {string} name the variable name
             * @param {mixed} value the variable value
             * @return {void}
             */
            'set': function(name, value)
            {
                this.viewData[name] = value;
            },
            
            /**
             * Render the component based on its template
             * @see {getTemplate}
             */
                'render': function()
                {
                    this.element.html($.View(this.getTemplate(), this.viewData));
                    return;
                    // how to decorate properly the component

                    // Subtemplate decoration test
    //                if(this instanceof lb.core.controller.ContainerController){
    //                }
    //                else{
                        var content = $.View(this.getTemplate(), this.viewData);
                        this.element.html($.View("//lb/core/view/decorator/box.ejs"));
                        //tout pas beau, mais les sub tpl ne marchent pas , il semble meme que le passage de variable contenant du html est escape qq part
                        this.element.find('.lb-box-content').html(content);
    //                }

                }
            
        });
        
    }
);
