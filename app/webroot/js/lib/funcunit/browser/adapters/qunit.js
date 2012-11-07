steal('../core.js', function(FuncUnit) {
FuncUnit.unit = {
	pauseTest:function(){
		stop();
	},
	resumeTest: function(){
		start();
	},
	assertOK: function(assertion, message){
		ok(assertion, message)
	},
	equiv: function(expected, actual){
		return QUnit.equiv(expected, actual);
	}
}
});