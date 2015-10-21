@function can.Model.prototype.unbind unbind
@parent can.Model.prototype
@description Stop listening to events on this Model.
@signature `model.unbind(eventName[, handler])`
@param {String} eventName The event to unbind from.
@param {function} [handler] A handler previously bound with `bind`.
If __handler__ is not passed, `unbind` will remove all handlers
for the given event.
@return {can.Model} The Model, for chaining.

@body
`unbind(eventName, handler)` removes a listener
attached with [can.Model::bind].

```
var handler = function(ev, createdTask){

}
task.bind("created", handler)
task.unbind("created", handler)
```

You have to pass the same function to `unbind` that you
passed to `bind`.

Unbind will also remove the instance from the store
if there are no other listeners.