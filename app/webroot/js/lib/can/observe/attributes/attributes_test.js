steal('can/util', 'can/observe/attributes', function(can) {

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



})();