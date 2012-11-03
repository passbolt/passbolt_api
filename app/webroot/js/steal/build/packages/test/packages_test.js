// load("steal/build/packages/test/packages_test.js")
/**
 * Tests compressing a very basic page and one that is using steal
 */
load('steal/rhino/rhino.js')
steal('steal', 'steal/test', function( s ) {
	// STEALPRINT = false;
	s.test.module("steal/build/packages")

	s.test.test("steal.build.packages", function(){
		load('steal/rhino/rhino.js');
		steal('steal/build/packages',
			function(){
				load('steal/build/packages/test/packages_test/scripts/build.js')
				s.test.open('steal/build/packages/test/packages_test/prod.html')
				s.test.ok(typeof window.appA === "undefined");
				s.test.clear();
				s.test.open('steal/build/packages/test/packages_test/prod.html#a')
				s.test.equals(window.appA, true);
				
				// TODO change this test to actually open the app in packages mode instead of hardcoding the files
				var filesToRemove = [
					'production.js',
					'packages/app_a.js',
					'packages/app_b.js',
					'packages/app_c.js',
					'packages/app_d.js',
					'packages/app_a-app_b.js',
					'packages/app_a-app_b-app_c-app_d.js'
				];
				
				var path;
				for(var i=0;i<filesToRemove.length; i++){
					path = 'steal/build/packages/test/packages_test/'+filesToRemove[i];
					// print('checking '+path)
					s.test.ok(s.File(path).exists())
					s.test.remove(path)
				}
					
				s.test.clear();
			});
	});

});