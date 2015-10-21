@function can.Map.prototype.removeAttr removeAttr
@parent can.Map.prototype 6

@description Remove a property from a Map.

@signature `map.removeAttr(attrName)`
@param {String} attrName the name of the property to remove
@return {*} the value of the property that was removed

@body
`removeAttr` removes a property by name from a Map.


    var people = new can.Map({a: 'Alice', b: 'Bob', e: 'Eve'});

    people.removeAttr('b'); // 'Bob'
    people.attr();          // {a: 'Alice', e: 'Eve'}


Removing an attribute will cause a _change_ event to fire with `'remove'`
passed as the _how_ parameter and `undefined` passed as the _newVal_ to
handlers. It will also cause a _property name_ event to fire with `undefined`
passed as _newVal_. An in-depth description at these events can be found
under `[can.Map.prototype.attr attr]`.