@function can.classize can.classize
@parent can.Map.setter 0
@plugin can/map/setter

@description Make a string into a class name.

@signature `can.classize(str)`

`can.classize` splits a string by underscores or
dashes and capitalizes each part before joining
them back together. This method is useful for
taking HTML class names and getting the original
Control name from them.

```
can.classize('my_control_name'); // 'MyControlName'
```
@param {String} str The string to transform.
@return {String} The string as a class name.