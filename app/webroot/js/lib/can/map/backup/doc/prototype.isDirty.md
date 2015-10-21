@function can.Map.backup.prototype.isDirty isDirty
@plugin can/map/backup
@parent can.Map.backup 1

@description Check whether an Observe has changed since the last time it was backed up.

@signature `map.isDirty([deep])`

`isDirty` checks whether any properties have changed value or whether any properties have
been added or removed since the last time the Observe was backed up. If _deep_ is `true`,
If the Observe has never been backed up, `isDirty` returns `undefined`.
`isDirty` will include nested Observes in its checks.

@param {bool} [deep=false] whether to check nested Observes
@return {bool} Whether the Observe has changed since the last time it was [can.Map.backup.prototype.backup backed up].

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

recipe.isDirty();     // false
recipe.backup();

recipe.attr('title', 'Flapjack Mix');
recipe.isDirty();     // true
recipe.restore();
recipe.isDirty();   // false

recipe.attr('ingredients.0.quantity', '7 cups');
recipe.isDirty();     // false
recipe.isDirty(true); // true

recipe.backup();
recipe.isDirty();     // false
recipe.isDirty(true); // false
```