steal( 
    'jquery/view/ejs',
    
    MAD_ROOT+'/controller/component/workspaceController.js',
    
    'plugin/password/controller/component/passwordBrowserController.js',
    'plugin/password/controller/component/categoryChooserController.js',
    'plugin/password/controller/component/passwordInformationController.js',
    'plugin/password/controller/component/accessRightController.js'
)
.then(
//    MAD_ROOT+'/view/template/component/container/three-column.ejs',
//    MAD_ROOT+'/view/template/component/container/vertical.ejs',
    function($){
        
        /*
        * @class passbolt.passbolt.controller.PasswordWorkspaceController
        * @parent index 
        * @constructor
        * Creates a new PasswordWorkspaceController
        * @return {passbolt.password.controller.PasswordWorkspaceController}
        */
        mad.controller.component.WorkspaceController.extend('passbolt.password.controller.PasswordWorkspaceController', {
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
                this.options.template = '//'+MAD_ROOT+'/view/template/component/container/'+this.options.templateType+'.ejs';
                
                // Render
                this.render();
                
                // *************************************************************
                // First side area
                // *************************************************************
                
                // Add the Password browser component
                var passwordBrowserController = this.addComponent(passbolt.password.controller.component.PasswordBrowserController, {
                    'id':'passbolt_password_password_browser'
                });
                passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
                passwordBrowserController.render();
                
                // *************************************************************
                // Main area
                // *************************************************************
                
                // Add the Category Chooser component
                this.addComponent(passbolt.password.controller.component.CategoryChooserController, {
                    'id':'passbolt_password_category_chooser'
                }, 'mad-container-first_side');
                
                
                // *************************************************************
                // Second side area
                // *************************************************************
                
                // Add vertical container to the second side area
                var secondSideContainer = this.addComponent(mad.controller.component.ContainerController, {
                    'id':'passbolt_password_second_side_container'
                    , 'template' : '//'+MAD_ROOT+'/view/template/component/container/vertical.ejs'
                }, 'mad-container-second_side');
                secondSideContainer.render();
                
                // Add the Password Information component
                secondSideContainer.addComponent(passbolt.password.controller.component.PasswordInformationController, {
                    'id':'passbolt_password_password_information'
                });
                
                // Add the Password Information component
                secondSideContainer.addComponent(passbolt.password.controller.component.AccessRightController, {
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
