@function can.Model.unbind unbind
@parent can.Model.static
@description Stop listening for events on a Model class.

@signature `can.Model.unbind(eventType, handler)`
@param {String} eventType The type of event. It must be
`"created"`, `"updated"`, `"destroyed"`.
@param {function} handler A callback function
that was passed to `bind`.
@return {can.Model} The model constructor function.

@body
`unbind(eventType, handler)` removes a listener
attached with [can.Model.bind].

```
var handler = function(ev, createdTask){

}
Task.bind("created", handler)
Task.unbind("created", handler)
```

You have to pass the same function to `unbind` that you
passed to `bind`.