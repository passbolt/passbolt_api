FuncUnit = {
	// the list of browsers that selenium runs tests on
	// browsers: ["*firefox", "*googlechrome"],
	
	// the root where funcunit folder lives, used when running from commandline
	// jmvcRoot: "http://localhost:3000/javascriptmvc",
	
	// used for debugging
	// the number of milliseconds between commands, "slow" is 500 ms
	// speed: "slow",
	
	// turn on if you want to exit hard with the -e flag
	// failOnError: true,
	
	// any files or directories that coverage calculations should ignore
	coverageIgnore: ['*/test', "*_test.js", "*jquery.js", "*qunit.js"]
	
	// uncomment this to ignore all JMVC directories
	// coverageIgnore: ["!jmvc"]
}