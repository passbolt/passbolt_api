@function can.view.Scope.read read
@parent can.view.Scope.static

@deprecated {2.1} You should use [can.compute.read] instead of this function.

@signature `Scope.read(parent, reads, options)`

@param {*} parent A parent object to read properties from.
@param {Array<String>} reads An array of properties to read.
@param {can.view.Scope.readOptions} options Configures
how to read properties and values and register callbacks

@return {{value: *, parent: *}} Returns an object that
provides the value and parent object.

@option {*} value The value found by reading `reads` properties.  If
no value was found, value will be undefined.

@option {*} parent The most immediate parent object of the value specified by `key`.

@body