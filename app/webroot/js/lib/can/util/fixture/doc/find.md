@description Get an item from the store by ID.
@function can.fixture.types.Store.find
@parent can.fixture.types.Store
@signature `store.find(settings)`
@param {Object} settings An object containing an `id` key corresponding to the item to find.

@body
`store.find(settings)`
`store.destroy(request, callback)` simulates a request to get a single item from the server.


    todosStore.find({
        url: "/todos/5"
    }, function(){ });

