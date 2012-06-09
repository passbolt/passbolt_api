steal( 'jquery/controller',
	   'jquery/view/ejs',
	   'jquery/controller/view',
	   'lb/models' )
.then( './views/init.ejs', 
       './views/countdown.ejs', 
       function($){

/**
 * @class lb.Countdown.List
 * @parent index
 * @inherits jQuery.Controller
 * Lists countdowns and lets you destroy them.
 */
$.Controller('lb.Countdown.List',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(){
		this.element.html(this.view('init',lb.Models.Countdown.findAll()) )
	},
	'.destroy click': function( el ){
		if(confirm("Are you sure you want to destroy?")){
			el.closest('.countdown').model().destroy();
		}
	},
	"{lb.Models.Countdown} destroyed" : function(Countdown, ev, countdown) {
		countdown.elements(this.element).remove();
	},
	"{lb.Models.Countdown} created" : function(Countdown, ev, countdown){
		this.element.append(this.view('init', [countdown]))
	},
	"{lb.Models.Countdown} updated" : function(Countdown, ev, countdown){
		countdown.elements(this.element)
		      .html(this.view('countdown', countdown) );
	}
});

});