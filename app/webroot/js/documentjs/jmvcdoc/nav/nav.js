steal('jquery/controller',
	'jquery/lang/observe/delegate',
	'jquery/view/ejs',
	'documentjs/jmvcdoc/models/search.js',
		'documentjs/jmvcdoc/resources/helpers.js',
		'documentjs/jmvcdoc/tooltip.js',
		function($){

/**
 * @class Jmvcdoc.Nav
 * 
 * listens for a history change, gets object it represents, and draws it ....
 */
$.Controller('Jmvcdoc.Nav',
/* @Static */
{
	defaults : {
	
	}
},
/* @Prototype */
{
	"{$.route} who set" : function(clientState, ev, val){
		if(Doc.dataDeferred.isResolved()){
			this.navFor(val)
		} else {
			Doc.dataDeferred.then(this.proxy('navFor',val))
		}
	},
	navFor : function(val){
		// write out who this is
		var item = Doc.findOne({
			name: val
		}),
		focus = item;
		
		// get parent ...
		
		while(focus.parents && 
			( !focus.childDocs || !focus.childDocs.length || /static|prototype/i.test( focus.type ) ) ) {
			focus =  Doc.findOne({name: focus.parents[0]})
		}
		var path = [focus], curParent = focus;
		while( curParent.parents && curParent.parents.length ){
			curParent = Doc.findOne({name: curParent.parents[0]});
			path.unshift(curParent);
		}
		
		// get all children ....
		var list = focus.children().slice(0),
			i=0,
			args,
			children,
			hasStaticOrPrototype = false;
		// get static children notes
		while(i < list.length){
			// if we have static or prototype, we need to insert those into the
			// list after the prototype
			if(/static|prototype/.test( list[i].type ) ) {
				args = [i+1,0];
				children = list[i].children()
				args.push.apply(args, children);
				list.splice.apply(list, args);
				i = i+children.length+1;
				hasStaticOrPrototype = true;
			} else {
				i++;
			}
		}
		
		// get selected parents ...
		
		// make list's html:
		
		this.element.html("//documentjs/jmvcdoc/nav/views/results.ejs", {
			list: list,
			selected: path,
			hide: false,
			hasStaticOrPrototype : hasStaticOrPrototype
		}, DocumentationHelpers);
		
		// highlight selected guy ...
		steal.html.ready();
	},
	".remove click" : function(el, ev){
		ev.preventDefault();
		var content = el.closest('.content').prevAll('.content').eq(0);
		if(content.length){
			window.location.href = content.find('a').attr('href');
		} else {
			window.location.hash = ""
		}
	},
	"{$.route} search set" : function(clientState, ev, val){
		if(Doc.dataDeferred.isResolved()){
			this.searchFor(val)
		} else {
			Doc.dataDeferred.then(this.proxy('searchFor',val))
		}
	},
	searchFor : function(val){
		var res = Doc.findAll({
			search: val
		});
		this.element.html("//documentjs/jmvcdoc/nav/views/results.ejs", {
			list: res,
			selected: [],
			hide: false,
			hasStaticOrPrototype : true
		}, DocumentationHelpers);
	},
	"a mouseover": function( el ) {
		this._highlight(el)
	},
	"#results a mouseover" : function(el){
		var name = el.attr('data-name');
		// track which tooltip we should be showing
		this.showTooltip = name;
		
		Doc.findOne({
			name: name
		}, this.proxy(function(data){
			
			if(data.description && this.showTooltip == name){
				//$("#tooltip").show().tooltip({message: data.description, of:  el})
			}
			
			
			//console.log(data.description)
		}))
		
	},
	"a mouseout": function( el ) {
		el.removeClass("highlight")
		this.showTooltip = null;
		//$("#tooltip").hide()
	},
	_highlight: function( el ) {
		if (!this._isInvalidMenuItem(el) ) {
			el.addClass("highlight")
		}
	},
	_isInvalidMenuItem: function( el ) {
		return (el.hasClass("prototype") || el.hasClass("static"))
	}
})

}, './views/results.ejs');