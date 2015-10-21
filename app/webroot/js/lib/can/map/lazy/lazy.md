@constructor can.LazyMap
@inherits can.Map
@parent can.Map.plugins
@plugin can/map/lazy
@release 2.1
@test can/LazyMap/lazy/test.html

@description Create observable objects that initialize on demand.

@signature `new can.LazyMap([props])`

Creates a new instance of can.LazyMap.

@param {Object} [props] Properties and values to initialize the Map with.
@return {can.LazyMap} An instance of `can.LazyMap` with the properties from _props_.

@body

Just like `can.Map`, `can.LazyMap` provides a way to listen for and keep track of changes to objects. But unlike Map, a LazyMap only initializes data when bound, set or read. For lazy observable arrays, `can.LazyList` is also available.

This on demand initialization of nested data can yield big performance improvements when using large datasets that are deeply nested data where only a fraction of the properties are accessed or bound to.

## Limitations of LazyMaps

Although passing all original [can.Map] and [can.List] tests, `can.LazyMap` and `can.LazyList` do not work with the [can.Map.attributes], [can.Map.setter], [can.Map.delegate], [can.Map.backup]
and [can.Map.validations] plugins.

Additionally, If all properties of a LazyMap or LazyList are being read, bound or set, initialization time can be slightly higher than using a Map or List.

## Working with LazyMaps

`can.LazyMap` and `can.LazyList` are API compatible with [can.Map] and [can.List]. 

To create a LazyMap, use `new can.LazyMap([props])`. Properties should be read or set using `[can.Map.prototype.attr attr]`, never directly.

```
// chores is just a normal Array initially
var lazyPerson = new can.LazyMap({
  name: 'Bob',
  chores: ['dishes', 'garbage']
});

lazyPerson.attr('chores') // Now chores is a can.List
```

## See Also

For information on manipulating attributes, see [can.Map.prototype.attr attr]. To see what events are fired on property changes and how to listen for those events see [can.Map.prototype.bind bind].
