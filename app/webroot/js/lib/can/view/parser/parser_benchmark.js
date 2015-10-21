steal('can/util','can/view/parser', 'can/test/benchmarks.js', 'can/test',function (can, parser, benchmarks) {
	/* jshint ignore:start */

	can.ajax({
		async: false,
		url: can.test.path("view/parser/benchmark.stache"),
		dataType: 'text',
		success: function (data) {
			// Make sure we got some text back.
			window._ParserBenchmarkText = data;
		}
	});
	var handles = {
		start:     function( tagName, unary ){},
		end:       function( tagName, unary ){},
		close:     function( tagName ){},
		attrStart: function( attrName ){},
		attrEnd:   function( attrName ){},
		attrValue: function( value ){},
		chars:     function( value ){},
		comment:   function( value ){},
		special:   function( value ){},
		done:      function( ){}
	 };
	
	var intermediate = can.view.parser(window._ParserBenchmarkText,handles);
	
	benchmarks.add(
		"can/view/stache/parser Updating elements",
		function () {
			var handles = {
				start:     function( tagName, unary ){},
				end:       function( tagName, unary ){},
				close:     function( tagName ){},
				attrStart: function( attrName ){},
				attrEnd:   function( attrName ){},
				attrValue: function( value ){},
				chars:     function( value ){},
				comment:   function( value ){},
				special:   function( value ){},
				done:      function( ){}
			 };
			
		},
		function () {
			can.view.parser(intermediate,handles);
		},
		function () {
			
		});
	/* jshint ignore:end */
});
