@description Simulate destroying a Model on a fixture.
@function can.fixture.types.Store.destroy
@parent can.fixture.types.Store
@signature `store.destroy(request, callback)`
@param {Object} request Parameters for the request.
@param {Function} callback A function to call after destruction.

@body
`store.destroy(request, response())` simulates a request to destroy an item from the server.


    todosStore.destroy({
        url: "/todos/5"
    }, function(){ 
    
    });

