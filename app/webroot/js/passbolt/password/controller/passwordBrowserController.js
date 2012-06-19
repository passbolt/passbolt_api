steal( 
    'lb/core/controller/componentController.js'
)
.then(
    'passbolt/password/view/template/passwordBrowser.ejs'
    , function($){
        
        /*
        * @class passbolt.passbolt.controller.PasswordBrowserController
        * @parent index 
        * @constructor
        * Creates a new PasswordBrowserController.
        * @return {passbolt.password.controller.PasswordBrowserController}
        */
        lb.core.controller.ComponentController.extend('passbolt.password.controller.PasswordBrowserController', 
        
        /** @static */
        {
            'eventToAjaxTransaction': ['passbolt_password_selected']
        },
        
        /** @prototype */
        {
            
            'init' : function(el, options)
            {
                this._super();
            }
            
            , 'selectPassword': function(passwordId)
            {
                console.log('PasswordBrowserController : password selected '+passwordId);
            }
            
            , 'refresh': function(categoryId)
            {
                console.log('refresh the password browser with the password of the selected category '+categoryId);
            }
            
            , '{lb.eventBus} passbolt_password_selected': function(element, evt, data)
            {
                lb.ajaxWrapper.request({
                    'url':'http://getacountdown.local/ajax/action3'
                    , 'data': {
                        'key1': 'value1',
                        'key2': 'value2',
                        'key3': 'value3'
                    }
                    , 'success': function(srvData){
                        console.log("password browser controller callback "+srvData);
                    }
                }, true);
                this.selectPassword(data.password_id);
            }
            
            , '{lb.eventBus} passbolt_category_selected': function(element, evt, data)
            {
                this.refresh(data.category_id);
            }
            
            , 'li click': function(element, evt, data)
            {
                this.getEventBus().trigger('passbolt_password_selected', {'password_id':element.html()})
            }
            
            , 'render' : function(options){
                this._super(options);
            }
        });
        
    }
);
