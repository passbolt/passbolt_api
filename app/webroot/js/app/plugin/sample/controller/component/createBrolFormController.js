steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/view/template/component/button.ejs'
)
.then(
	function($){
        
		mad.controller.ComponentController.extend('passbolt.sample.controller.component.CreateBrolFormController',
		/** @static */
		{
			'defaults': {
				'label':			'Create Brol Form'
			}
		},
		/** @prototype */
		{			
			// Construcor
			'init': function(el, options)
			{
				this._super();
				this.render();
				
				new mad.controller.component.InputController($('#js_label_input', this.element), {});
				new mad.controller.component.ButtonController($('#js_submit_button', this.element), {}).render();
			},
			
			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */
			
			/**
			 * Listen to the event click on the DOM button element
			 * @return {void}
			 */
			'#js_submit_button click': function() {
				var labelInputComponent = mad.app.getComponent('js_label_input');
				var brol = new passbolt.plugin.sample.model.Brol({
					'label':labelInputComponent.getValue()
				});
				
			}
		});
        
	}
);
