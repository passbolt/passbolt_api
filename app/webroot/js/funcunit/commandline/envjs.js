steal('steal/browser/envjs', function(){
	FuncUnit.loader.envjs = function(page){
		FuncUnit.browser = new steal.browser.envjs({
			fireLoad: true
		});
		
		FuncUnit.bindEvents(FuncUnit.browser);
		FuncUnit.browser.open(page);
	}
})