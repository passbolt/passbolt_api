@function can.Map.prototype.define.TypeConstructor Type
@parent can.Map.prototype.define

Provides a constructor function to be used to convert any value passed into [can.Map::attr attr] into an appropriate value


@signature `constructorFunc`

A constructor function can be provided that is called to convert incoming values set on this property, like:

    define: {
      prop: {
        Type: Person
      }
    }

@body

Similar to [can.Map.prototype.define.type type], this uppercase version provides a mechanism for converting incoming values to another format or type.

Specifically, this constructor will be invoked any time this property is set, and any data passed into the setter will be passed as arguments for the constructor.

If the call to attr passes an object that is already an instance of the constructor specified with `Type`, no conversion is done.
