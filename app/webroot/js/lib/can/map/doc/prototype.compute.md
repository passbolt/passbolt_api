@function can.Map.prototype.compute compute
@parent can.Map.prototype 4

@description Make a can.compute from an observable property.

@signature `map.compute(attrName)`
@param {String} attrName the property to bind to
@return {can.compute} a [can.compute] bound to _attrName_

@body

`compute` is a convenience method for making computes from properties
of Observes. More information about computes can be found under [can.compute].


    var map = new can.Map({a: 'Alexis'});
    var name = map.compute('a');
    name.bind('change', function(ev, nevVal, oldVal) {
        console.log('a changed from ' + oldVal + 'to' + newName + '.');
    });

    name(); // 'Alexis'

    map.attr('a', 'Adam'); // 'a changed from Alexis to Adam.'
    name(); // 'Adam'

    name('Alice'); // 'a changed from Adam to Alice.'
    name(); // 'Alice'
