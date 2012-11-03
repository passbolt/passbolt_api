@page jQuery.event.swipe
@parent jquerypp

`jQuery.event.swipe` provides cross browser `swipeleft`, `swiperight`, `swipeup`, `swipedown` and a general `swipe` event.
On mobile devices, swipe uses touch events. On desktop browsers, swipe uses mouseevents.

A swipe happens when the pointer travels between [jQuery.event.swipe.min] and [jQuery.event.swipe.max] pixels within [jQuery.event.swipe.delay] milliseconds in any of the four directions.

	$('#swiper').on({
	  'swipe' : function(ev) {
	    console.log('Swiping')
	  },
	  'swipeleft' : function(ev) {
	    console.log('Swiping left')
	  },
	  'swiperight' : function(ev) {
	    console.log('Swiping right')
	  },
	  'swipeup' : function(ev) {
	    console.log('Swiping up')
	  },
	  'swipedown' : function(ev) {
	    console.log('Swiping down')
	  }
	})
