@typedef {} can.fixture.types.Store Store
@parent can.fixture.types

Contains an array of items and methods for 
finding, adding, updating, and removing items from the store. Many of those
methods are designed to work with `can.fixture` and simulate [can.Model]'s CRUD
behavior.

## Use

For a model like:

    Todo = can.Model.extend({
      findAll: "/todos",
      findOne: "/todos/{id}",
      create: "/todos",
      update: "/todos/{id}",
      destroy: "/todos/{id}"
    },{})

Create a fixture store and hook it up like:


    var todoStore = can.fixture.store(1000,function(id){
      return {name: "Todo "+id}
    })


    can.fixture({
      'GET /todos':         todoStore.findAll,
      'GET /todos/{id}':    todoStore.findOne,
      'POST /todos':        todoStore.create,
      'PUT /todos/{id}':    todoStore.update,
      'DELETE /todos/{id}': todoStore.destroy
    });