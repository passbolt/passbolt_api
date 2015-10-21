@function can.Model.bind bind
@parent can.Model.static
@description Listen for events on a Model class.

@signature `can.Model.bind(eventType, handler)`
@param {String} eventType The type of event.  It must be
`"created"`, `"updated"`, `"destroyed"`.
@param {function} handler A callback function
that gets called with the event and instance that was
created, destroyed, or updated.
@return {can.Model} The model constructor function.

@body
`bind(eventType, handler(event, instance))` listens to
__created__, __updated__, __destroyed__ events on all
instances of the model.

```
Task.bind("created", function(ev, createdTask){
this //-> Task
 createdTask.attr("name") //-> "Dishes"
})

new Task({name: "Dishes"}).save();
```
