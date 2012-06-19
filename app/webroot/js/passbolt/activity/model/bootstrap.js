steal( 
    'lb/core/model/moduleBootstrap.js'
    , 'passbolt/activity/controller/activityWorkspaceController.js'
    , 'passbolt/activity/controller/passwordActivityController.js'
)
.then( 
    function($){
        
        lb.core.model.BootstrapInterface.extend('passbolt.activity.model.Bootstrap', {},
        {
            'init': function()
            {
                var passwordWorkspaceSecondSideContainer = lb.app.getComponent('passbolt_password_second_side_container');
                var appWorkspacesContainer = lb.app.getComponent('app_workspaces_container');
                
                // Add the Password Information component
                passwordWorkspaceSecondSideContainer.addComponent(passbolt.activity.controller.PasswordActivityController, {
                    'id':'passbolt_activity_password_activity_controller'
                });
                
                // Add the Activity workspace component to the application
                var activityWorkspaceController = appWorkspacesContainer.addComponent(passbolt.activity.controller.ActivityWorkspaceController, {
                    'id':'activity-activityWorkspace-controller',
                    'label':'Activity'
                });
            }
        });
    }
);
