steal( 
    MAD_ROOT
)
.then(
    'plugin/password/view/template/component/passwordInformation.ejs'
    , function($){
        
        /*
        * @class passbolt.password.controller.component.PasswordInformationController
        * @parent index 
        * @constructor
        * Creates a new PasswordInformationController.
        * @return {passbolt.password.controller.component.PasswordInformationController}
        */
        mad.controller.ComponentController.extend('passbolt.password.controller.component.PasswordInformationController', 
        
        /** @static */
        {
            'eventToAjaxTransaction': ['passbolt_password_selected']
        },
        
        /** @prototype */
        {
            
            'init' : function(el, options)
            {
                this._super();
                this.render();
            }
            
            , 'selectPassword': function(passwordId)
            {
                console.log('PasswordInformationController : password selected '+passwordId);
            }
            
            , '{lb.eventBus} passbolt_password_selected': function(element, evt, data)
            {
                lb.ajaxWrapper.request({
                    'url':'http://getacountdown.local/ajax/action1'
                    , 'data': {
                        'key1': 'value1',
                        'key2': 'value2',
                        'key3': 'value3'
                    }
                    , 'success': function(srvData){
                        console.log("password information controller callback "+srvData);
                    }
                }, true);
                this.selectPassword(data.password_id);
            }
            
        });
        
    }
);
