@function can.Map.prototype.each each
@parent can.Map.prototype 5

@description Call a function on each property of a Map.

@signature `map.each( callback(item, propName ) )`

`each` iterates through the Map, calling a function
for each property value and key.

@param {function(*,String)} callback(item,propName) the function to call for each property
The value and key of each property will be passed as the first and second
arguments, respectively, to the callback. If the callback returns false,
the loop will stop.

@return {can.Map} this Map, for chaining

@body

    var names = [];
    new can.Map({a: 'Alice', b: 'Bob', e: 'Eve'}).each(function(value, key) {
        names.push(value);
    });

    names; // ['Alice', 'Bob', 'Eve']

    names = [];
    new can.Map({a: 'Alice', b: 'Bob', e: 'Eve'}).each(function(value, key) {
        names.push(value);
        if(key === 'b') {
            return false;
        }
    });

    names; // ['Alice', 'Bob']
    