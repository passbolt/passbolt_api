@function can.List.prototype.filter filter
@parent can.List.prototype

@description Filter the elements of a List, returning a new List instance with just filtered items.

@signature `list.filter(filterFunc, context)`

@param {function(this:*,*,Number,can.List):Boolean} filterFunc(item, index, list) A function to call with each element of the list. Returning `false` will remove the index.
@param {Object} context The object to use as `this` inside the callback.

@body

A filter function that accepts a function, which is run on every element of the list.  If the 
filter callback returns true, the list returned will contain this item, false and it will not.

Returns a new can.List instance.
	
	var list = new can.List([1, 2, 3])

	// returns new can.List([1, 2])
	var filtered = list.filter( function(item, index, list)
	{
		return item < 3;
	}); 