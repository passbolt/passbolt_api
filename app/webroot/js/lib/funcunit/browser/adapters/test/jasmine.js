steal('funcunit/jasmine')
	.then('funcunit')
	.then('./app.js', function(){
		describe("Adapters", function(){
			it("should use the jasmine adapter", function(){
				S('.clickme').click();
				S('.clickresult').text("clicked", "clicked the link")
			})
		})
	})