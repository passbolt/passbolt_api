@function can.Map.prototype.serialize serialize
@parent can.Map.prototype 7

@description Serialize this object to something that can be passed to `JSON.stringify`.

@signature `map.serialize()`

Get the serialized Object form of the map.  Serialized
data is typically used to send back to a server.


    o.serialize() //-> { name: 'Justin' }


Serialize currently returns the same data
as [can.Map.prototype.attrs].  However, in future
versions, serialize will be able to return serialized
data similar to [can.Model].  The following will work:


    new Map({time: new Date()})
        .serialize() //-> { time: 1319666613663 }


@return {Object} a JavaScript Object that can be
serialized with `JSON.stringify` or other methods.