steal(
    MAD_ROOT+'/controller/component/containerController.js'
)
.then( 
    function($){
        
        mad.controller.component.ContainerController.extend('mad.controller.component.PopupController', 
		/** @static */
		{
            'defaults' : {
                'label':		'Popup Container Controller',
				'cssClasses':	'js_popup'
            },
			
			/**
			 * Get a new popup container
			 * @param {array} options Options to pass to the popup constructor
			 * @return {mad.controller.component.PopupController}
			 */
			'get': function(options){
				var popupId = uuid();
				var $popup = $('<div id="'+popupId+'" class="js_popup"/>').appendTo(mad.app.element);
				return new mad.controller.component.PopupController($popup, options);
			}
        }
		/** @prototype */
        ,{
            
			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */
			
			/**
			 * Listen to the user interaction click with the close button
			 * @return {void}
			 */
			'.js_popup_close click': function(){
				this.element.remove();
			}
			
        });
    }
);
