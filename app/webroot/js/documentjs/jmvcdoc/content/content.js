steal('jquery/controller',
	'jquery/lang/observe/delegate',
	'jquery/view/ejs',
	'documentjs/jmvcdoc/highlight',
	
	'documentjs/jmvcdoc/resources/helpers.js',
	'documentjs/jmvcdoc/models/search.js',
	'./doc_updated.js').then(

	'./views/attribute.ejs',
	'./views/class.ejs',
	'./views/constructor.ejs',
	'./views/favorite.ejs',
	'./views/function.ejs',
	'./views/page.ejs', 
	'./views/results.ejs', 
	'./views/top.ejs', 
		function($){

/**
 * @class Jmvcdoc.Content
 */
$.Controller('Jmvcdoc.Content',
/* @Static */
{
	defaults : {
	
	}
},
/* @Prototype */
{
	"{clientState} who set" : function(clientState, ev, val){
		this._currentPage = val;
		// write out who this is
		this.element.html("Loading ...")
			.scrollTop(0);
		Doc.findOne({
			name: val
		}, this.proxy(function(docData){
			if(Doc.dataDeferred.isResolved()){
				this.show(docData)
			} else {
				Doc.dataDeferred.then(this.proxy('show',docData))
			}
		}));
		
	},
	show : function(docData){
		document.title = docData.title || docData.name.replace(/~/g,".");
		this.element.html("//documentjs/jmvcdoc/content/views/" + docData.type.toLowerCase() + ".ejs", docData, DocumentationHelpers)
			.trigger("docUpdated",[docData]);
		$('#results a.open').removeClass('open')
		$('#results a[href="'+location.hash+'"]').addClass('open');
		
		if(_gaq){
			_gaq.push(['_trackPageview', document.title]);
		}
	}
});

});