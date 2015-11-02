var steal = require('../main');
var path = require("path");

System.logLevel = 0;

steal.config({
	config: path.join(__dirname, "node_test_plugins", "config.js"),
	main: "main"
});

steal.startup().then(function(){
	console.log("worked");
},function(e){
	console.log(e);
})
