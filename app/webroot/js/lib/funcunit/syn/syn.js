steal('./synthetic','./mouse','./browsers','./key.js','./drag/drag.js',function(Syn){
	return Syn;
})


steal('./synthetic.js')
	.then('./mouse.js')
	.then('./browsers.js')
	.then('./key.js')
	.then('./drag/drag.js');
