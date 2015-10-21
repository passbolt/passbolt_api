@constructor can.view.Scope
@inherits can.Construct
@parent can.view.static
@test can/view/scope/test.html
@plugin can/view/scope
@group can.view.Scope.types types

@description Create a lookup node for [can.mustache.key keys].

@signature `new can.view.Scope(context, [parent])`

@release 2.0.1


@param {*} context A value that represents the 
current context. This is often an object or observable and is the first
place a `key` is looked up.

@param {can.view.Scope} [parent] The parent scope. If a `key` value
is not found in the current scope, it will then look in the parent
scope.

@return {can.view.Scope} Returns a scope instance.

@body

## Use

A `can.view.Scope` represents a lookup context and parent contexts
that can be used to lookup a [can.mustache.key key] value.

If no parent scope is provided, only the scope's context will be 
explored for values.  For example:

    var data = {name: {first: "Justin"}},
    	scope = new can.view.Scope(data);
    
    scope.attr("name.first") //-> "Justin"
    scope.attr("length")     //-> undefined

However, if a `parent` scope is provided, key values will be
searched in the parent's context after the initial context is explored.  For example:

    var list = [{name: "Justin"},{name: "Brian"}],
    	justin = list[0];
    	
    var listScope = new can.view.Scope(list),
    	curScope = new can.view.Scope(justin, listScope)
    
    curScope.attr("name") //-> "Justin"
    curScope.attr("length") //-> 2

Use [can.view.Scope::add add] to easily create a new scope from a parent scope like:


    var list = [{name: "Justin"},{name: "Brian"}],
    	justin = list[0];
    	
    var curScope = new can.view.Scope(list).add(justin);
    
    curScope.attr("name") //-> "Justin"
    curScope.attr("length") //-> 2
