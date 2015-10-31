
var worker = new Worker(System.stealURL+"?main=webworkers/worker&config="+System.configPath);

worker.addEventListener("message", function(ev){
	if(window.QUnit) {
		QUnit.deepEqual(ev.data,  {name: "dep"}, "got a post message");
		QUnit.start();
		removeMyself();
	} else {
		console.log("got message", ev);
	}
	
});

module.exports = worker;
