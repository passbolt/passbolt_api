print("==========================  jquery/generate/scaffold ============================")

load('steal/rhino/rhino.js');
load('steal/test/test.js');

(function(rhinoSteal){
	
_args = ['cookbook']; 
load('jquery/generate/app');
_args = ['Cookbook.Models.Recipe']; 
load('jquery/generate/scaffold');

load('steal/rhino/rhino.js');
cookbookContent = readFile('cookbook/cookbook.js').
    replace(".models()", ".models('recipe')").
    replace(".controllers()", ".controllers('recipe')");
new steal.File('cookbook/cookbook.js').save( cookbookContent );

qunitContent = readFile('cookbook/test/qunit/qunit.js').
    replace(".then(\"cookbook_test\")", ".then(\"recipe_test\")");
new steal.File('cookbook/test/qunit/qunit.js').save( qunitContent );

funcunitContent = readFile('cookbook/test/funcunit/funcunit.js').
    replace(".then(\"cookbook_test\")", ".then(\"recipe_controller_test\")");
new steal.File('cookbook/test/funcunit/funcunit.js').save( funcunitContent );
 
//now see if unit and functional run
print("-- Run unit tests for cookbook --");
load('funcunit/loader.js');
FuncUnit.load('cookbook/qunit.html');

print("-- Run functional tests for cookbook --");
load('steal/rhino/rhino.js');
load('funcunit/loader.js');
FuncUnit.load('cookbook/funcunit.html');

print("-- Compress cookbook --");
load("cookbook/scripts/build.js")

print("-- Generate docs --");
_args = ['cookbook/cookbook.html']
load("documentjs/documentjs.js");
DocumentJS('cookbook/cookbook.html');

load('steal/rhino/rhino.js');
cookbookPage = readFile('cookbook/cookbook.html').
    replace("steal.js", "steal.production.js");
new steal.File('cookbook/cookbook.html').save( cookbookPage );

print("== complete !!!!!!!!!!!!!!!!!!!!!!!!!!!!\n");

//print("-- cleanup --");
steal.File("cookbook").removeDir();

})(steal);

