@property can.Map.attributes.static.attributes attributes
@parent can.Map.attributes.static 0

`can.Map.attributes` is a property that contains key/value pair(s) of an attribute's name and its
respective type for using in [can.Map.attributes.static.convert convert] and [can.Map.prototype.serialize serialize].

```
var Contact = can.Map.extend({
    attributes : {
        birthday : 'date',
        age: 'number',
        name: 'string'
    }
});
```
