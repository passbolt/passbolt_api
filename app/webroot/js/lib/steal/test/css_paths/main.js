import 'css_paths/folder/main.less!';
import 'css_paths/folder/main.css!';

if(typeof window !== "undefined" && window.QUnit) {
	var image = new Image();
	image.onload = function(){
		QUnit.ok(true, "image loaded");
		QUnit.start();
		removeMyself();
	};
	image.onerror = function(){
		QUnit.ok(false, "image not loaded");
		QUnit.start();
		removeMyself();
	};
	image.src = $("#test-element1").css("background-image").replace(/url\("?/,"").replace(/"?\)/,"");
} else {
	console.log("background-image", $("#test-element1").css("background-image"));
}
