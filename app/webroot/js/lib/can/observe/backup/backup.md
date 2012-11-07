@page can.Observe.backup 
@parent can.Observe
@plugin can/observe/backup
@test can/observe/backup/qunit.html
@download http://donejs.com/can/dist/can.observe.backup.js

You can backup and restore [can.Observe] data with the can.Observe.backup plugin.
To backup an observe instance call [can.Observe.prototype.backup backup] like:

	var recipe = new can.Observe({
      name : 'Pancakes',
      ingredients : [{
          name : "eggs",
          amount : '1'
      }, {
          name : "flour",
          amount : '1 cup'
      }, {
          name : "milk",
          amount : '1 1/4 cup'
      }]
	});

	recipe.backup();

You can check if the instance has changed with [can.Observe.prototype.isDirty isDirty]:

	recipe.attr('name', 'Potcakes');
	recipe.isDirty() //-> true

Finally, you can restore the original attributes with [can.Observe.prototype.restore restore]:

	recipe.restore();
	recipe.name // -> "Pancakes"

By default only direct attributes are checked and restored. Pass true if you want _isDirty_ and
_restore_ to also include nested observe instances and [can.Model] associations.

See this in action:

@demo can/observe/backup/backup.html