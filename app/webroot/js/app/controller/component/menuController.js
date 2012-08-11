steal( 
    MAD_ROOT+'/controller/componentController.js'
)
.then(
    function($){
        
        mad.controller.ComponentController.extend('passbolt.controller.component.MenuController', 
		/** @static */
		{
            'defaults': {
                'label': 'MenuController'
            }
        }
		/** @prototype */
        ,{			
            'li click': function(elt, event){
				mad.eventBus.trigger('js-wk-controller-select');
			}
		});
    }
);
