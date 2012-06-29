steal( 
    MAD_ROOT
)
.then(
//    'plugin/password/view/template/component/categoryChooser.ejs',
    function($){
        
        /*
        * @class passbolt.passbolt.controller.CategoryChooserController
        * @parent index 
        * @constructor
        * Creates a new CategoryChooserController.
        * @return {passbolt.password.controller.CategoryChooserController}
        */
        mad.controller.ComponentController.extend('passbolt.password.controller.component.CategoryChooserController', 
        
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
                passbolt.eventBus.trigger('passbolt_category_selected', {'category_id':categoryId})
            }
            
            , 'li click': function(element, evt, data)
            {
                this.selectCategory(element.html());
            }
            
        });
        
    }
);
