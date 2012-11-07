// A Rhino-version of steal
(function(win){
	
	// if(typeof console == 'undefined'){
		console = {
			log: function(){
				print.apply(null, arguments)
			}
		}
	// }
	// we are going to need to swap this out while loading the scripts
	// it's possible a new version of steal will need to be loaded / replace the old one
	// we will have to check the file to see if it is "stealing" anything
	// if it is .. run, but we also need to convert the fn type to not actually call anything
	// this will create a shell
	win.steal = {
		types : {
			"js" : function(options, success){
				if(options.text){
					eval(text)
				}else{
					load(options.src)
				}
				success()
			}
		}
	}
	load("steal/steal.js");
	load("steal/rhino/file.js");
})(this);

