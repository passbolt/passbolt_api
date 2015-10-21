@description Simulate a findAll to a fixture.
@function can.fixture.types.Store.findAll
@parent can.fixture.types.Store
@signature `store.findAll(request)`

`store.findAll(request)` simulates a request to
get a list items from the server. It supports the
following params:

- order - `order=name ASC`
- group - `group=name`
- limit - `limit=20`
- offset - `offset=60`
- id filtering - `ownerId=5`


@param {{}} request The ajax request object. The available parameters are:
@option {String} order The order of the results.
`order: 'name ASC'`
@option {String} group How to group the results.
`group: 'name'`
@option {String} limit A limit on the number to retrieve.
`limit: 20`
@option {String} offset The offset of the results.
`offset: 60`
@option {String} id Filtering by ID.
`id: 5`

@return {Object} a response object like:

    {
        count: 1000,
        limit: 20,
        offset: 60,
        data: [item1, item2, ...]
    }

where:

- count - the number of items that match any filtering before limit and offset is taken into account
- offset - the offset passed
- limit - the limit passed
- data - an array of JS objects with each item's properties

