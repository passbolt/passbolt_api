@page can.List.prototype.splice splice
@parent can.List.prototype

@function can.List.prototype.splice splice
@description Insert and remove elements from a List.
@signature `list.splice(index[, howMany[, ...newElements]])`
@param {Number} index where to start removing or inserting elements

@param {Number} [howMany] the number of elements to remove
 If _howMany_ is not provided, `splice` will remove all elements from `index` to the end of the List.

@param {*} newElements elements to insert into the List

@return {Array} the elements removed by `splice`

@body
 `splice` lets you remove elements from and insert elements into a List.

 This example demonstrates how to do surgery on a list of numbers:

```
 var list = new can.List([0, 1, 2, 3]);

 // starting at index 2, remove one element and insert 'Alice' and 'Bob':
 list.splice(2, 1, 'Alice', 'Bob');
 list.attr(); // [0, 1, 'Alice', 'Bob', 3]
```

 ## Events

 `splice` causes the List it's called on to emit _change_ events,
 _add_ events, _remove_ events, and _length_ events. If there are
 any elements to remove, a _change_ event, a _remove_ event, and a
 _length_ event will be fired. If there are any elements to insert, a
 separate _change_ event, an _add_ event, and a separate _length_ event
 will be fired.

 This slightly-modified version of the above example should help
 make it clear how `splice` causes events to be emitted:

```
 var list = new can.List(['a', 'b', 'c', 'd']);
 list.bind('change', function(ev, attr, how, newVals, oldVals) {
     console.log('change: ' + attr + ', ' + how + ', ' + newVals + ', ' + oldVals);
 });
 list.bind('add', function(ev, newVals, where) {
     console.log('add: ' + newVals + ', ' + where);
 });
 list.bind('remove', function(ev, oldVals, where) {
     console.log('remove: ' + oldVals + ', ' + where);
 });
 list.bind('length', function(ev, length) {
     console.log('length: ' + length + ', ' + this.attr());
 });

 // starting at index 2, remove one element and insert 'Alice' and 'Bob':
 list.splice(2, 1, 'Alice', 'Bob'); // change: 2, 'remove', undefined, ['c']
                                    // remove: ['c'], 2
                                    // length: 5, ['a', 'b', 'Alice', 'Bob', 'd']
                                    // change: 2, 'add', ['Alice', 'Bob'], ['c']
                                    // add: ['Alice', 'Bob'], 2
                                    // length: 5, ['a', 'b', 'Alice', 'Bob', 'd']
```

 More information about binding to these events can be found under [can.List.attr attr].
