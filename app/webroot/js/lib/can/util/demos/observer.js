steal('can/observe', 
	'can/util/json.js',
	'can/control',
function(){
	
	
		Observer = can.Control({
			defaults: { 
				observeName : "observe",
				end : function(){
					return "                          "
				}
			}
		},{
			init : function(){
				// draw
				var data = this.options.observe.attr()
				this.options.obs = new can.Observe(data);
				this.on();
				this.render();
			},
			render : function(){
				var frag =  this.draw(0,undefined,this.options.obs)
				this.element.html(frag).find(".end").eq(-1).replaceWith(
					"<span class='end'>})" +this.options.end.call(this)+"</span>");
			},
			draw : function(indent, name, value){
				indent = indent || 0;
				var space = new Array(indent*4).join(" "),
					frag = document.createDocumentFragment();
					
				var self = this,
					namePart;
				if(!name && indent === 0){
					if(this.options.fullName){
						namePart =  this.options.fullName
					} else {
						namePart =  this.options.observeName+".attr("
					}
					
				} else if(typeof name === 'string'){
					namePart = "<span class='name'>"+name+"</span>"+" : "
				} else {
					namePart = "<span class='name'>"+"</span>"+""
				}

				if(namePart){
					var propName = $("<span>").html(
						(!name && indent === 0 ? "" : "\n")+
						"<span class='remove'>"+space+"</span>"+
						namePart
						);
					if(name !== undefined){
						propName.addClass('propName')
							.data("propName", name) 
					} 
					
					
				} 
				
				frag.appendChild( propName[0] )
				
				if(value instanceof can.Observe) {
					var isList = value instanceof can.Observe.List,
						obs = $("<span><span class='start'>"+
							(isList ? "[" : "{") 
						+"</span></span>")
						.addClass("observe")
						.data("observe",value)
						.data("indent",indent+1);
						

					var children = $()
					value.each(function(val, name){
						var section = self.draw(indent+1, /*isList ? undefined :*/ name, val);
						children.push(section);
					})
					// remove the last ","

					if(!children.length){
						obs.append("<span class='end'>"+
							(isList ? "]" : "}") +",                    </span>")
	
					} else {
						// remove the last ","
						if(children.eq(-1)[0].lastChild.className === 'comma'){
							$(children.eq(-1)[0].lastChild).remove()
						} else if(children.eq(-1)[0].lastChild.className === 'observe'){
							var end = $(children.eq(-1)[0].lastChild).children('.end');
							end[0].firstChild.nodeValue
								= end[0].firstChild.nodeValue.replace(",","")
						}
						
						
						obs.append(children)
						obs.append("\n"+space+"<span class='end'>"+
							(isList ? "]" : "}") +",                        </span>")
					}
					frag.appendChild(obs[0])
				} else {
					var val = $("<span>")
						.addClass("value")
						.data("propValue", value)
						.text( can.toJSON(value) )
					
					frag.appendChild(val[0])
					frag.appendChild($('<span class="comma">,</span>')[0])
				}
				return frag;
			},
			".name mouseenter" : function(el, ev){
				if(ev.relatedTarget && ev.relatedTarget.nodeName === "INPUT") {
					return;
				}
				this.newEditInput(el)
				ev.stopImmediatePropagation();
				this.editing = this.getObserveAndName(el);
			},
			"{editOverInput} destroyed" : function(){
				if(this.editing.inserting && !this.dontRemoveTemp){
					this.element.find('.temp').remove();
				}
				this.revert();
			},
			"{editOverInput} keypress" : function(el, ev){
				if(ev.keyCode === 9){
					ev.preventDefault();
					
					if(this.editing.inserting){
						this.editing.name = this.options.editOverInput.val();
						this.dontRemoveTemp = true;
						this.options.editOverInput.remove();
						
						this.editing.naming = $("<span class='naming'> </span>");
						this.editing.inserting.parent().append(this.editing.naming)
						this.newEditInput(this.editing.naming)
						this.dontRemoveTemp = false;
					}
					
				} else if(ev.keyCode === 13){
					if(this.editing.inserting){
						
						if(this.editing.naming && this.options.editOverInput.val()) {
							try{
								var value = can.evalJSON(this.options.editOverInput.val())
								this.editing.observe.attr(this.editing.name, value);
								
								this.commit();
								this.options.editOverInput.remove();
							} catch(e){
								console.log("bad formatted",e)
							}
							return;
						}
						
					}
					this.commit();
					this.options.editOverInput.remove()
				}
			},
			"{editOverInput} keyup" : function(el, ev){
				//submitting

				
				
				if(!this.options.editOverInput.val() || ev.keyCode === 9){
					return;
				}
				

				
				
				if(this.editing.inserting){
					
					
					
					
					// do nothing ...
					if(this.editing.isList){
						
						try{
							var value = can.evalJSON(this.options.editOverInput.val())
							console.log(this.editing.index, value)
							this.editing.observe.attr(this.editing.index, value)
						} catch(e){
							console.log("bad formatted")
						}
						
						
					} else if(!this.editing.naming){
						this.editing.inserting.text(this.options.editOverInput.val());
					} else {
						// make naming right ...
						this.editing.naming.text(this.options.editOverInput.val())
						try{
							var value = can.evalJSON(this.options.editOverInput.val())
							this.editing.observe.attr(this.editing.name, value)
						} catch(e){
							console.log("bad formatted")
						}
					}
					
				
				} else if(this.editing.value){
					
					try{
						var value = can.evalJSON(this.options.editOverInput.val())
						this.editing.observe.attr(this.editing.name, value)
					} catch(e){
						console.log("bad formatted")
					}
				} else {
					var data = this.editing.observe.attr();
					var ordered = {};
					for(var name in data) {
						if(name === this.editing.name){
							ordered[this.options.editOverInput.val()] = data[name]
						} else {
							ordered[name] = data[name]
						}
					}
					this.editing.name = this.options.editOverInput.val();
					this.editing.observe.attr({}, true);
					this.editing.observe.attr(ordered);
					
				}
				
				
			},
			newEditInput : function(el){
				this.options.editOverInput && this.options.editOverInput.remove();
				this.options.editOverInput = $("<input type='text'>");
				new EditOver(this.options.editOverInput,{
					el : el
				})
				this.on();
			},
			".value mouseenter" : function(el, ev){
				if(ev.relatedTarget && ev.relatedTarget.nodeName === "INPUT") {
					return;
				}
				this.newEditInput(el)

				this.editing = this.getObserveAndName( el.prev().children().eq(0) );
				this.editing.value = true;
				
				
				ev.stopImmediatePropagation();
			},
			getObserveAndName : function(el){
				var propParent = el.closest('.propName')
					propName = propParent.data("propName")
				//get the observe
				var observe = propParent.closest(".observe").data("observe")
					|| this.options.obs;
				return {
					observe: observe,
					name: propName,
					oldName: propName
				}
			},
			".remove click": function(el, ev){
				// get the propName
				var propParent = el.closest('.propName')
					propName = propParent.data("propName")
				//get the observe
				var observe = propParent.parent(".observe").data("observe")
					|| this.options.obs;
				
				// remove the property
				observe.removeAttr(propName);
				
				this.options.editOverInput && this.options.editOverInput.remove();
				ev.stopImmediatePropagation();
			},
			".end click" : function(el, ev){
				//this.options.editOverInput && this.options.editOverInput.remove();
				
				
				var obsEl = el.closest(".observe")
					indent = obsEl.data("indent") || 0,
					space = new Array(indent*4).join(" "),
					observe = observe = obsEl.data("observe") || this.options.obs,
					isList = observe instanceof can.Observe.List;
				// get the element before end ... ad an input
				el.prev().after("<span class='temp'>\n"+space+"<span class='insert'> </span>"
					+(isList ? "" : " : ")+"</span>");
					
					
				var insert = el.prev('.temp').find('.insert');
				this.newEditInput(insert);
				

				
				this.editing = {
					observe : observe,
					inserting: insert,
					isList : isList
				};
				if(isList){
					this.editing.index = observe.length;
				}
				
				ev.stopImmediatePropagation();
			},
			"{obs} change" : function(){
				// redraw
				var self = this;
				clearTimeout(this.updateTimer);
				this.updateTimer = setTimeout(function(){
					self.render();
				},1)
				
			},
			"{observe} change" : function(){

				var data = this.options.observe.attr();
				this.options.obs.attr(data, true)
			},
			commit : function(){
				var data = this.options.obs.attr();
				this.options.observe.attr(data, true)
			},
			revert : function(){
				var data = this.options.observe.attr();
				this.options.obs.attr(data, true)
			}
		})
		
		function setEnd(txt) {  
	      if (txt.createTextRange) {  
	       //IE  
	       var fieldRange = txt.createTextRange();  
	       fieldRange.moveStart('character', txt.value.length);  
	       fieldRange.collapse();  
	       fieldRange.select();  
	       }  
	      else {  
	       //Firefox and Opera  
	       txt.focus();  
	       var length = txt.value.length;  
	       txt.setSelectionRange(length, length);  
	      }  
	    }   
		
		// draws an input element over some other element
		EditOver = can.Control({
			init : function(){
				this.element.addClass("edit-over")
				this.element.val(this.options.el.text())
				this.element.appendTo(document.body)
				
				var offset = this.options.el.offset();
				offset.top--;
				offset.left -= 3;
				
				
				this.element.width(this.options.el.width()+6)
				this.element.height(this.options.el.height()+2)
				var css = {
					fontSize : this.options.el.css("fontSize"),
					fontFamily : this.options.el.css("fontFamily"),
					position: "absolute"
				}
				this.element.css(css)
				
				this.element.offset(offset)
				if(this.options.focus){
					this.element[0].focus()
					setEnd(this.element[0])
				} else {
					this.element[0].select();
				}
				
				var self = this;
				setTimeout(function(){
					self.ready = true;
				},10)
				this.measure = $('<span>').css(css).text(this.element.val())
				this.measure.appendTo(document.body)
				this.measure.css({left: "0px", top: "-1000px"})
			},
			"{window} click" : function(el, ev){
				if(this.ready && ev.target !== this.element[0]){
					this.element.remove();
				}
			},
			"keypress" : function(el, ev){
				if(ev.charCode){
					this.measure.text(this.element.val()+"W")
					this.element.width(this.measure.width()+6)
				}
			},
			"keyup" : function(el, ev){
				this.measure.text(this.element.val())
				this.element.width(this.measure.width()+6)
			},
			destroy : function(){
				this.measure.remove();
				can.Control.prototype.destroy.apply(this, arguments)
			}
		})
	
	
})
