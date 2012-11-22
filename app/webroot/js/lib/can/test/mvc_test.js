(function(undefined) {

module("mvc");

test("Class basics", function(){
	
	var Note = can.Construct({
		init : function(name){
			this.name = name;
		},
		
		author : function(){ return this.name },
		
		coordinates : "xy",
		
		allowedToEdit: function(account) { 
			return true;
		}
	});
	
	var PrivateNote = Note({
		allowedToEdit: function(account) {
			return false;
		}
	})
	
	var n = new PrivateNote("Hello World");
	equals(n.author(),"Hello World")
});

test("Model basics",function(){
	/*$.fixture("/mvc/foo",function(){
		return [[{
			id: 1,
			name : "foo"
		}]]
	})*/

	var url = "can/test/fixtures/foo.json";
	if(typeof steal !== 'undefined') {
		url = steal.config().root.join(url) + '';
	}

	var Task = can.Model({
		findAll : url
	},{
		print : function(){
			return this.name;
		}
	});
	stop();
	Task.findAll({}, function(tasks){

		equals(tasks.length, 1,"we have an array")
		equals(tasks[0].id, 1, "we have the objects")
		ok(tasks[0] instanceof Task, "we have an instance of task")

		// add a task
		tasks.bind('add', function(ev, items, where){
			ok(items.length, "add called with an array");

			ok(newtask === items[0], "add called with new task")
			start();

		})
		var newtask = new Task({name: "hello"})
		tasks.push( newtask )
	});
})

test("Control Basics",3,function(){
	var Task = can.Model({
		findAll : "/mvc/foo"
	},{
		print : function(){
			return this.name;
		}
	});
	
	var Tasks = can.Control({
		"{Task} created" : function(Task, ev, task){
			ok(task, "created called")
		},
		"click" : function(el, ev){
			ok(el, "click called")
		}
	});
	
	
	var tasks = new Tasks( '#qunit-test-area' , {
		Task : Task
	})
	can.trigger(can.$('#qunit-test-area'),"click")
	
	new Task({id: 1}).created();
	
	equals(can.$('#qunit-test-area')[0].className, "")
	
	tasks.destroy();
	
	// make sure we can't click
	can.trigger(can.$('#qunit-test-area'),"click")
	
})

})();
