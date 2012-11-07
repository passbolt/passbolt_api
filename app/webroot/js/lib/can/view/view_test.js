steal('can/view', function(can) {
	module("can/view");

	/*test("Ajax transport", function(){
		var order = 0;
		$.ajax({
			url: "//can/view/test/qunit/template.ejs",
			dataType : "view",
			async : false
		}).done(function(view){
			equals(++order,1, "called synchronously");
			equals(view({message: "hi"}).indexOf("<h3>hi</h3>"), 0, "renders stuff!")
		});

		equals(++order,2, "called synchronously");
	})*/

	if(window.Jaml){
		test("multiple template types work", function(){
			can.each(["micro","ejs","jaml"/*, "tmpl"*/], function( ext){
				var div = can.$(document.createElement('div'));

				can.append(div, can.view("//can/view/test/qunit/template."+ext,{"message" :"helloworld"}))


				ok( div[0].getElementsByTagName('h3').length, ext+": h3 written for ")
				ok( /helloworld\s*/.test( div[0].innerHTML ), ext+": hello world present for ")
			})
		});
	}

	test("buildFragment works right", function(){
		can.append( can.$("#qunit-test-area"), can.view("//can/view/test/qunit/plugin.ejs",{}) )
		ok(/something/.test( can.$("#something span")[0].firstChild.nodeValue ),"something has something");
		can.remove( can.$("#something") );
		can.append( can.$("#qunit-test-area"), can.view("//can/view/test/qunit/plugin.ejs",{}) )
		ok(/something/.test( can.$("#something span")[0].firstChild.nodeValue ),"something has something");
		can.remove( can.$("#something") );
		can.append( can.$("#qunit-test-area"), can.view("//can/view/test/qunit/plugin.ejs",{}) )
		ok(/something/.test( can.$("#something span")[0].firstChild.nodeValue ),"something has something");
		can.remove( can.$("#something") );
		can.append( can.$("#qunit-test-area"), can.view("//can/view/test/qunit/plugin.ejs",{}) )
		ok(/something/.test( can.$("#something span")[0].firstChild.nodeValue ),"something has something");
		can.remove( can.$("#something") );
	})


	test("plugin in ejs", function(){

		can.append( can.$("#qunit-test-area"), can.view("//can/view/test/qunit/plugin.ejs",{}) )
		ok(/something/.test( can.$("#something span")[0].firstChild.nodeValue ),"something has something");
		can.remove( can.$("#something") );
	});



	test("async templates, and caching work", function(){
		stop();
		var i = 0;

		can.view.render("//can/view/test/qunit/temp.ejs",{"message" :"helloworld"}, function(text){
			ok(/helloworld\s*/.test(text), "we got a rendered template");
			i++;
			equals(i, 2, "Ajax is not synchronous");
			start();
		});
		i++;
		equals(i, 1, "Ajax is not synchronous")
	})
	test("caching works", function(){
		// this basically does a large ajax request and makes sure
		// that the second time is always faster
		stop();
		var startT = new Date(),
			first;
		can.view.render("//can/view/test/qunit/large.ejs",{"message" :"helloworld"}, function(text){
			first = new Date();
			ok(text, "we got a rendered template");


			can.view.render("//can/view/test/qunit/large.ejs",{"message" :"helloworld"}, function(text){
				var lap2 = (new Date()) - first,
					lap1 =  first-startT;
				// ok( lap1 > lap2, "faster this time "+(lap1 - lap2) )

				start();
			})

		})
	})
	test("hookup", function(){

		can.view("//can/view/test/qunit/hookup.ejs",{})

	})

	test("inline templates other than 'tmpl' like ejs", function(){
		var script = document.createElement('script');
		script.setAttribute('type', 'test/ejs')
		script.setAttribute('id', 'test_ejs')
		script.text = '<span id="new_name"><%= name %></span>';
		document.getElementById("qunit-test-area").appendChild(script);

		var div = document.createElement('div');
		div.appendChild(can.view('test_ejs', {name: 'Henry'}))

		equal( div.getElementsByTagName("span")[0].firstChild.nodeValue , 'Henry');
	});

	//canjs issue #31
	test("render inline templates with a #", function(){
		var script = document.createElement('script');
		script.setAttribute('type', 'test/ejs')
		script.setAttribute('id', 'test_ejs')
		script.text = '<span id="new_name"><%= name %></span>';
		document.getElementById("qunit-test-area").appendChild(script);

		var div = document.createElement('div');
		div.appendChild(can.view('#test_ejs', {name: 'Henry'}));

		//make sure we aren't returning the current document as the template
		equals(div.getElementsByTagName("script").length, 0, "Current document was not used as template")
		if(div.getElementsByTagName("span").length === 1) {
			equal( div.getElementsByTagName("span")[0].firstChild.nodeValue , 'Henry');
		}
	});

	test("object of deferreds", function(){
		var foo = new can.Deferred(),
			bar = new can.Deferred();
		stop();
		can.view.render("//can/view/test/qunit/deferreds.ejs",{
			foo : typeof foo.promise == 'function' ? foo.promise() : foo,
			bar : bar
		}).then(function(result){
			equals(result, "FOO and BAR");
			start();
		});
		setTimeout(function(){
			foo.resolve("FOO");
		},100);
		bar.resolve("BAR");

	});

	test("deferred", function(){
		var foo = new can.Deferred();
		stop();
		can.view.render("//can/view/test/qunit/deferred.ejs",foo).then(function(result){
			equals(result, "FOO");
			start();
		});
		setTimeout(function(){
			foo.resolve({
				foo: "FOO"
			});
		},100);

	});

	/*test("bad url", function(){
		can.render("//asfdsaf/sadf.ejs")
	});*/

	test("hyphen in type", function(){
		var script = document.createElement('script');
		script.setAttribute('type', 'text/x-ejs')
		script.setAttribute('id', 'hyphenEjs')
		script.text = '\nHyphen\n';
		document.getElementById("qunit-test-area").appendChild(script);
		var div = document.createElement('div');
		div.appendChild(can.view('hyphenEjs', {}))

	    ok( /Hyphen/.test(div.innerHTML) , 'has hyphen');
	})

	test("create template with string", function(){
		can.view.ejs("fool", "everybody plays the <%= who %> <%= howOften %>");

		var div = document.createElement('div');
		div.appendChild(can.view('fool', {who: "fool", howOften: "sometimes"}));

		ok( /fool sometimes/.test(div.innerHTML) , 'has fool sometimes'+div.innerHTML);
	})


	test("return renderer", function() {
		can.view.ejs('renderer_test', "This is a <%= test %>");
		var renderer = can.view('renderer_test');
		ok(can.isFunction(renderer), 'Renderer is a function');
		equal(renderer({ test : 'working test' }), 'This is a working test', 'Rendered');
		renderer = can.view("//can/view/test/qunit/template.ejs");
		ok(can.isFunction(renderer), 'Renderer is a function');
		equal(renderer({ message : 'Rendered!' }), '<h3>Rendered!</h3>', 'Synchronous template loaded and rendered');
		// TODO doesn't get caught in Zepto for whatever reason
//		raises(function() {
//			can.view('jkflsd.ejs');
//		}, 'Nonexistent template throws error');
	})
});
