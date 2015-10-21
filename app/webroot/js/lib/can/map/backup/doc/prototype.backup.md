@function can.Map.backup.prototype.backup backup
@plugin can/map/backup
@parent can.Map.backup 0

@description Save the values of the properties of an Map.

@signature `map.backup()`

`backup` backs up the current state of the properties of an Observe and marks
the Observe as clean. If any of the properties change value, the original
values can be restored with [can.Map.backup.prototype.restore restore].

@return {can.Map} The map, for chaining.

@body

## Example

```
var recipe = new can.Map({
title: 'Pancake Mix',
yields: '3 batches',
ingredients: [{
 ingredient: 'flour',
 quantity: '6 cups'
},{
 ingredient: 'baking soda',
 quantity: '1 1/2 teaspoons'
},{
 ingredient: 'baking powder',
 quantity: '3 teaspoons'
},{
 ingredient: 'salt',
 quantity: '1 tablespoon'
},{
 ingredient: 'sugar',
 quantity: '2 tablespoons'
}]
});
recipe.backup();

recipe.attr('title', 'Flapjack Mix');
recipe.title;     // 'Flapjack Mix'

recipe.restore();
recipe.title;     // 'Pancake Mix'
```