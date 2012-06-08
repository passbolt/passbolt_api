(function($){

var confirms = [], 
	prompts = [], 
	currentDocument,
	currentHref,
	lookingForNewDocument = false,
	urlWithoutHash = function(url){
		return url.replace(/\#.*$/, "");
	},
	absolutize = function(url){
		var f = steal.File(url);
		return f.protocol() ? f.path : f.joinFrom(steal.pageUrl().dir(), true);
	},
	// returns true if url matches current window's url
	isCurrentPage = function(url){
		var pathname = urlWithoutHash(FuncUnit.win.location.pathname),
			href = urlWithoutHash(FuncUnit.win.location.href),
			url = urlWithoutHash(url);
		// must strip off hash from URLs
		if( pathname === url || href === url ){
			return true;
		}
		return false;
	};
/**
 * @add FuncUnit
 */
$.extend(FuncUnit,{
	/**
	 * @attribute browsers
	 * Used to configure the browsers selenium uses to run FuncUnit tests.  See the 
	 * [funcunit.selenium Selenium] page for more information.
	 */
	
	// open is a method
	/**
	 * Opens a page.  It will error if the page can't be opened before timeout. If a URL begins with "//", pages are opened 
	 * from the FuncUnit root (the root folder where funcunit is located)
	 * ### Example 

    S.open("//app/app.html")

	 * 
	 * @param {String} path a full or partial url to open.
	 * @param {Function} success
	 * @param {Number} timeout
	 */
	open: function( path, success, timeout ) {
		var fullPath = FuncUnit.getAbsolutePath(path), 
		temp;
		if(typeof success != 'function'){
			timeout = success;
			success = undefined;
		}
		FuncUnit.add({
			method: function(success, error){ //function that actually does stuff, if this doesn't call success by timeout, error will be called, or can call error itself
				steal.dev.log("Opening " + path)
				FuncUnit._open(fullPath, error);
				FuncUnit._onload(function(){
					success()
				}, error);
			},
			success: success,
			error: "Page " + path + " not loaded in time!",
			timeout: timeout || 30000
		});
	},
	_open: function(url){
		hasSteal = false;
		// this will determine if this is supposed to open within a frame
		FuncUnit.frame =  $('#funcunit_app').length? $('#funcunit_app')[0]: null;
	
		
		// if the first time ..
		if (newPage) {
			if(FuncUnit.frame){
				FuncUnit.win = FuncUnit.frame.contentWindow;
				FuncUnit.win.location = url;
			}
			else{
				// giving a large height forces it to not open in a new tab and just opens to the window's height
				var width = $(window).width();
				FuncUnit.win = window.open(url, "funcunit",  "height=1000,toolbar=yes,status=yes,width="+width/2+",left="+width/2);
				// This is mainly for opera. Other browsers will hit the unload event and close the popup.
				// This block breaks in IE (which never reaches it) because after closing a window, it throws access 
				// denied any time you try to access it, even after reopening.
				if(FuncUnit.win.___FUNCUNIT_OPENED){
					FuncUnit.win.close();
					FuncUnit.win = window.open(url, "funcunit",  "height=1000,toolbar=yes,status=yes,left="+width/2);
				}
				
				
				if(!FuncUnit.win){
					throw "Could not open a popup window.  Your popup blocker is probably on.  Please turn it off and try again";
				}
			}
		}
		// otherwise, change the frame's url
		else {
			lookingForNewDocument = true;
			if(isCurrentPage(url)){
				// set the hash and reload
				FuncUnit.win.location.hash = url.split('#')[1] || '';
				FuncUnit.win.location.reload(true);
			} else {
				// setting the location forces a reload; IE freaks out if you try to do both
				FuncUnit.win.location = url;
			}
			// setting to null b/c opera uses the same document
			currentDocument = null;
		}
		lookingForNewDocument = true;
	},
	/**
	 * When a browser's native confirm dialog is used, this method is used to repress the dialog and simulate 
	 * clicking OK or Cancel.  Alerts are repressed by default in FuncUnit application windows.
	 * @codestart
	 * S.confirm(true);
	 * @codeend
	 * @param {Boolean} answer true if you want to click OK, false otherwise
	 */
	confirm: function(answer){
		confirms.push(!!answer)
	},
	/**
	 * When a browser's native prompt dialog is used, this method is used to repress the dialog and simulate 
	 * clicking typing something into the dialog.
	 * @codestart
	 * S.prompt("Harry Potter");
	 * @codeend
	 * @param {String} answer Whatever you want to simulate a user typing in the prompt box
	 */
	prompt: function(answer){
		prompts.push(answer)
	},
	_opened: function(){
		if (!this._isOverridden("alert")) {
			FuncUnit.win.alert = function(){}
		}
		
		if (!this._isOverridden("confirm")) {
			FuncUnit.win.confirm = function(){
				var res = confirms.shift();
				return res;
			}
		}
		
		if (!this._isOverridden("prompt")) {
			FuncUnit.win.prompt = function(){
				return prompts.shift();
			}
		}
	},
	_isOverridden:function(type) {
		return !(/(native code)|(source code not available)/.test(FuncUnit.win[type]));
	},
	_onload: function(success, error){
		// saver reference to success
		loadSuccess = function(){
			if(FuncUnit.win.steal){
				hasSteal = true;
			}
			// called when load happens ... here we check for steal
			// console.log("success", (FuncUnit.win.steal && FuncUnit.win.steal.isReady) || !hasSteal, 
				// "isReady", (FuncUnit.win.steal && FuncUnit.win.steal.isReady));
			if((FuncUnit.win.steal && FuncUnit.win.steal.isReady) || !hasSteal){
				success();
			}else{
				setTimeout(arguments.callee, 200)
			}
				
		}
		
		// we only need to do this setup stuff once ...
		if (!newPage) {
			return;
		}
		newPage = false;
		
		if (FuncUnit.support.readystate)
		{
			poller();
		}
		else {
			unloadLoader();
		}
		
	},
	/**
	 * @hide
	 * Gets a path, will use steal if present
	 * @param {String} path
	 */
	getAbsolutePath: function( path ) {
		if ( /^\/\//.test(path) ){
			return steal.File(absolutize(steal.root.path)).join(path.substr(2)) + '';
		} else {
			return absolutize(path);
		}
	},
	/**
	 * @attribute win
	 * Use this to refer to the window of the application page.  You can also 
	 * reference window.document.
	 * @codestart
	 * S(S.window).innerWidth(function(w){
	 *   ok(w > 1000, "window is more than 1000 px wide")
	 * })
	 * @codeend
	 */
	win: null,
	// for feature detection
	support: {
		readystate: "readyState" in document
	},
	/**
	 * Used to evaluate code in the application page.
	 * @param {String} str the code to evaluate
	 * @return {Object} the result of the evaluated code
	 */
	eval: function(str){
		return FuncUnit.win.eval(str)
	},
	// return true if document is currently loaded, false if its loading
	// actions check this
	documentLoaded: function(){
		var loaded = FuncUnit.win.document.readyState === "complete" && 
				     FuncUnit.win.location.href != "about:blank";
		return loaded;
	},
	// return true if new document found
	checkForNewDocument: function(){
		var documentFound = ((FuncUnit.win.document !== currentDocument && // new document 
							!FuncUnit.win.___FUNCUNIT_OPENED) // hasn't already been marked loaded
							// covers opera case after you click a link, since document doesn't change in opera
							|| (currentHref != FuncUnit.win.location.href)) && // url is different 
							FuncUnit.documentLoaded(); // fully loaded
		if(documentFound){
			// reset flags
			lookingForNewDocument = false;
			currentDocument = FuncUnit.win.document;
			currentHref = FuncUnit.win.location.href;
			
			// mark it as opened
			FuncUnit.win.___FUNCUNIT_OPENED = true;
			// reset confirm, prompt, alert
			FuncUnit._opened();
		}
		
		return documentFound;
	}
});

	//don't do any of this if in rhino
	if (navigator.userAgent.match(/Rhino/)) {
		return;	
	}
	
	
	FuncUnit.win = null;
	var newPage = true, 
		hasSteal = false,
		unloadLoader, 
		loadSuccess, 
		firstLoad = true,
		onload = function(){
			FuncUnit.win.document.documentElement.tabIndex = 0;
			setTimeout(function(){
				FuncUnit.win.focus();
				var ls = loadSuccess
				loadSuccess = null;
				if (ls) {
					ls();
				}
			}, 0);
			Syn.unbind(FuncUnit.win, "load", onload);
		},
		onunload = function(){
			FuncUnit.stop = true;
			removeListeners();
			setTimeout(unloadLoader, 0)
			
		},
		removeListeners = function(){
			Syn.unbind(FuncUnit.win, "unload", onunload);
			Syn.unbind(FuncUnit.win, "load", onload);
		}
	unloadLoader = function(){
		if(!firstLoad) // dont remove the first run, fixes issue in FF 3.6
			removeListeners();
		
		Syn.bind(FuncUnit.win, "load", onload);
		
		//listen for unload to re-attach
		Syn.bind(FuncUnit.win, "unload", onunload)
	}
	
	//check for window location change, documentChange, then readyState complete -> fire load if you have one
	var newDocument = false, 
		poller = function(){
			var ls;
			// right after setting a new hash and reloading, IE barfs on this occassionally (only the first time)
			try{
				if(FuncUnit.win && FuncUnit.win.document == null){
					return;
				}
			}catch(e){
				setTimeout(arguments.callee, 500);
				return;
			}
			
			if (lookingForNewDocument && FuncUnit.checkForNewDocument() ) {
				
				ls = loadSuccess;
				
				loadSuccess = null;
				if (ls) {
					FuncUnit.win.focus();
					FuncUnit.win.document.documentElement.tabIndex = 0;
					
					ls();
				}
			}
			
		setTimeout(arguments.callee, 500)
	}

	// All browsers except Opera close the app window on a reload.  This is to fix the case the URL to be opened 
	// has a hash.  In this case, window.open doesn't cause a reload if you reuse an existing popup, so we need to close.
	$(window).unload(function(){
		FuncUnit.win && FuncUnit.win.close();
	});
	
})(window.jQuery || window.FuncUnit.jQuery)
