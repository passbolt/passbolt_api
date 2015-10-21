@page can.List.prototype.each each
@parent can.List.prototype

@function can.List.prototype.each each
@description Call a function on each element of a List.
@signature `list.each( callback(item, index) )`

`each` iterates through the Map, calling a function
for each element.

@param {function(*, Number)} callback the function to call for each element
The value and index of each element will be passed as the first and second
arguments, respectively, to the callback. If the callback returns false,
the loop will stop.

@return {can.List} this List, for chaining

@body
```
var i = 0;
new can.Map([1, 10, 100]).each(function(element, index) {
    i += element;
});

i; // 111

i = 0;
new can.Map([1, 10, 100]).each(function(element, index) {
    i += element;
    if(index >= 1) {
        return false;
    }
});

i; // 11
```