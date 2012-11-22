steal('can/util', 'can/model', 'can/model/list', 'can/observe/attributes', function(can) {

module("can/observe/attributes");

test("literal converters and serializes", function(){
	can.Observe("Task1",{
		attributes: {
			createdAt: "date"
		},
		convert: {
			date: function(d){
				var months = ["jan", "feb", "mar"]
				return months[d.getMonth()]
			}
		},
		serialize: {
			date: function(d){
				var months = {"jan":0, "feb":1, "mar":2}
				return months[d]
			}
		}
	},{});
	can.Observe("Task2",{
		attributes: {
			createdAt: "date"
		},
		convert: {
			date: function(d){
				var months = ["apr", "may", "jun"]
				return months[d.getMonth()]
			}
		},
		serialize: {
			date: function(d){
				var months = {"apr":0, "may":1, "jun":2}
				return months[d]
			}
		}
	},{});
	var d = new Date();
	d.setMonth(1, 1)
	var task1=new Task1({
		createdAt: d,
		name:"Task1"
	});
	d.setMonth(2, 1)
	var task2=new Task2({
		createdAt: d,
		name:"Task2"
	});
	equals(task1.createdAt, "feb", "Task1 model convert");
	equals(task2.createdAt, "jun", "Task2 model convert");
	equals(task1.serialize().createdAt, 1, "Task1 model serialize");
	equals(task2.serialize().createdAt, 2, "Task2 model serialize");
	equals(task1.serialize().name, "Task1", "Task1 model default serialized");
	equals(task2.serialize().name, "Task2", "Task2 model default serialized");
});

var makeClasses= function(){
	can.Observe("AttrTest.Person", {
		serialize: function() {
			return "My name is " + this.name;
		}
	});
	can.Observe("AttrTest.Loan");
	can.Observe("AttrTest.Issue");
	
	AttrTest.Person.model = function(data){
		return new this(data);
	}
	AttrTest.Loan.models = function(data){
		return can.map(data, function(l){
			return new AttrTest.Loan(l)
		});
	}
	AttrTest.Issue.models = function(data){
		return can.map(data, function(l){
			return new AttrTest.Issue(l)
		});
	}
	can.Observe("AttrTest.Customer",
	{
		attributes : {
			person : "AttrTest.Person.model",
			loans : "AttrTest.Loan.models",
			issues : "AttrTest.Issue.models"
		}			
	},
	{});
}


test("default converters", function(){
	var num = 1318541064012;
	equals( can.Observe.convert.date(num).getTime(), num, "converted to a date with a number" );
})

test("basic observe associations", function(){
	makeClasses();
	
	var c = new AttrTest.Customer({
		person : {
			id: 1,
			name: "Justin"
		},
		issues : [],
		loans : [
			{
				amount : 1000,
				id: 2
			},
			{
				amount : 19999,
				id: 3
			}
		]
	});
	
	equals(c.person.name, "Justin", "association present");
	
	equals(c.person.constructor, AttrTest.Person, "belongs to association typed");
	
	equals(c.issues.length, 0);
	
	equals(c.loans.length, 2);
	
	equals(c.loans[0].constructor, AttrTest.Loan);
	
	
});

test("single seralize w/ attr name", function(){
	var Breakfast = can.Observe({
		attributes: {
			time: "date"
		},
		serialize: {
			date: function(d){
				return d.getTime();
			}
		}
	},{});
	
	var time = new Date();
	var b = new Breakfast({
		time: time
	});
	
	equals(b.serialize('time'), time.getTime());
});

test("defaults", function(){
	var Zelda = can.Observe({
		defaults: {
			sword: 'Wooden Sword',
			shield: false,
			hearts: 3,
			rupees: 0
		}
	},{});
	
	var link = new Zelda({
		rupees: 255
	});
	
	equals(link.attr('sword'), 'Wooden Sword');
	equals(link.attr('rupees'), 255);
});

test("nested model attr", function(){
  can.Model('NestedAttrTest.User', {}, {});

  can.Model('NestedAttrTest.Task', {
      attributes : {
        owner : "NestedAttrTest.User.model"
      }
    }, {});


  can.Model('NestedAttrTest.Project',{
      attributes : {
        creator : "NestedAttrTest.User.model"
      }
    }, {});

    var michael = NestedAttrTest.User.model({ id : 17, name : 'Michael'});
    var amy = NestedAttrTest.User.model({ id : 29, name : 'Amy'});

    // add bindings so the objects get stored in the User.store
    michael.bind('foo', function(){});
    amy.bind('foo', function(){});

    var task = NestedAttrTest.Task.model({
      id : 1,
      name : "Do it!",
      owner : {id : 17}
    });

    var project = NestedAttrTest.Project.model({
      id : 1,
      name : "Get Things Done",
      creator : {id : 17}
    });

    task.bind('foo', function(){});
    project.bind('foo', function(){});

    equal(task.attr('owner.name'), 'Michael', 'owner hash correctly modeled');
    equal(project.attr('creator.name'), 'Michael', 'creator hash correctly modeled');

    task.attr({ owner : { id : 29, name : 'Amy'}});
    equal(task.attr('owner.name'), 'Amy', 'Task correctly updated to Amy user model');
    equal(task.attr('owner.id'), 29, 'Task correctly updated to Amy user model');

    equal(project.attr('creator.name'), 'Michael', 'Project creator should still be Michael');
    equal(project.attr('creator.id'), 17, 'Project creator should still be Michael');
    equal(NestedAttrTest.User.store[17].id, 17, "The model store should still have Michael associated by his id");
});

test("attr() should respect convert functions for lists when updating", function(){
  can.Model('ListTest.User', {}, {});
  can.Model.List('ListTest.User.List', {}, {});

  can.Model('ListTest.Task', {
    attributes : {
      project : "ListTest.Project.model"
    }
  }, {});

  can.Model('ListTest.Project',{
      attributes : {
        members : "ListTest.User.models"
      }
    }, {});

    var task = ListTest.Task.model({
      id : 1,
      name : "Do it!",
      project : {
        id : 789,
        name : "Get stuff done",
        members : []
      }
    });

    equal(task.project.members instanceof ListTest.User.List, true, "the list is a User List");

    task.attr({
      id : 1,
      project : {
        id : 789,
        members: [{ id : 72, name : "Andy"}, { id : 74, name : "Michael"}]
      }
    });

    equal(task.project.members instanceof ListTest.User.List, true, "the list is a User List");
    equal(task.project.members.length, 2, "The members were added");
    equal(task.project.members[0] instanceof ListTest.User, true, "The members was converted to a model object");
    equal(task.project.members[1] instanceof ListTest.User, true, "The user was converted to a model object");
});

test("plugin passes old value to converter", 2, function() {
	var Ob = can.Observe('AttrOldVal', {
		oldVal : function(val, oldVal) {
			if(val === 'first') {
				ok(!oldVal, 'First time does not have an old value');
			}
			if(val === 'second') {
				equal(oldVal, 'first', 'Old value is correct');
			}
			return val;
		},
		attributes : {
			test : 'AttrOldVal.oldVal'
		}
	}, {});

	var o = new Ob({
		test : 'first'
	});

	o.attr('test', 'second');
});

test("attr does not blow away old observable when going from empty to having items (#160)", function(){
	can.Model('EmptyListTest.User', {}, {});
	can.Model.List('EmptyListTest.User.List', {}, {});

	can.Model('EmptyListTest.Project',{
		attributes : {
			members : "EmptyListTest.User.models"
		}
	}, {});

	var project = EmptyListTest.Project.model({
		id : 789,
		members : []
	});

	var oldCid = project.attr("members")._cid;
	project.attr({members:['bob']});
	same(project.attr("members")._cid, oldCid, "should be the same observe, so that views bound to the old one get updates")
	equals(project.attr("members").length, 1, "list should have bob in it");
});

})();