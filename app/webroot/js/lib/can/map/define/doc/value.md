@function can.Map.prototype.define.value value
@parent can.Map.prototype.define

Returns the default value for instances of this can.Map.  This is called before `init`.

@signature `defaulter()`

A function can be provided that returns the default value used for this property, like:

    define: {
      prop: {
        value: function(){ return []; }
      }
    }

If the default value should be an object of some type, it should be specified as the return value of a function (the above call signature) so that all instances of this map don't point to the same object.  For example, if the property `value` above had not returned an empty array but instead just specified an array using the next call signature below, all instances of that map would point to the same array (because JavaScript passes objects by reference).

@this {can.Map} the instance of the can.Map.

@return {*} The default value.  This will be passed through setter and type.

@signature `defaulVal`

Any value can be provided as the default value used for this property, like:

    define: {
      prop: {
        value: 'foo'
      }
    }

@param {*} defaultVal The default value, which will be passed through setter and type.

@body

There is a third way to provide a default value, which is explained in the [can.Map.prototype.define.ValueConstructor Value] docs page. `value` lowercased is for providing default values for a property type, while `Value` uppercased is for providing a constructor function, which will be invoked with `new` to create a default value for each instance of this map.