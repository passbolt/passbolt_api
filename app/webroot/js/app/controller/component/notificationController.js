steal( 
    MAD_ROOT+'/controller/componentController.js'
)
.then(
    function($){
        
        mad.controller.ComponentController.extend('passbolt.controller.component.NotificationController', 
		/** @static */
		{
            'defaults': {
                'label': 'NotificationController'
            }
        }
		/** @prototype */
        ,{			
			/**
			 * Listen the event passbolt_notify and display any 
			 * @param {Notification} notif
			 */
			// @todo notice that the event has to be writen with a-Z0-1_
			// create an object Notification
            '{mad.eventBus} passbolt_notify': function(elt, event, notif){
				this.setViewData({
					'status':	notif.status,
					'title':	notif.title,
					'message':	notif.message,
					'data':		notif.data
				});
				this.render();
			}
		});
        
        // Augment the notification controller with the Singleton Object
		// @todo move this feature in the extend function, override the extend function of the class Class
        passbolt.controller.component.NotificationController.augment('mad.core.Singleton');
    }
);
