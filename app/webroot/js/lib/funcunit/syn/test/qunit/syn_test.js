steal("funcunit/syn/synthetic.js", function(Syn){
	
module("funcunit/syn")

st = {
	g: function( id ) {
		return document.getElementById(id)
	},
	log: function( c ) {
		if(st.g("mlog"))
			st.g("mlog").innerHTML = st.g("mlog").innerHTML+c+"<br/>"
	},
	binder: function( id, ev, f ) {
		st.bind(st.g(id), ev, f)
	},
	unbinder: function( id, ev, f ) {
		st.unbind(st.g(id), ev, f)
	},
	bind : function(el, ev, f){
		return el.addEventListener ? 
			el.addEventListener(ev, f, false) : 
			 el.attachEvent("on"+ev, f) 
	},
	unbind : function(el, ev, f){
		return el.addEventListener ?
			el.removeEventListener(ev, f, false) :
			el.detachEvent("on"+ev, f) 
	},
	rootJoin : (typeof steal == "undefined" ? function(path){
			return "../../"+path;
		} : 
		function(path){ return steal.config().root.join(path) } )
};


setTimeout(function(){
	if(Syn.support.ready ==2){
		for(var name in Syn.support){
			st.log(name+": "+Syn.support[name])
		}
	}else{
		setTimeout(arguments.callee, 1);
	}
	
},1);

test("Selecting a select element", function(){
	st.g("qunit-test-area").innerHTML = 
		"<form id='outer'><select name='select'><option value='1' id='one'>one</option><option value='2' id='two'>two</option></select></form>";
	
	var change = 0, changef = function(){
		change++;
	}

	st.g("outer").select.selectedIndex = 0;

	st.bind(st.g("outer").select,"change",changef );
	
	stop()
	Syn.click( st.g("two"), function(){
		equals(change, 1 , "change called once")
		equals(st.g("outer").select.selectedIndex, 1, "Change Selected Index");
		//st.g("qunit-test-area").innerHTML = ""
		start();
	})



	
})

test("scrollTop triggers scroll events", function(){
	st.g("qunit-test-area").innerHTML = "<div id='scroller' style='height:100px;width: 100px;overflow:auto'>"+
			"<div style='height: 200px; width: 100%'>text"+
			"</div>"+
			"</div>";
			
	st.binder("scroller","scroll",function(ev){
		ok(true,"scrolling created just by changing ScrollTop");
		st.g("qunit-test-area").innerHTML ="";
		start();
	} );
	stop();
	setTimeout(function(){
		var sc = st.g("scroller");
		sc && (sc.scrollTop = 10);
	
	},13)
	
})

if(!Syn.skipFocusTests){
	test("focus triggers focus events", function(){
		st.g("qunit-test-area").innerHTML = "<input type='text' id='focusme'/>";
		
		st.binder("focusme","focus",function(ev){
			ok(true,"focus creates event");
			st.g("qunit-test-area").innerHTML ="";
			start();
		} );
		stop();
		setTimeout(function(){
			st.g("focusme").focus();
		
		},10)
		
	});
};

})