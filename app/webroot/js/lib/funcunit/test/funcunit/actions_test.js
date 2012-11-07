module("scroll", {
   setup:function() {
      S.open("//funcunit/test/scroll.html", null, 10000) ;
   }
 })

test("scroll on click", function(){
	S('#innerdiv').click()
	S("#scrolldiv").scrollTop(100, "Scrolled down 100")
	S("#scrolldiv").scrollLeft(100, "Scrolled left 100")
})

test("auto scrollleft", function(){  
	S("#scrolldiv").scroll('left', 100)
	S('#scrolldiv').scrollLeft(100, 'scroll left worked')
})

test("auto scrolldown", function(){  
	S("#scrolldiv").scroll('top', 100)
	S('#scrolldiv').scrollTop(100, 'scroll top worked')
})