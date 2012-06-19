steal( 
    'jquery/view/ejs',
    'lb/core/controller/componentController.js'
)
.then(
    'passbolt/password/view/template/categoryChooser.ejs'
    , function($){
        
        /*
        * @class passbolt.passbolt.controller.CategoryChooserController
        * @parent index 
        * @constructor
        * Creates a new CategoryChooserController.
        * @return {passbolt.password.controller.CategoryChooserController}
        */
        lb.core.controller.ComponentController.extend('passbolt.password.controller.CategoryChooserController', 
        
        /** @static */
        {},
        
        /** @prototype */
        {
            
            'init' : function(el, options)
            {
                this._super();
                this.render();
            }
            
            , 'selectCategory': function(categoryId)
            {
                this.getEventBus().trigger('passbolt_category_selected', {'category_id':categoryId})
            }
            
            , 'li click': function(element, evt, data)
            {
                this.selectCategory(element.html());
            }
            
        });
        
    }
);
