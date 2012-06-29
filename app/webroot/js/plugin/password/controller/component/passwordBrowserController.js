steal( 
    MAD_ROOT+'/controller/componentController.js'
)
.then(
//    'plugin/password/view/template/component/passwordBrowser.ejs',
    function($){
        
        /*
        * @class passbolt.password.controller.component.PasswordBrowserController
        * @parent index 
        * @constructor
        * Creates a new PasswordBrowserController.
        * @return {passbolt.password.controller.component.PasswordBrowserController}
        */
        mad.controller.ComponentController.extend('passbolt.password.controller.component.PasswordBrowserController', 
        
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
            
            , '{mad.eventBus} passbolt_password_selected': function(element, evt, data)
            {
                mad.net.Ajax.singleton().request({
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
            
            , '{mad.eventBus} passbolt_category_selected': function(element, evt, data)
            {
                this.refresh(data.category_id);
            }
            
            , 'li click': function(element, evt, data)
            {
                mad.eventBus.trigger('passbolt_password_selected', {'password_id':element.html()})
            }
        });
        
    }
);
