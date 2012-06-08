steal('steal/browser/phantomjs', function(){
	FuncUnit.loader.phantomjs = function(page){
		FuncUnit.browser = new steal.browser.phantomjs({
			// print: true
		});
		
		FuncUnit.bindEvents(FuncUnit.browser)
		FuncUnit.browser.open(page)
	}
})