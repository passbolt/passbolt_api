//we probably have to have this only describing where the tests are
steal('funcunit')
	.then('jquery/test/qunit')
	.then('funcunit/syn/test/qunit')
	.then('funcunit/test/funcunit')
	.then(function(){
		FuncUnit.jQuery("<iframe src='steal/test/qunit.html'></iframe>").appendTo(document.body)
	})
	.then('mxui/test.js')