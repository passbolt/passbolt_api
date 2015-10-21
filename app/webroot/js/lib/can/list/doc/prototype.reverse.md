@page can.List.prototype.reverse reverse
@parent can.List.prototype

@function can.List.prototype.reverse reverse
@description Reverse the order of a List.
@signature `list.reverse()`

`reverse` reverses the elements of the List in place.

@return {can.List} the List, for chaining

@body
```
var list = new can.List(['Alice', 'Bob', 'Eve']);
var reversedList = list.reverse();

reversedList.attr(); // ['Eve', 'Bob', 'Alice'];
list === reversedList; // true
```

`reverse` calls `replace` internally and triggers corresponding `add`, `remove`, `change` and `length` events respectively.

## Demo

@iframe can/list/doc/reverse.html 350