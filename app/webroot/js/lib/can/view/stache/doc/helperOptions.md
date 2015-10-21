@typedef {{fn:can.stache.sectionRenderer,inverse:can.stache.sectionRenderer,hash:Object}} can.stache.helperOptions helperOptions
@parent can.stache.types 

@description The options argument passed to a [can.stache.helper helper function].

@option {can.stache.sectionRenderer} [fn] Renders the "truthy" subsection 
BLOCK.  `options.fn` is only available if the helper is called as a 
[can.stache.tags.section section] or [can.stache.tags.inverse inverse section] like:
`{{#helper}}` or `{{^helper}}.  The subsection BLOCK's 

Available if the helper is called 
as a section or inverse section. 
[can.stache.helpers.sectionHelper section helper] is called.  Call `fn` to
render the BLOCK with the specified `context`.

@option {can.stache.sectionRenderer} [inverse] Provided if a 
[can.stache.helpers.sectionHelper section helper] is called 
with [can.stache.helpers.else {{else}}].  Call `inverse` to
render the INVERSE with the specified `context`.

@option {Object.<String,*|String|Number>} hash An object containing all of the final 
arguments listed as `name=value` pairs for the helper.
	
	{{helper arg1 arg2 name=value other=3 position="top"}}

	options.hash = {
		name: <context_lookup>.value,
		other: 3,
		position: "top"
	}

@option {*} context The current context the stache helper is called within.

    
    
    var temp = can.stache(
      "{{#person.name}}{{helper}}{{/person.name}}");
    
    var data = {person: {name: {first: "Justin"}}};
    
    can.stache.registerHelper("helper", function(options){
    
      options.context === data.person //-> true
      
    })
    
    
    temp(data);
    
    

@option {can.view.Scope} scope An object that represents the current context and all parent 
contexts.  It can be used to look up [can.stache.key key] values in the current scope.

    var temp = can.stache(
      "{{#person.name}}{{helper}}{{/person.name}}");
    
    var data = {person: {name: {first: "Justin"}}};
    
    can.stache.registerHelper("helper", function(options){
    
      options.scope.attr("first")   //-> "Justin"
      options.scope.attr("person")  //-> data.person
      
    })
    
    
    temp(data);

@option {can.view.Options} options An object that represents the local stache helpers.  It can be used to look 
up [can.stache.key key] values

    var temp = can.stache("{{#person.name}}{{helper}}{{/person.name}}");
    
    var data = {person: {name: "Justin"}};
    
    can.stache.registerHelper("helper", function(options){
    
      options.options.attr("helpers.specialHelper") //-> function
      
    })
    
    
    temp(data, {
      specialHelper: function(){ ... }
    });