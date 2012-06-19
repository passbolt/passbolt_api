steal( 
    'jquery/view/ejs',
    
    'lb/core/controller/workspaceController.js',
    'lb/core/helper/component/boxDecorator.js',
    'lb/core/helper/component/loadingDecorator.js',
    
    'passbolt/password/controller/passwordBrowserController.js',
    'passbolt/password/controller/categoryChooserController.js',
    'passbolt/password/controller/passwordInformationController.js',
    'passbolt/password/controller/accessRightController.js'
)
.then(
    'lb/core/view/template/container/three-column.ejs',
    'lb/core/view/template/container/vertical.ejs',
    function($){
        
        /*
        * @class passbolt.passbolt.controller.PasswordWorkspaceController
        * @parent index 
        * @constructor
        * Creates a new PasswordWorkspaceController
        * @return {passbolt.password.controller.PasswordWorkspaceController}
        */
        lb.core.controller.WorkspaceController.extend('passbolt.password.controller.PasswordWorkspaceController', {
            'defaults' : {
                'label': 'WorkspaceController'
                , 'templateType' : 'three-column'
            }
        }
        ,{
            
            'init' : function(el, options)
            {
                this.options = $.extend(true, {}, this.options, options);
                // Use the templateType to define the template
                this.options.template = '//lb/core/view/template/container/'+this.options.templateType+'.ejs';
                
                // Render
                this.render();
                
                // *************************************************************
                // First side area
                // *************************************************************
                
                // Add the Password browser component
                var passwordBrowserController = this.addComponent(passbolt.password.controller.PasswordBrowserController, {
                    'id':'passbolt_password_password_browser'
                });
                passwordBrowserController = passwordBrowserController.decorate('lb.core.helper.BoxDecorator');
                passwordBrowserController = passwordBrowserController.decorate('lb.core.helper.LoadingDecorator');
                passwordBrowserController.render();
                
                // *************************************************************
                // Main area
                // *************************************************************
                
                // Add the Category Chooser component
                this.addComponent(passbolt.password.controller.CategoryChooserController, {
                    'id':'passbolt_password_category_chooser'
                }, 'lb-container-first_side');
                
                
                // *************************************************************
                // Second side area
                // *************************************************************
                
                // Add vertical container to the second side area
                var secondSideContainer = this.addComponent(lb.core.controller.ContainerController, {
                    'id':'passbolt_password_second_side_container'
                    , 'template' : '//lb/core/view/template/container/vertical.ejs'
                }, 'lb-container-second_side');
                secondSideContainer.render();
                
                // Add the Password Information component
                secondSideContainer.addComponent(passbolt.password.controller.PasswordInformationController, {
                    'id':'passbolt_password_password_information'
                });
                
                // Add the Password Information component
                secondSideContainer.addComponent(passbolt.password.controller.AccessRightController, {
                    'id':'passbolt_password_access_right'
                });
                
                this._super();
            }
            
            ,'index': function(a, b, c)
            {
                console.log('Execute function index of the password workspace controller, with the following arguments');
                console.dir(arguments);
            }
            
        });
        
    }
);
