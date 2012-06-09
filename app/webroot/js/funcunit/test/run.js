//integration point for JavaScriptMVC testing

print("==========================  funcunit ============================")

load('steal/rhino/rhino.js');
steal('funcunit/commandline/selenium.js', 
	'funcunit/commandline/phantomjs.js', 
	'funcunit/commandline/envjs.js', 
	function(){
		FuncUnit.loader.selenium('funcunit/funcunit.html');
		FuncUnit.loader.phantomjs('funcunit/funcunit.html');
		FuncUnit.loader.envjs('funcunit/qunit/test/qunit.html');
		FuncUnit.loader.phantomjs('funcunit/qunit/test/qunit.html');
		FuncUnit.loader.selenium('funcunit/qunit/test/qunit.html');
	})