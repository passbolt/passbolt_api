module('Static')

test('steal.makeOptions', function(){
	var options = steal.makeOptions({
		id: 'jquery'
	});
	equal(options.id + "", 'jquery/jquery.js')
})