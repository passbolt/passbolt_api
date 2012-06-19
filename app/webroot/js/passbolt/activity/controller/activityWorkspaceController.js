steal( 
    'jquery/view/ejs',
    'lb/core/controller/workspaceController.js'
)
.then(
    'lb/core/view/template/container/three-column.ejs',
    'lb/core/view/template/container/vertical.ejs',
    function($){
        
        /*
        * @class passbolt.activity.controller.ActivityWorkspaceController
        * @parent index 
        * @constructor
        * Creates a new ActivityWorkspaceController
        * @return {passbolt.activity.controller.ActivityWorkspaceController}
        */
        lb.core.controller.WorkspaceController.extend('passbolt.activity.controller.ActivityWorkspaceController', {
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
                    'id':'passbolt_activity_password_browser'
                }); 
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
                    'id':'passbolt_activity_second_side_container'
                    , 'template' : '//lb/core/view/template/container/vertical.ejs'
                }, 'lb-container-second_side');
                secondSideContainer.render();
                
                // Add the Password Information component
                secondSideContainer.addComponent(passbolt.password.controller.PasswordInformationController, {
                    'id':'passbolt_activity_password_information'
                });
                
                // Add the Password Information component
                secondSideContainer.addComponent(passbolt.password.controller.AccessRightController, {
                    'id':'passbolt_activity_access_right'
                });
                
                this._super();
            }
            
            , index:function()
            {
                console.log('Execute function index of the activity workspace controller, with the following arguments');
                console.dir(arguments);
            }
            
        });
        
    }
);
