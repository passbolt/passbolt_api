steal( 
    'jquery/view/ejs',
    'app/controller/component/passwordBrowserController.js',
    'mad/controller/component/workspaceController.js',
    'mad/view/template/component/container/three-column.ejs',
    'mad/view/template/component/container/vertical.ejs'
)
.then(
    function($){
        
        /*
        * @class passbolt.activity.controller.ActivityWorkspaceController
		* @inherits {mad.controller.component.WorkspaceController}
        * @parent index 
		* 
        * @constructor
        * Creates a new ActivityWorkspaceController
        * @return {passbolt.activity.controller.ActivityWorkspaceController}
        */
        mad.controller.component.WorkspaceController.extend('passbolt.activity.controller.ActivityWorkspaceController', {
            'defaults' : {
                'label': 'WorkspaceController'
                , 'templateType' : 'three-column'
            }
        }
        ,{
            
            'init' : function(el, options)
            {
				return;
                this.options = $.extend(true, {}, this.options, options);
                // Use the templateType to define the template
                this.options.templateUri = '//app/view/template/workspace.ejs';
                // Init the controller
                this._super();
				
                // Render
                this.render();
				
                // *************************************************************
                // First side area
                // *************************************************************
                
                // Add the Category Chooser component
                var categoryChooser = this.addComponent(passbolt.password.controller.component.CategoryChooserController, {
                    'id':'passbolt_activity_category_chooser'
                }, 'workspace-sidebar-first');
                categoryChooser.render();
                
                // *************************************************************
                // Main area
                // *************************************************************
                
                // Add the Password browser component
                var passwordBrowserController = this.addComponent(passbolt.password.controller.component.PasswordBrowserController, {
                    'id':'passbolt_activity_password_browser'
                }, 'workspace-main');
                passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
                passwordBrowserController.render();
                
                // *************************************************************
                // Second side area - create a container to be able to add other tool after
                // *************************************************************
                
                // Add vertical container to the second side area
                var secondSideContainer = this.addComponent(mad.controller.component.ContainerController, {
                    'id':'passbolt-activity-second-side-container'
                    , 'templateUri' : '//'+MAD_ROOT+'/view/template/component/container/vertical.ejs'
                }, 'workspace-sidebar-second');
                secondSideContainer.render();
                
                // Add the Password Information component
                var passwordInfoComponent = secondSideContainer.addComponent(passbolt.password.controller.component.PasswordInformationController, {
                    'id':'passbolt-activity-password-information'
                });
				passwordInfoComponent
					.decorate('mad.helper.component.BoxDecorator')
					.render();
                
                // Add the Password Access Right component
                var passwordAccessRightComponent = secondSideContainer.addComponent(passbolt.password.controller.component.AccessRightController, {
                    'id':'passbolt_activity_access_right'
                });
				passwordAccessRightComponent
					.decorate('mad.helper.component.BoxDecorator')
					.render();
            }
            
            , index:function()
            {
                console.log('Execute function index of the activity workspace controller, with the following arguments');
                console.dir(arguments);
            }
            
        });
        
    }
);
