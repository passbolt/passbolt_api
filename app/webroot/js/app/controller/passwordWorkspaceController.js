steal( 
    'jquery/view/ejs',
    
    MAD_ROOT+'/controller/component/workspaceController.js',
    
    'app/controller/component/passwordBrowserController.js',
    'app/controller/component/categoryChooserController.js'
//    'plugin/password/controller/component/passwordInformationController.js',
//    'plugin/password/controller/component/accessRightController.js'
)
.then(
    function($){
        
        /*
        * @class passbolt.controller.PasswordWorkspaceController
        * @parent index 
        * @constructor
        * Creates a new PasswordWorkspaceController
        * @return {passbolt.controller.PasswordWorkspaceController}
        */
        mad.controller.component.WorkspaceController.extend('passbolt.controller.PasswordWorkspaceController', {
            'defaults' : {
                'label': 'Password'
            }
        }
        ,{
            
            'init' : function(el, options)
            {
                this.options = $.extend(true, {}, this.options, options);
                // Init the controller
                this._super();
				
                // Render
                this.render();
                // *************************************************************
                // First side area
                // *************************************************************
                
                // Add the Category Chooser component
                var categoryChooser = this.addComponent(passbolt.controller.component.CategoryChooserController, {
                    'id':'passbolt_password_category_chooser'
                }, 'workspace-sidebar-first');
                categoryChooser.render();
                
                // *************************************************************
                // Main area
                // *************************************************************
                
                // Add the Password browser component
                var passwordBrowserController = this.addComponent(passbolt.controller.component.PasswordBrowserController, {
                    'id':'passbolt_password_password_browser'
                }, 'workspace-main');
                passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
                passwordBrowserController.render();
                
				return;
				
                // *************************************************************
                // Second side area - create a container to be able to add other tool after
                // *************************************************************
                
                // Add vertical container to the second side area
                var secondSideContainer =  new mad.controller.component.ContainerController(this.element.find('.workspace-sidebar-second'),{
                    'id':'passbolt-password-second-side-container'
                    , 'templateUri' : '//'+MAD_ROOT+'/view/template/component/container/vertical.ejs'
                });
                secondSideContainer.render();
//                var secondSideContainer = this.addComponent(mad.controller.component.ContainerController, {
//                    'id':'passbolt-password-second-side-container'
//                    , 'templateUri' : '//'+MAD_ROOT+'/view/template/component/container/vertical.ejs'
//                }, 'workspace-sidebar-second');
//                secondSideContainer.render();
                
                // Add the Password Information component
                var passwordInfoComponent = secondSideContainer.addComponent(passbolt.password.controller.component.PasswordInformationController, {
                    'id':'passbolt-password-password-information'
                });
				passwordInfoComponent
					.decorate('mad.helper.component.BoxDecorator')
					.render();
                
                // Add the Password Access Right component
                var passwordAccessRightComponent = secondSideContainer.addComponent(passbolt.password.controller.component.AccessRightController, {
                    'id':'passbolt_password_access_right'
                });
				passwordAccessRightComponent
					.decorate('mad.helper.component.BoxDecorator')
					.render();
            },
            
            'index': function(a, b, c)
            {
                console.log('Execute function index of the password workspace controller, with the following arguments');
                console.dir(arguments);
            }
            
        });
        
    }
);
