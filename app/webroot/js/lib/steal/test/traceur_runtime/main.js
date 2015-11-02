"format amd";
define([], function(){
	var Character = (function() {
	    var Character = function Character(x, y) {
	      this.x = x;
	      this.y = y;
	    };
	    return ($traceurRuntime.createClass)(Character, {}, {});
	}());
	
	QUnit.ok(Character, "got an object");
	QUnit.start();
	removeMyself();
  return Character;
});

