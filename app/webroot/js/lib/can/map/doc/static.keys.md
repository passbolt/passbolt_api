@function can.Map.keys keys
@parent can.Map.static 0

@description Returns an array of the map's keys.

@signature `can.Map.keys(map)`
@param {can.Map} map the `can.Map` to get the keys from
@return {Array} array An array containing the keys from _map_.

@body
`keys` iterates over a map to get an array of its keys.


    var people = new can.Map({
        a: 'Alice',
        b: 'Bob',
        e: 'Eve'
    });

    can.Map.keys(people); // ['a', 'b', 'e']