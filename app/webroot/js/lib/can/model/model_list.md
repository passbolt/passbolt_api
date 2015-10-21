@constructor can.Model.List
@inherits can.List
@parent canjs
@download can/model
@test can/model/qunit.html

@description A list connected to can.Model's CRUD abilities.

@signature `new can.Model.List()`

Create an empty model list.

@signature `new can.Model.List( [models] )`

Create a model list with the provided model instances.

@param {Array.<can.Model|Object>} [models] An array of [can.Model] instances
or Objects that will be converted to the list's [can.List.Map Map type].

@signature `new can.Model.List( deferred )`

Create a model list with the results of `deferred`.

@param {Deferred.Array.<can.Model|Object>} deferred A promise that will
resolve to an array. Once the promise resolves, the `List` will have its
contents replaced as if `new can.Model.List(array)` had been called.

@signature `new can.Model.List( params )`

Create an initially empty model list, but use the model's [can.Model.findAll findAll]
to get a list of models and add it to this empty list.

@param {Object} params Params that are passed to
the [can.List.Map Map property's] [can.Model.findAll findAll] method.

@body

## Use

`can.Model.List` is a [can.List] associated with a [can.Model]. `can.Model.List`s
are just like [can.List] except they have a few super-powers:

 - They are returned by [can.Model.findAll findAll].
 - They automatically remove "destroyed" items.
 - They can retrieve items from the server (similar to `findAll`).

## Defining a model list

When [can.Model] is extended,  `can.Model.List` is automatically extended and set as that model's
[can.Model.static.List List property]. Typically, a `can.Model.List` is
defined for you. For example:

    Task = can.Model.extend({
      findAll: "/tasks"
    },{})
    new Task.List instanceof can.Model.List //-> true

This List type is returned by [can.Model.findAll findAll]:

    Task.findAll({}, function(tasks){
      tasks instanceof Task.List //-> true
    })

The List's [can.List.Map Map] property points to the extended [can.Model]:

    Task = can.Model.extend({
      findAll: "/tasks"
    },{});
    Task.List.Map //-> Task

Defining custom `can.Model.Lists` allows you to extend lists with helper
functions for a list of a specific type. The following
adds the ability to retrieve the number of completed and remaining todos:

    Todo.List = Todo.List.extend({
        completed: function() {
            var completed = 0;
            this.each(function(i, todo) {
                completed += todo.attr('complete') ? 1 : 0
            })
            return completed;
        },
        remaining: function() {
            return this.attr('length') - this.completed();
        }
    })

    Todo.findAll({}, function(todos) {
        todos.completed() // -> 0
        todos.remaining() // -> 2
    });

## Creating a model list instance

If you use [can.Model.findAll findAll], it calls back with and resolves to a model list:

    var def = Task.findAll({}, function(tasks){
      tasks instanceof Task.List //-> true
    });

    def.then(function(tasks){
      tasks instanceof Task.List //-> true
    })

To create an empty model list yourself, use `new {model_name}.List()` like:

    var todos = new Todo.List();
    todos.attr("length") //-> 0

You can also pass an array to instantiate the list with the array data using
`new {model_name}.List(ARRAY)`. It can be either an array of models:

    var todo1 = new Todo( { name: "Do the dishes", id: 1 } ),
        todo2 = new Todo( { name: "Wash floors", id: 2 } )
    var todos = new Todo.List( [todo1, todo2] );

...or an array of objects. If objects are provided, model list will convert
them to models. The following does the same thing as the previous example:

    var todos = new Todo.List( [
      { name: "Do the dishes", id: 1 },
      { name: "Wash floors", id: 2 }
    ] );

If, instead of using an array, a model list is created with a plain
JavaScript object like:

    var todos = new Todo.List({due: "today"});

The object is assumed to be
parameters to the list's Model's [can.Model.findAll findAll]
method. An empty list will be returned, but `Todo.findAll` will
be called. The items it returns will be inserted into the
list.

    var todos = new Todo.List({due: "today"});
    todos.attr("length") //-> 0

    todos.bind("length", function(){
      console.log("items added to the list")
    })


## Removing models from model list

One advantage that `can.Model.List` has over a traditional `can.List`
is that when you destroy a model, if it is in that list, it will automatically
be removed from the list.

    // Listen for when something is removed from the todos list.
    todos.bind("remove", function( ev, oldVals, indx ) {
        console.log("todo"+indx+" removed")
    })

    todos.attr(0).destroy(); // console shows "todo 0 removed"


