@property {can.Model.List} can.Model.static.List List
@parent can.Model.static

@description Specifies the type of List that [can.Model.findAll findAll]
should return.

@option {can.Model.List} A can.Model's List property is the
type of [can.List List] returned
from [can.Model.findAll findAll]. For example:

```
Task = can.Model.extend({
 findAll: "/tasks"
},{})

Task.findAll({}, function(tasks){
 tasks instanceof Task.List //-> true
})
```

Overwrite a Model's `List` property to add custom
behavior to the lists provided to `findAll` like:

```
Task = can.Model.extend({
 findAll: "/tasks"
},{})
Task.List = Task.List.extend({
 completed: function(){
   var count = 0;
   this.each(function(task){
     if( task.attr("completed") ) count++;
   })
   return count;
 }
})

Task.findAll({}, function(tasks){
 tasks.completed() //-> 3
})
```

When [can.Model] is extended,
[can.Model.List] is extended and set as the extended Model's
`List` property. The extended list's [can.List.Map Map] property
is set to the extended Model.  For example:

```
Task = can.Model.extend({
 findAll: "/tasks"
},{})
Task.List.Map //-> Task
```