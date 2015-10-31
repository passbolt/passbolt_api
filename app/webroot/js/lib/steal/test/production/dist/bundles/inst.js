/*bundlesConfig*/
System.bundles = {"bundles/inst":["inst"],"bundles/bar.css!":["bar.css!$css"]};
/*stealconfig*/


define("$css",[], function(){
	return {
		instantiate: function(load) {
			load.metadata.format = "css";
			load.metadata.buildType = "css";
			load.metadata.execute = function(){
				var head = document.head || document.getElementsByTagName('head')[0],
					style = document.createElement('style');
			
				style.type = 'text/css';
				if (style.styleSheet){
					style.styleSheet.cssText = load.source;
				} else {
					style.appendChild(document.createTextNode(load.source));
				}
				head.appendChild(style);
				return System.newModule({});
			};
		}
	};
});

/*main*/
define("inst",["bar.css!"],function(e){
	
	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.equal(document.getElementById("test-element").clientWidth, 100, "element width set by css");
	
		QUnit.start();
		removeMyself();
	} else {
		console.log("width", document.getElementById("test-element").clientWidth);
	}
	
});
