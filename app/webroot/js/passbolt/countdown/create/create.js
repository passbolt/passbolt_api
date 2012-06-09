steal( 'jquery/controller',
       'jquery/view/ejs',
	   'jquery/dom/form_params',
	   'jquery/controller/view',
	   'lb/models' )
	.then('./views/init.ejs', function($){

/**
 * @class lb.Countdown.Create
 * @parent index
 * @inherits jQuery.Controller
 * Creates countdowns
 */
$.Controller('lb.Countdown.Create',
/** @Prototype */
{
	init : function(){
		this.element.html(this.view());
	},
	submit : function(el, ev){
		ev.preventDefault();
		this.element.find('[type=submit]').val('Creating...')
		new lb.Models.Countdown(el.formParams()).save(this.callback('saved'));
	},
	saved : function(){
		this.element.find('[type=submit]').val('Create');
		this.element[0].reset()
	}
})

});