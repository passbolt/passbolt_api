steal(
	"funcunit/qunit", 
  	"funcunit/syn",
  	"funcunit/syn/drag"
  )
  .then("jquery", "jquery/event/drag", "jquery/event/drop")
  .then("./drag_test.js")