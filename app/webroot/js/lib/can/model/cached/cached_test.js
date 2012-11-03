steal('funcunit/qunit','can/model/cached','can/util/fixture',function(){
	module("can/model/cached")
	
	test("findAll",function(){
		stop();
	
		var numRequests = 0,
			origDelay = can.fixture.delay;
		can.fixture.delay = 500;
		
		can.fixture("/tasks", function(data){
			if(numRequests++ === 0){
				return [
					{id: 1, name: "first"},
					{id: 2, name: "second"}
				]
			} else {
				return [
					{id: 1, name: "First"},
					{id: 2, name: "second"},
					{id: 3, name: "third"}
				]
			};
		})
		
		Task = can.Model.Cached({
			findAll: "/tasks"
		},{})
		Task.cacheClear();
		
		Task.findAll({}, function(tasks){
						
			setTimeout(function(){
				Task.findAll({}, function(secondTasks){
					same(tasks.attr(), secondTasks.attr())
					
					secondTasks.bind("change", function(ev, attr, how, newVal,oldVal){
						start();
					})
					
					
				})
			},13)
		})
		
	})
	test("findOne",function(){
		stop();
	
		var numRequests = 0,
			origDelay = can.fixture.delay;
		can.fixture.delay = 500;
		
		can.fixture("/tasks/1", function(data){
			if(numRequests++ === 0){
				return {id: 1, name: "first"}
			} else {
				return {id: 1, name: "First"}
			};
		})
		
		Task = can.Model.Cached({
			findOne: "/tasks/{id}"
		},{})
		Task.cacheClear();
		
		Task.findOne({id: 1}, function(task){
						
			setTimeout(function(){
				Task.findOne({id: 1}, function(secondTask){
					same(task.attr(), secondTask.attr())
					
					secondTask.bind("change", function(ev, attr, how, newVal,oldVal){
						start();
					})
					
					
				})
			},13)
		})
		
	});
	
	test("findAll and findOne", function(){
		stop();
	
		var origDelay = can.fixture.delay;
		can.fixture.delay = 500;
		
		can.fixture("/tasks", function(data){
			return [
				{id: 1, name: "first"},
				{id: 2, name: "second"}
			];
		});
		can.fixture("/tasks/1", function(){
			return {id: 1, name: "First"}
		});
		
		Task = can.Model.Cached({
			findAll: "/tasks",
			findOne: "/tasks/{id}"
		},{})
		Task.cacheClear();
		
		Task.findAll({}, function(tasks){
						
			setTimeout(function(){
				Task.findOne({id: 1}, function(task){
					same(tasks[0].attr(),task.attr())
					
					task.bind("change", function(ev, attr, how, newVal,oldVal){
						start();
					})
					
					
				})
			},13)
		})
	})
	
	test("destroy", function(){
		stop();
		var TASKS = [
				{id: 1, name: "first"},
				{id: 2, name: "second"}
			];
		can.fixture("GET /tasks", function(data){
			return TASKS;
		});
		can.fixture("DESTROY /tasks/{id}", function(options){
			//TASKS.splice( (+options.data.id)-1,1)
			return {};
		})
		
		Task = can.Model.Cached({
			findAll: "/tasks",
			destroy: "/tasks/{id}"
		},{})
		Task.cacheClear();
		
		Task.findAll({}, function(tasks){
			tasks[0].destroy(function(){
				Task.findAll({},function(tasks2){
					equals(tasks2.length, 1);
					tasks2.bind("change", function(){
						ok(true, "updated list")
						start();
					})
					
					
				})
			})
		})
		
		
	})
})
