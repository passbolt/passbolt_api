steal('steal/browser', function(){
	steal.browser.selenium = function(options){
		// kill the server in case it was running from before
		this.killServer();
		steal.browser.call(this, options, 'selenium')
		this._startServer();
		this.clientPath = "funcunit/selenium";
	}
	steal.extend(steal.browser.selenium, {
		defaults:  {
			serverPort: 4444,
			serverHost: "localhost"
		},
		trigger: function(){
			browser.trigger.apply(self, arguments);
		}
	});
	steal.browser.selenium.prototype = new steal.browser();
	steal.extend(steal.browser.selenium.prototype, {
		/**
		 * Opens the browser, at each of the specified browsers, one by one
		 * @param {Object} page
		 * @param {Object} browsers
		 */
		open: function(page, browsers){
			this._currentBrowserIndex = 0;
			this.page = this._getPageUrl(page);
			this.browsers = browsers || ["*firefox"];
			this._browserStart(0);
			this._poll();
			// block until we're done
			this.browserOpen = true;
			while(this.browserOpen) {
				java.lang.Thread.currentThread().sleep(1000);
			}
			return this;
		},
		_loadDriverClass: function() {
			var URLClassLoader = Packages.java.net.URLClassLoader,
				URL = java.net.URL,
				File = java.io.File,
				ss = new File("funcunit/selenium/selenium-java-client-driver.jar"),
				ssurl = ss.toURL(),
				urls = java.lang.reflect.Array.newInstance(URL, 1);
			urls[0] = new URL(ssurl);

			var clazzLoader = new URLClassLoader(urls),
				mthds = clazzLoader.loadClass("com.thoughtworks.selenium.DefaultSelenium").getDeclaredConstructors(),
				rawMeth = null;
			//iterate through methods to find the one we are looking for
			for ( var i = 0; i < mthds.length; i++ ) {
				var meth = mthds[i]; 
				if ( meth.toString().match(/DefaultSelenium\(java.lang.String,int,java.lang.String,java.lang.String\)/) ) {
					constructor = meth;
				}
			} 
			return function( serverHost, serverPort, browserStartCommand, browserURL ) {
				var host = new java.lang.String(serverHost),
					port = new java.lang.Integer(serverPort),
					cmd = new java.lang.String(browserStartCommand),
					url = new java.lang.String(browserURL);
				return constructor.newInstance(host, port, cmd, url);
			};
		},
		_startServer: function(){
			//first lets ping and make sure the server is up
			var port = this.options.serverPort, 
				addr = java.net.InetAddress.getByName(this.options.serverHost)
			try {
				var s = new java.net.Socket(addr, port)
			} 
			catch (ex) {
				spawn(function(){
					var jarCommand = 'java -jar '+
						'funcunit/selenium/selenium-server-standalone-2.12.0.jar'+
						' -userExtensions funcunit/selenium/user-extensions.js';
					if (java.lang.System.getProperty("os.name").indexOf("Windows") != -1) {
						var command = 'start "selenium" ' + jarCommand;
						runCommand("cmd", "/C", command.replace(/\//g, "\\"))
					}
					else {
						var command = jarCommand + " > selenium.log 2> selenium.log &";
						runCommand("sh", "-c", command);
					}
				})
				var timeouts = 0, 
					started = false;
				var pollSeleniumServer = function(){
					try {
						var s = new java.net.Socket(addr, port)
						started = true;
					} 
					catch (ex) {
						if (timeouts > 20) {
							print("Selenium is not running. Please use js -selenium to start it.")
							quit();
						} else {
							timeouts++;
						}
					}					
				}
				while(!started){
					java.lang.Thread.currentThread().sleep(1000);
					pollSeleniumServer();
				}
			}
			this.DefaultSelenium = this._loadDriverClass();
		},
		killServer: function(){
			if (java.lang.System.getProperty("os.name").indexOf("Windows") != -1) {
				runCommand("cmd", "/C", 'taskkill /fi "Windowtitle eq selenium" > NUL')
			} else { // mac
				runCommand("sh", "-c", "ps aux | awk '/selenium\\// {print$2}' | xargs kill -9")
			}
		},
		// create new selenium instance, start it, open page, set FuncUnit.mode = "Selenium", start polling for data
		_browserStart: function(index){
			var browser = this.browsers[this._currentBrowserIndex];
			this.trigger("browserStart", {
				browser: browser
			})
			this.selenium = this.DefaultSelenium(this.options.serverHost, 
				this.options.serverPort, 
				browser, 
				this.page);
			this.selenium.start();
			this.selenium.open(this.page);
			selThreads.resumePolling();
		},
		close: function(data){
			//print(">>>>>>>>>>   CLOSING")
			//this.keepPolling = false;
			var browser = this.browsers[this._currentBrowserIndex];
			this.trigger("browserDone", {
				browser: browser
			});
			selThreads.pausePolling();
			this.selenium.close();
			this.selenium.stop();
			//print(">>>>>>>>>>   STOPPED")
			this._currentBrowserIndex++;
			if (this._currentBrowserIndex < this.browsers.length) {
				this._browserStart(this._currentBrowserIndex)
			} 
			else {
				selThreads.stopPolling();
				//print(">>>>>>>>>>   ALL DONE")
				this.trigger("allDone");
				this.killServer();
				this.browserOpen = false;
			}
		},
		evaluate: function(fn){
			var txt = fn.toString().replace(/\n|\r\n/g,""),
				evalText = "Selenium.evaluate('"+txt+"')";
			var res = this.selenium.getEval(evalText);
			res = ""+res;
			return res;
		},
		injectJS: function(file){
			var scriptText = readFile(file).replace(/\n|\r\n/g,""),
				evalText = "Selenium.injectJS('"+escape(scriptText)+"')";
			this.selenium.getEval(evalText);
			return true;
		},
		_poll: function(){
			selThreads.poll(this);
			
		}
	});
	
	var keepPolling = false,
		stopPolling = false;
	
	
	var selThreads = {
		poll : function(self){
			this.resumePolling();
			
			spawn(function(){
				
				var stop = selThreads.getResult(self);
				if(!stop){
					java.lang.Thread.currentThread().sleep(350);
					arguments.callee();
				}
			})
			
		},
		pausePolling :  sync(function(){
			keepPolling = false;
		}),
		resumePolling : sync(function(){
			keepPolling = true;
		}),
		stopPolling : sync(function(){
			stopPolling = true;
			keepPolling = false;
		}),
		getResult : sync(function(self){
			if(!keepPolling){
				return stopPolling;
			}
			var resultJSON, 
				res,
				evt;
			//self.keepPolling = true;
			//print(">>>>>>>>>>   POLLING")
			resultJSON = self.selenium.getEval("Selenium.getResult()");
			eval("res = "+resultJSON);
			if(res && res.length){
				for (var i = 0; i < res.length; i++) {
					evt = res[i];
					self.trigger(evt.type, evt.data);
				}
			}
			return stopPolling;
		}),
		keepPolling : false
	}
	
})