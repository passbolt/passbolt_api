@property {*} can.List.prototype.reason 
@parent can.List.plugins.promise

@option {*} The reason the list's deferred was rejected.  Read it via
[can.Map::attr attr].

@body

## Use

    var def = new can.Deferred();
	var list = new can.List( def );
	
	list.fail(function(){
	  list.attr("reason") //-> {message: "epic fail"}
	});
    
    def.reject({message: "epic fail"});

