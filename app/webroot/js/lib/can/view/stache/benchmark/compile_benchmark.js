steal('can/util','can/view/stache', 'steal-benchmark', 'can/test',function (can, stache, b) {
	
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
	
	b.suite("can/view/stache compile").add(
		"can/view/stache compile template from string",
		function () {

		},
		function () {
			window.frag = can.stache(window._ParserBenchmarkText);
		},
		function () {
			
		})
		
	.add(
		"can/view/stache compile template from string",
		function () {

		},
		function () {
			window.frag = can.stache(intermediate);
		},
		function () {
			
		});
	/* jshint ignore:end */
});
