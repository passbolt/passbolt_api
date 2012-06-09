(function(){

	if(steal.options.instrument){
		QUnit.done(function(){
			// send to console
			if(steal.options.browser){
				steal.client.trigger("coverage", steal.instrument.compileStats());
			}
		})
	}
	
	// if there's a failed assertion, don't run the rest of this test, just fail and move on
	QUnit.log(function(data){
		if(data.result === false) {
			FuncUnit._queue = [];
			start();
		}
	})
	
	if(steal.options.browser === "phantomjs"){
		var ifrm = document.createElement("iframe");
		ifrm.id = 'funcunit_app';
		ifrm.setAttribute("width", "960");
		ifrm.setAttribute("height", "800");
		document.body.insertBefore(ifrm, document.body.firstChild);
	}

	if(steal.options.browser){
		var evts = ['begin', 'testStart', 'testDone', 'moduleStart', 'moduleDone', 'done', 'log'], 
			type,
			orig = {};
		
		for (var i = 0; i < evts.length; i++) {
			type = evts[i];
			(function(type){
				QUnit[type] (function(data){
					if(orig[type]){
						orig[type].apply(this, arguments);
					}
					
					steal.client.trigger(type, data);
				});
			})(type);
		}
	}
	
})()
