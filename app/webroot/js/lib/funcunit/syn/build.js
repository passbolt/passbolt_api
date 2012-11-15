load('steal/rhino/rhino.js')

/**
 * Build syn, funcunit, user-extensions
 */
steal.File('funcunit/syn/dist').mkdir()
steal('steal/build/pluginify', function(s){
	steal.build.pluginify("funcunit/syn",{
		global: "true",
		nojquery: true,
		out: "funcunit/syn/dist/syn.js"
	})
})
// add drag/drop

var copyToDist = function(path){
	var fileNameArr = path.split("/"),
		fileName = fileNameArr[fileNameArr.length - 1];
	print("copying to "+fileName)
	steal.File(path).copyTo("funcunit/syn/resources/"+fileName);
}