steal('can/util', 'can/observe/sort', function(can) {

module("can/observe/sort");

test("list events", function(){
	
	var list = new can.Observe.List([
		{name: 'Justin'},
		{name: 'Brian'},
		{name: 'Austin'},
		{name: 'Mihael'}])
	list.comparator = 'name';
	list.sort();
	// events on a list
	// - move - item from one position to another
	//          due to changes in elements that change the sort order
	// - add (items added to a list)
	// - remove (items removed from a list)
	// - reset (all items removed from the list)
	// - change something happened
	
	// a move directly on this list
	list.bind('move', function(ev, item, newPos, oldPos){
		ok(true,"move called");
		equals(item.name, "Zed");
		equals(newPos, 3);
		equals(oldPos, 0);
	});
	
	// a remove directly on this list
	list.bind('remove', function(ev, items, oldPos){
		ok(true,"remove called");
		equals(items.length,1);
		equals(items[0].name, 'Alexis');
		equals(oldPos, 0, "put in right spot")
	})
	list.bind('add', function(ev, items, newPos){
		ok(true,"add called");
		equals(items.length,1);
		equals(items[0].name, 'Alexis');
		equals(newPos, 0, "put in right spot")
	});
	
	list.push({name: 'Alexis'});
	
	// now lets remove alexis ...
	list.splice(0,1);
	list[0].attr('name',"Zed")
})

})();