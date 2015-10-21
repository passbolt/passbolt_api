@description Simulate an update on a fixture.
@function can.fixture.types.Store.update
@parent can.fixture.types.Store
@signature `store.update(request, callback)`
@param {Object} request Parameters for the request.
@param {Function} callback A function to call with the updated item and headers.

@body
`store.update(request, response(props,headers))` simulates a request to update an items properties on a server.

    todosStore.update({
        url: "/todos/5"
    }, function(props, headers){
        props.id //-> 5
        headers.location // "todos/5"
    });
