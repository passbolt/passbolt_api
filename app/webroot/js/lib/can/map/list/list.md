@page can.Map.List
@parent can.Map.plugins
@plugin can/map/list
@test can/map/list/test.html

@deprecated {2.1} This plugin is pending review and its API has not been finalized. Including this plugin will overwrite [can.List.prototype.filter] (which is faster but doesn't provide live-updating). The new `filter` and `map` methods also have a slightly different API, including the _element_, _index_, and _list_ as arguments (instead of only _element_ and _list_).

@description

The `can.Map.List` plugin adds support for live-updating mapped and filtered observable lists.



@signature `list.filter(callback)`

Generates a new filtered list that live-updates itself to contain the filtered items of the original list.

```
var list = new can.List([
	{ name: 'Justin' },
	{ name: 'Brian' },
	{ name: 'Austin' },
	{ name: 'Mihael' }])
	
// Returns a filtered list that only matches names containing an "n"
var filtered = list.filter(function(element, index, list) {
	return item.attr("name").match(/n/i);
});
```

@param {function(element,index,list)} callback A filtering function that will determine whether an element is filtered or not.
@return {can.List} A live-updating filtered list.


@signature `list.map(callback)`

Generates a new mapped list that live-updates itself to contain the mapped items of the original list.

```
var list = new can.List([
	{ name: 'Justin' },
	{ name: 'Brian' },
	{ name: 'Austin' },
	{ name: 'Mihael' }])
	
// Returns a mapped list that returns the element names.
var filtered = list.filter(function(element, index, list) {
	return item.attr("name");
});
```


@param {function(element,index,list)} callback A mapping function that will determine each element's mapped value.
@return {can.List} A live-updating mapped list.
