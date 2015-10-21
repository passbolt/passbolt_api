@constructor can.Model
@parent canjs
@download can/model
@test can/model/test.html
@link ../docco/model/model.html docco

@signature `can.Model([name,] staticProperties, instanceProperties)`
Create a can.Model constructor. (See [can.Construct] for more details on this syntax.)
@param {String} [name] If given, this will be the globally-available name of the constructor function.
@param {Object} staticProperties The static properties of the class. See below for properties with
special meanings to `can.Model`.
@param {Object} instanceProperties The instance properties of instances of the class. These will usually
be functions.
@return {Function} A can.Model constructor.

@signature `new can.Model([options])`
Creates a new instance of _ModelConstructor_.
@param {Object} [options] Options to pass to `setup` or `init`.
@return {can.Model} A new instance of _ModelConstructor_.

@body
Model adds service encapsulation to [can.Map].  Model lets you:

 - Get and modify data from the server
 - Listen to changes by the server
 - Keep track of all instances and prevent duplicates in the non-leaking [can.Model.store]
 
## Get and modify data from the server

can.Model makes connecting to a JSON REST service 
really easy.  The following models `todos` by
describing the services that can create, retrieve,
update, and delete todos. 

```
Todo = can.Model.extend({
  findAll: 'GET /todos.json',
  findOne: 'GET /todos/{id}.json',
  create:  'POST /todos.json',
  update:  'PUT /todos/{id}.json',
  destroy: 'DELETE /todos/{id}.json' 
},{});
```

This lets you create, retrieve, update, and delete
todos programatically:

__Create__

Create a todo instance and 
call <code>[can.Model::save save]\(success, error\)</code>
to create the todo on the server.
    
```
// create a todo instance
var todo = new Todo({name: "do the dishes"})

// save it on the server
todo.save();
```

__Retrieve__

Retrieve a list of todos from the server with
<code>[can.Model.findAll findAll]\(params, success(items), error\)</code>: 
    
```
Todo.findAll({}, function( todos ){

  // print out the todo names
  $.each(todos, function(i, todo){
    console.log( todo.name );
  });
});
```

Retrieve a single todo from the server with
<code>[can.Model.findOne findOne]\(params, success(item), error\)</code>:

```
Todo.findOne({id: 5}, function( todo ){

  // print out the todo name
  console.log( todo.name );
});
```

__Update__

Once an item has been created on the server,
you can change its properties and call
<code>save</code> to update it on the server.

```
// update the todos' name
todo.attr('name','Take out the trash')
  
// update it on the server
todo.save()
```

__Destroy__

Call <code>[can.Model.prototype.destroy destroy]\(success, error\)</code>
to delete an item on the server.

    todo.destroy()

## Listen to changes in data

Listening to changes in data is a critical part of 
the [http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller Model-View-Controller]
architecture.  can.Model lets you listen to when an item is created, updated, destroyed
or its properties are changed. Use 
<code>Model.[can.Model.bind bind]\(eventType, handler(event, model)\)</code>
to listen to all events of type on a model and
<code>model.[can.Model.prototype.bind bind]\(eventType, handler(event)\)</code>
to listen to events on a specific instance.

__Create__

```
// listen for when any todo is created
Todo.bind('created', function( ev, todo ) {...})

// listen for when a specific todo is created
var todo = new Todo({name: 'do dishes'})
todo.bind('created', function( ev ) {...})
```
  
__Update__

```
// listen for when any todo is updated
Todo.bind('updated', function( ev, todo ) {...})

// listen for when a specific todo is created
Todo.findOne({id: 6}, function( todo ) {
  todo.bind('updated', function( ev ) {...})
})
```

__Destroy__

```
// listen for when any todo is destroyed
Todo.bind('destroyed', function( ev, todo ) {...})

// listen for when a specific todo is destroyed
todo.bind('destroyed', function( ev ) {...})
```

__Property Changes__

```
// listen for when the name property changes
todo.bind('name', function(ev){  })
```

__Listening with can.Control or can.Component__

You can use can.Control or the events property of can.Component to listen to model changes like:

```
Todos = can.Control.extend({
  "{Todo} updated" : function(Todo, ev, todo) {...}
})
```
