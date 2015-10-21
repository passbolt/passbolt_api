@description Simulate a findOne request on a fixture.
@function can.fixture.types.Store.findOne
@parent can.fixture.types.Store
@signature `store.findOne(request, callback)`
@param {Object} request Parameters for the request.
@param {Function} callback A function to call with the retrieved item.

@body
`store.findOne(request, response(item))` simulates a request to
get a single item from the server by id.

    todosStore.findOne({
        url: "/todos/5"
    }, function(todo){
    
    });

