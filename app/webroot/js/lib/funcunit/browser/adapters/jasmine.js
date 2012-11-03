steal('../core.js', function(FuncUnit) {
var paused = false;
FuncUnit.unit = {
	pauseTest:function(){
		paused = true;
		waitsFor(function(){
			return paused === false;
		})
	},
	resumeTest: function(){
		paused = false;
	},
	assertOK: function(assertion, message){
		expect(assertion).toBeTruthy();
	},
	equiv: function(expected, actual){
		return jasmine.getEnv().equals_(expected, actual)
	}
}
return FuncUnit;
})()
