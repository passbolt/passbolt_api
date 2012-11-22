steal("can/model/list", 'funcunit/qunit', 'can/util/fixture', function(){
	 
module("jquery/model/list", {
	setup: function() {
		can.Model.extend("Person")
	
		can.Model.List("Person.List",{
			destroy : "DELETE /person/destroyAll",
			update : "PUT /person/updateAll"
		},{});
		var people = []
		for(var i =0; i < 20; i++){
			people.push( new Person({id: "a"+i}) )
		}
		this.people = new can.Model.List(people);
	}
})

test("hookup with list", function(){
	
	
	
	var div = $("<div>")
	
	for(var i =0; i < 20 ; i ++){
		var child = $("<div>");
		var p = new Person({foo: "bar"+i, id: i});
		p.hookup( child[0] );
		div.append(child)
	}
	var models = div.children().models();
	ok(models.constructor === Person.List, "correct type");
	equals(models.length, 20,  "Got 20 people")


})

test("create", function(){
	
	equals(this.people.length, 20)
	
	equals(this.people.get("a2")[0].id,"a2" , "get works")
})


test("splice", function(){
	ok(this.people.get("a1").length,"something where a1 is")
	this.people.splice(1,1)
	equals(this.people.length, 19)
	ok(!this.people.get("a1").length,"nothing where a1 is")
	
})

test("remove", function(){
	var res = this.people.remove("a1")
	ok(!this.people.get("a1").length,"nothing where a1 is")
	ok(res.length, "got something array like")
	equals(res[0].id, "a1")
})

test("remove with a shadowed id", function(){
	var MyModel = can.Model.extend({
		id: 'foo'
	},{
		foo: function() {
			return 'bar';
		}
	});

	var list = new MyModel.List([
		new MyModel({ foo: 'bar' }),
		new MyModel({ foo: 'baz' })
	]);
	list.remove('bar');
	equals(list.length,1,'bar was removed');
	equals(list[0].attr('foo'),'baz');
});

test("list from models", function(){
	var people = Person.models([{id: 1}, {id: 2}]);
	ok(people.elements, "we can find elements from a list")
});

test("destroy a list", function(){
	var people = Person.models([{id: 1}, {id: 2}]);
	stop();
	// make sure a request is made
	can.fixture('DELETE /person/destroyAll', function(){
		
		ok(true, "called right fixture");
		return true;
	})
	// make sure the people have a destroyed event
	people[0].bind('destroyed', function(){
		ok(true, "destroyed event called")
	})
	
	people.destroy(function(deleted){
		ok(true, "destroy callback called");
		equal(people.length, 0, "objects removed");
		equal(deleted.length, 2, "got back deleted items")
		start()
		// make sure the list is empty
		
	})

});

test("destroy a list with nothing in it", function(){
	var people = Person.models([]);
	stop();
	
	// make sure a request is made
	can.fixture('DELETE /person/destroyAll', function(){
		ok(true, "called right fixture");
		return true;
	});
	
	people.destroy(function(deleted){
		ok(true, "destroy callback called");
		equal(deleted.length, people.length, "got back deleted items")
		start();
	});
});

test("update a list", function(){
	var people = Person.models([{id: 1}, {id: 2}]),
		updateWith = {
			name: "Justin",
			age : 29
		},
		newProps = {
			newProp : "yes"
		};
	stop();
	
	// make sure a request is made
	can.fixture('PUT /person/updateAll', function(orig){
		ok(true, "called right fixture");
		ok(orig.data.ids.length, 2, "got 2 ids")
		same(orig.data.attrs, updateWith, "got the same attrs")
		return newProps;
	})
	
	// make sure the people have a destroyed event
	people[0].bind('updated', function(){
		ok(true, "updated event called")
	})
	
	people.update(updateWith,function(updated){
		ok(true, "updated callback called");
		ok(updated.length, 2, "got back deleted items");
		same(updated[0].attr(),$.extend({id : 1},newProps, updateWith ));
		start();
	});
})

test("update a list with nothing in it", function(){
	var people = Person.models([]),
		updateWith = {
			name: "Justin",
			age : 29
		};
	stop();
	
	// make sure a request is made
	can.fixture('PUT /person/updateAll', function(orig){
		ok(true, "called right fixture");
		return newProps;
	});

	people.update(updateWith,function(updated){
		ok(true, "updated callback called");
		equal(updated.length, people.length, "nothing updated");
		start();
	});
});

test("attr update a list when more things come back", function(){
  var people = Person.models([
    {
      id : 1,
      name : 'Michael',
      age : 20
    },
    {
      id : 2,
      name : 'Amy',
      age : 80
    }]);

  people.attr([
    {
      id : 3,
      name : 'Andy',
      age : 101
    },
    {
      id : 1,
      name : 'Michael',
      age : 120
    },
    {
      id : 2,
      name : 'Amy',
      age : 180
    }
  ]);
  equal(people.attr('0.id'), 1);
  equal(people.attr('0.age'), 120, "Michael's age incremented by 100 years");

  equal(people.attr('1.id'), 2);
  equal(people.attr('1.age'), 180, "Amy's age incremented by 100 years");

  equal(people.attr('2.id'), 3, "Added Andy to the end of the list");
  equal(people.attr('2.age'), 101);
});

test("attr update a list when less things come back and remove is true", function(){
  var people = Person.models([
    {
      id : 1,
      name : 'Michael',
      age : 20
    },
    {
      id : 2,
      name : 'Amy',
      age : 80
    },
    {
      id : 3,
      name : 'Andy',
      age : 1
    }
  ]);

  people.attr([
    {
      id : 3,
      name : 'Andy',
      age : 101
    },
    {
      id : 1,
      name : 'Michael',
      age : 120
    }], true);


  equal(people.length, 2, "Removed Amy");

  equal(people.attr('0.id'), 1);
  equal(people.attr('0.age'), 120, "Michael's age incremented by 100 years");

  equal(people.attr('1.id'), 3, "Andy is now the 2nd person in the list");
  equal(people.attr('1.age'), 101, "Andy's age incremented by 100 years");
});

test("attr update an empty list only fires one change event", function(){
  var people = Person.models([]);
  var changeCount = 0;
  people.bind('change', function(){
    changeCount++
  });

  people.attr([
    {
      id : 3,
      name : 'Andy',
      age : 101
    },
    {
      id : 1,
      name : 'Michael',
      age : 120
    }]
  );


  equal(changeCount, 1, "Only one change event is fired even though two items added");
});

test("attr update a list with shadowed ids", function(){
  var MyPerson = Person.extend({
    id: function() {
      return 'hi!';
    }
  });
  var people = MyPerson.models([
    {
      id : 1,
      name : 'Michael',
      age : 20
    },
    {
      id : 2,
      name : 'Amy',
      age : 80
    },
    {
      id : 3,
      name : 'Andy',
      age : 1
    }
  ]);

  people.attr([
    {
      id : 3,
      name : 'Andy',
      age : 101
    },
    {
      id : 1,
      name : 'Michael',
      age : 120
    }], true);


  equal(people.length, 2, "Removed Amy");

  equal(people.attr('0.id'), 1);
  equal(people.attr('0.age'), 120, "Michael's age incremented by 100 years");

  equal(people.attr('1.id'), 3, "Andy is now the 2nd person in the list");
  equal(people.attr('1.age'), 101, "Andy's age incremented by 100 years");
});

test("attr updates items based on id (when present), not position", function(){
    var people = Person.models([
      {
        id : 1,
        name : 'Michael',
        age : 20
      },
      {
        id : 2,
        name : 'Amy',
        age : 80
      },
      {
        id : 3,
        name : 'Andy',
        age : 1
      }
    ]);

    people.attr([
      {
        id : 3,
        name : 'Andy',
        age : 101
      },
      {
        id : 1,
        name : 'Michael',
        age : 120
      },
      {
        id : 2,
        name : 'Amy',
        age : 180
      }
    ]);

    equal(people.attr('0.id'), 1);
    equal(people.attr('0.age'), 120, "Michael's age incremented by 100 years");

    equal(people.attr('1.id'), 2);
    equal(people.attr('1.age'), 180, "Amy's age incremented by 100 years");

    equal(people.attr('2.id'), 3);
    equal(people.attr('2.age'), 101, "Andy's age incremented by 100 years");
  });

test("events - add", 4, function(){
	var list = new Person.List;
	list.bind("add", function(ev, items){
		ok(1, "add called");
		equals(items.length, 1, "we get an array")
	});
	
	var person = new Person({id: 1, name: "alex"});
	
	
	list.push(person);
	
	// check that we are listening to updates on person ...
	
	ok( $._data(person,"events"), "person has events" );
	
	list.pop()
	
	ok( !$._data(person, "events"), "person has no events" );
	
});

test("events - update", function(){
	var list = new Person.List;
	list.bind("update", function(ev, updated){
		ok(1, "update called");
		ok(person === updated, "we get the person back");
		
		equals(updated.name, "Alex", "got the right name")
	});
	
	var person = new Person({id: 1, name: "justin"});
	list.push(person);
	
	person.updated({name: "Alex"})
});

})
