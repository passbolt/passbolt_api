steal( 
    MAD_ROOT+'/controller/component/buttonController.js'
)
.then(
    function($){
        
		/*
        * @class passbolt.controller.component.CopySecretButtonController
        * @inherits {mad.controller.ComponentController}
        * @parent index
        */
        mad.controller.component.ButtonController.extend('passbolt.controller.component.CopySecretButtonController', 
		/** @static */
		{
            'defaults': {
                'label': 'Copy Secret To Clipboard'
            }
        }
		/** @prototype */
        ,{
			
			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */
			
			/**
			 * Observe when a resource is focused
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} resource The focused resource
			 * @return {void}
			 */
			'{mad.eventBus} resource_focused': function(element, evt, resource)
            {
				if(this.value == resource.id){
					this.setState('ready');
				}
            },
			
			/**
			 * Observe when a resource is unfocused
			 * @param {jQuery} element The source element
			 * @param {Event} event The jQuery event
			 * @param {string} resource The unfocused resource
			 * @return {void}
			 */
			'{mad.eventBus} resource_unfocused': function(element, evt, resource)
            {
				if(this.value == resource.id){
					this.setState('hidden');
				}
            }
			
		});
    }
);
