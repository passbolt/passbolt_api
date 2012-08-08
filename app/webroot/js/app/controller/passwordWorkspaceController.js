steal( 
    'jquery/view/ejs',
    
    MAD_ROOT+'/controller/component/workspaceController.js',
    'app/controller/component/passwordBrowserController.js',
    'app/controller/component/categoryChooserController.js'
)
.then(
    function($){
        
        /*
        * @class passbolt.controller.PasswordWorkspaceController
        * @inherits {mad.controller.component.WorkspaceController}
        * @parent index 
		* 
        * @constructor
        * Creates a new PasswordWorkspaceController
        * @return {passbolt.controller.PasswordWorkspaceController}
        */
        mad.controller.component.WorkspaceController.extend('passbolt.controller.PasswordWorkspaceController', 
		/** @static */
		{
            'defaults' : {
                'label': 'Password'
            }
        },
        /** @prototype */
		{
            // constructor like
            'init' : function(el, options)
            {
                this._super();
                this.render();	// render the component to be used by the others
				
                // *************************************************************
                // First side area
                // *************************************************************
                
                // Add the Category Chooser component
                var categoryChooser = this.addComponent(passbolt.controller.component.CategoryChooserController, {
                    'id':'js_passbolt_password_category_chooser'
                }, 'js_workspace_sidebar_first');
                categoryChooser.render();
                
                // *************************************************************
                // Main area
                // *************************************************************
                
                // Add the Password browser component
                var passwordBrowserController = this.addComponent(passbolt.controller.component.PasswordBrowserController, {
                    'id':'js_passbolt_password_browser'
                }, 'js_workspace_main');
                passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
                passwordBrowserController.render();
                
                // *************************************************************
                // Second side area - create a container to be able to add other tool after
                // *************************************************************
                
                // Add vertical container to the second side area
                var secondSideContainer = new mad.controller.component.ContainerController($('.js_workspace_sidebar_second', this.element), {
                    'id':'js_passbolt_password_sidebar_second'
                    , 'templateUri' : '//'+MAD_ROOT+'/view/template/component/container/vertical.ejs'
					, 'readyState': 'hidden'
                });
                secondSideContainer.render();
            },
            
			/**
			 * Demonstration function to prove the dispatcher
			 * @dev
			 */
			'index': function(a, b, c)
            {
                console.log('Execute function index of the password workspace controller, with the following arguments');
                console.dir(arguments);
            },
			
			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */
			
			/**
			 * Observe when a resource is unselected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQury event
			 * @param {string} category The unselected Resource
			 * @return {void}
			 */
			'{passbolt.eventBus} resource_unselected': function(element, event, resourceId)
			{
				// The resource is no more selected, reinit the password workspace
				// component to its intitial state (ready)
				this.state.setState('ready');
			},
			
			/**
			 * Observe when a resource is selected
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQury event
			 * @param {string} category The selected Resource
			 * @return {void}
			 */
			'{passbolt.eventBus} resource_selected': function(element, event, resourceId)
			{
				// A resource has been selected, change the state of the password Workspace
				// controller
				this.setState('resourceSelected');
				// Another way is to drive the state of all the component from here. I choose
				// for a first hit to lets the component manage their own states changement
//				var secondSideBar = this.getApp().getComponent('js_passbolt_password_sidebar_second');
//				secondSideBar.changeStatus('show');
			},

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */
			
			/**
			 * Listen to the change relative to the state Ready.
			 * The ready state is fired automatically after the Component is rendered
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'listenReady': function(go){
				if(go){
					$('.js_workspace_main', this.element)
						.removeClass('grid_7')
						.addClass('grid_13 omega');
					$('.js_workspace_sidebar_second', this.element)
						.removeClass('grid_6 omega')
						.addClass('grid_0');
				}
				else{
					//nothing
				}
			},
			
			/**
			 * Listen to the change relative to the state ResourceSelected
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'listenResourceSelected': function(go){
				if(go){
					$('.js_workspace_main', this.element)
						.removeClass('grid_13 omega')
						.addClass('grid_7');
					$('.js_workspace_sidebar_second', this.element)
						.removeClass('grid_0')
						.addClass('grid_6 omega');
				}
				else{
					//nothing
				}
			}

        });
        
    }
);
