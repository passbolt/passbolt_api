@page can.List.plugins.sort sort
@parent can.List.plugins
@plugin can/list/sort
@test can/list/sort/test.html

Sort a [can.List] and keep it that way.

@body

## Use

The [can.List.plugins.sort] plugin makes it easy to define
and maintain how items are arranged in a [can.List]. To use it,
set a `comparator` [can.Map::attr attr] on a [can.List]. It can be a
`String` or a `Function`.

```
var cart = new can.List([
	{ title: 'Bread', price: 4.00 },
	{ title: 'Butter', price: 3.50 },
	{ title: 'Juice', price: 3.05 }
]);
cart.attr("comparator",'price');
cart; // -> [Juice, Butter, Bread]
```

Setting a comparator will sort the list immediately. But what's really nice
is that if your list is being listened to, it will automatically sort when
any of its items are changed:

```
var cart = new can.List([
	{ title: 'Juice', price: 3.05 }
	{ title: 'Butter', price: 3.50 },
	{ title: 'Bread', price: 4.00 }
]);
cart.attr("comparator",'price');
cart.bind("length", function(){});
cart.attr("0.price",5);
cart; // -> [Butter, Bread, Juice]
```

And it will keep sort order when items are pushed, unshifted, or spliced into the can.List:

```
var cart = new can.List([
	{ title: 'Juice', price: 3.05 }
	{ title: 'Butter', price: 3.50 },
	{ title: 'Bread', price: 4.00 }
]);
cart.bind("length", function(){});
cart.push({title: 'Apple', 3.25});
cart; // -> [Juice, Apple, Butter, Bread]
```

If you are using a `can.List` in a template, it will be bound to 
automatically.  Check out this demo that lets you change the sort order
and a person's name:

@demo can/list/sort/simple_sort.html

## Function Comparators

When a `String` is defined the default comparator function
arranges the items in ascending order. To customize the sort behavior,
define your own comparator function.

```
var stockPrices = new can.List([
	0.01, 0.98, 0.75, 0.12, 0.05, 0.16
]);
stockPrices.attr("comparator", function (a, b) {
	return a === b ? 0 : a < b ? 1 : -1; // Descending
})
stockPrices // -> [0.98, 0.75, 0.16, 0.12, 0.05, 0.01];

```

## String Comparators

String comparators will be passed to [can.List.attr] to
retrieve the values being compared.

```
var table = new can.List([
	[6, 3, 4],
	[1, 8, 2],
	[7, 9, 5]
]);
table.attr('comparator', '2')
table  // -> [1, 8, 2],
             [6, 3, 4],
             [7, 9, 5]
```


## Move events

Whenever there are changes to items in the [can.List], the
[can.List.plugins.sort] plugin moves the item to the correct
index and fires a "move" event.

```
var cart = new can.List([
	{ title: 'Bread', price: 3.00 },
	{ title: 'Butter', price: 3.50 },
	{ title: 'Juice', price: 3.25 }
]);
cart.attr('comparator','price');

cart.bind('move', function (ev, item, newIndex, oldIndex) {
	console.log('Moved:', item.title + ', from:', oldIndex + ', to:', newIndex);
})

cart.attr('0.price', 4.00); // Moved: Bread, from: 0, to: 3
							// -> [Juice, Butter, Bread]
```
