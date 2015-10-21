@function can.compute.async
@parent can.compute
@release 2.1
@hide

Create a compute that can set its value after the computed function has been called.

@signature `can.compute.async(initialValue, computed(currentValue, setValue(newValue) )`

@param {*} The initial value of the compute.

@param {can.compute.asyncComputer} computed A function 
that returns the current value of the compute and can optionally later call 
its `setValue` callback to update the value.

@return {can.computed} Returns a compute, but a compute that will 
possibly not have the correct value unless it is bound to.

@body

## Use

The following compute is a live list of todos for a given 
userId. `todos` value would alternate between `null` and a Todo.List as `userId` changes.


    var userId = can.compute(5)
    
    var todos = can.compute.async(null, function(oldTodoList, setValue){
      Todo.findAll({ userId: userId() }, function(todos){
        setValue(todos)
      });
      return null;
    });


The following replaces the list in place:

    var userId = can.compute(5)
    
    var todos = can.compute.async(new Todo.List(), function(todoList, setValue){
      todoList.replace( Todo.findAll({ userId: userId() })
      return todoList;
    });
