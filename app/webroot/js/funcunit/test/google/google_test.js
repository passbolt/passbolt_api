steal('funcunit', function(){
	module('google test', {
		setup: function(){
			S.open("/")
		}
	})
	
	test('autocomplete', function(){
		S('#lst-ib').type('Angry');
		S('.gssb_e .gsq_a tr').size(4);
		S('#rso a:first').text("Angry Birds - Rovio", "Angry birds is the first link")
		S('.vspii:first').click();
		S('#nycp').visible("Preview shows up")
	})
	
	test('image search', function(){
		S('#lst-ib').type('Angry');
		S('.gssb_e .gsq_a tr').size(4);
		S(".mitem:contains('Images') a").click();
		S('#rg_hi').visible("First image shows up")
	})
})
