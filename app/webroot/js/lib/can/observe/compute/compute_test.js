(function() {
module('can/observe/compute')

test("Basic Compute",function(){
	
	var o = new can.Observe({first: "Justin", last: "Meyer"});
	var prop = can.compute(function(){
		return o.attr("first") + " " +o.attr("last")
	})
	
	equals(prop(), "Justin Meyer");
	var handler =  function(ev, newVal, oldVal){
		equals(newVal, "Brian Meyer")
		equals(oldVal, "Justin Meyer")	
	}
	prop.bind("change", handler);
	
	o.attr("first","Brian");
	
	prop.unbind("change", handler)
	o.attr("first","Brian");
});


test("compute on prototype", function(){
	
	var Person = can.Observe({
		fullName: function(){
			return this.attr("first") + " " +this.attr("last")
		}
	})
	
	var me = new Person({
		first : "Justin",
		last : "Meyer"
	});
	var fullName = can.compute( me.fullName, me );
	
	equals(fullName(), "Justin Meyer");
	
	var called = 0;
	
	fullName.bind("change", function( ev, newVal, oldVal ) {
		called++;
		equal(called, 1, "called only once");
		equal(newVal, "Justin Shah");
		equal(oldVal, "Justin Meyer")
	});
	
	me.attr('last',"Shah")
	
	// to make this work, we'd have to look for a computed function and bind to it's change ...
	// maybe bind can just work this way?
})


test("setter compute", function(){
	var project = new can.Observe({
		progress: 0.5
	});
	
	// a setter compute that converts 50 to .5 and vice versa
	var computed = can.compute(function(val){
		if(val) {
			project.attr('progress', val / 100)
		} else {
			return parseInt( project.attr('progress') * 100 );
		}
	});
	
	equals(computed(), 50, "the value is right");
	computed(25);
	equals(project.attr('progress'), 0.25);
	equals(computed(),25 );
	
	computed.bind("change", function(ev, newVal, oldVal){
		equals(newVal, 75);
		equals(oldVal, 25)
	})
	
	computed(75);
	
})

test("compute a compute", function() {
	var project = new can.Observe({
		progress: 0.5
	});

	var percent = can.compute(function(val){
		if(val) {
			project.attr('progress', val / 100);
		} else {
			return parseInt( project.attr('progress') * 100, 10);
		}
	});

	equals(percent(),50,'percent starts right');
	percent.bind('change',function() {
		// noop
	});

	var fraction = can.compute(function(val) {
		if(val) {
			percent(parseInt(val.split('/')[0],10));
		} else {
			return percent() + '/100';
		}
	});

	fraction.bind('change',function() {
		// noop
	});

	equals(fraction(),'50/100','fraction starts right');

	percent(25);

	equals(percent(),25);
	equals(project.attr('progress'),0.25,'progress updated');
	equals(fraction(),'25/100','fraction updated');

	fraction('15/100');

	equals(fraction(),'15/100');
	equals(project.attr('progress'),0.15,'progress updated');
	equals(percent(),15,'% updated');
});

test("compute with a simple compute", function() {
	expect(4);
	var a = can.compute(5);
	var b = can.compute(function() {
		return a() * 2;
	});

	equal(b(),10,'b starts correct');
	a(3);
	equal(b(),6,'b updates');

	b.bind('change',function() {
		equal(b(),24,'b fires change');
	});
	a(12);
	equal(b(),24,'b updates when bound');
});


test("empty compute", function(){
	var c = can.compute();
	c.bind("change", function(ev, newVal, oldVal){
		ok(oldVal === undefined, "was undefined")
		ok(newVal === 0, "now zero")
	})
	
	c(0);
	
});

test("only one update on a batchTransaction",function(){
	var person = new can.Observe({first: "Justin", last: "Meyer"});
	var func = function(){
		return person.attr('first')+" "+person.attr('last')+Math.random()
	};
	var callbacks = 0;
	can.compute.binder(func, window, function(newVal, oldVal){
		callbacks++;
	});
	
	person.attr({
		first: "Brian",
		last: "Moschel"
	});
	
	equal(callbacks,1,"only one callback")
})

test("only one update on a start and end transaction",function(){
	var person = new can.Observe({first: "Justin", last: "Meyer"}),
		age = can.compute(5);
	var func = function(newVal,oldVal){
		return person.attr('first')+" "+person.attr('last')+age()+Math.random();
	};
	var callbacks = 0;
	can.compute.binder(func, window, function(newVal, oldVal){
		callbacks++;
	});
	
	can.Observe.startBatch();
	
	person.attr('first',"Brian");
	stop();
	setTimeout(function(){
		person.attr('last',"Moschel");
		age(12)
		
		can.Observe.stopBatch();
		
		equal(callbacks,1,"only one callback")
		
		start();
	})

	
	
})

test("Compute emits change events when an embbedded observe has properties added or removed", 3, function() {
	var obs = new can.Observe(),
		compute1 = can.compute(function(){
			var txt = obs.attr('foo');
			obs.each(function(val){
				txt += val.toString();
			});
			return txt;
		});

	compute1.bind('change', function(ev, newVal, oldVal) {
		ok(true, 'change handler fired: ' + newVal);
	})

	obs.attr('foo', 1);
	obs.attr('bar', 2);
	obs.attr('foo', 3);
	obs.removeAttr('bar');
	obs.removeAttr('bar');
});
})();