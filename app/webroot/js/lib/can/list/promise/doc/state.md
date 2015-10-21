@property {String} can.List.prototype.state 
@parent can.List.plugins.promise

@option {String} The state of the list's promise.  Read it via
[can.Map::attr attr].  It will be one of the following:

 - undefined - the list does not have a deferred
 - "pending" - the list's promise is pending
 - "resolved" - the list's promise was resolved
 - "rejected" - the list's promise was rejected

@body

## Use

    var def = new can.Deferred();
	var list = new can.List( def );
	
	list.attr('state') //-> "pending"

