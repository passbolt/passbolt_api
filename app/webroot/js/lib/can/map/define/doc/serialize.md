@function can.Map.prototype.define.serialize serialize
@parent can.Map.prototype.define

Called when an attribute is removed.

@signature `serializer( currentValue )`

@param {*} value The current value of the attribute. 

@param {String} attr The name of the attribute being serialized.

@return {*|undefined} If `undefined` is returned, the value is not serialized.

@this {can.Map} The map instance being serialized.

@body 

## Use

[can.Map::serialize serialize] is useful for serializing a can.Map instance into 
a more JSON-friendly form.  This can be used for many reasons, including saving a 
[can.Model] instance on the server or serializing [can.route.map]'s internal 
can.Map for display in the hash or pushstate URL.

The serialize property allows an opportunity to define how 
each attribute will behave when the map is serialized.  This can be useful for:

- serializing complex types like dates, arrays, or objects into string formats
- causing certain properties to be ignored when serialize is called

The following causes a locationIds property to be serialized into 
the comma separated ID values of the location property on this map:

    define: {
      locationIds: {
        serialize: function(){
		  var ids = [];
		  this.attr('locations').each(function(location){
		    ids.push(location.id);
		  });
		  return ids.join(',');
        }
      }
    }

Returning `undefined` for any property means this property will not be part of the serialized 
object.  For example, if the property numPages is not greater than zero, the following example 
won't include it in the serialized object.

    define: {
      prop: {
        numPages: function( num ){
          if(num <= 0) {
          	return undefined;
          }
          return num;
        }
      }
    }
