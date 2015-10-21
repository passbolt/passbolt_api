@description Simulate creating a Model with a fixture.
@function can.fixture.types.Store.create
@parent can.fixture.types.Store
@signature `store.create(request, callback)`
@param {Object} request Parameters for the request.
@param {Function} callback A function to call with the created item.

@body
`store.destroy(request, callback)` simulates a request to destroy an item from the server.


    todosStore.create({
        url: "/todos"
    }, function(){ });

