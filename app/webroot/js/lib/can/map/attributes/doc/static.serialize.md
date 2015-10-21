@property can.Map.attributes.static.serialize serialize
@parent can.Map.attributes.static 2

`can.Map.serialize` is an object of name-function pairs that are used to
serialize attributes.

Similar to [can.Map.attributes.static.convert can.Map.attributes.convert], in that the keys of this object correspond to
the types specified in [can.Map.attributes].

By default every attribute will be passed through the 'default' serialization method
that will return the value if the property holds a primitive value (string, number, ...),
or it will call the "serialize" method if the property holds an object with the "serialize" method set.

For example, to serialize all dates to ISO format:

```
var Contact = can.Map.extend({
attributes : {
 birthday : 'date'
},
serialize : {
 date : function(val, type){
   return new Date(val).toISOString();
 }
}
},{});

var contact = new Contact({
birthday: new Date("Oct 25, 1973")
}).serialize();
//-> { "birthday" : "1973-10-25T05:00:00.000Z" }
```