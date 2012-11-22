// =========== INTERACTIVE STUFF ===========
// Logic that deals with making steal work with IE.  IE executes scripts out of order, so in order to tell which scripts are
// dependencies of another, steal needs to check which is the currently "interactive" script.
var getInteractiveScript = function() {
		var scripts = h.getElementsByTagName("script"),
			i = scripts.length;
		while ( i-- ) {
			// if script's readyState is interactive it is the one we want
			if ( scripts[i].readyState === "interactive" ) {
				return scripts[i];
			}
		}
	},
	getCachedInteractiveScript = function() {
		if ( interactiveScript && interactiveScript.readyState === 'interactive' ) {
			return interactiveScript;
		}

		if ( interactiveScript = getInteractiveScript() ) {
			return interactiveScript;
		}

		// check last inserted
		if ( lastInserted && lastInserted.readyState == 'interactive' ) {
			return lastInserted;
		}

		return null;
	};


h.support.interactive = h.doc && !! getInteractiveScript();
if ( h.support.interactive ) {
	// after steal is called, check which script is "interactive" (for IE)
	st.after = h.after(st.after, function() {
		// check if disabled by st.loading()
		if (!h.support.interactive ) {
			return;
		}

		var interactive = getCachedInteractiveScript();
		// if no interactive script, this is a steal coming from inside a steal, let complete handle it
		if (!interactive || !interactive.src || /steal\.(production|production\.[a-zA-Z0-9\-\.\_]*)*js/.test(interactive.src) ) {
			return;
		}
		// get the source of the script
		var src = interactive.src;
		// create an array to hold all steal calls for this script
		if (!interactives[src] ) {
			interactives[src] = []
		}

		// add to the list of steals for this script tag
		if ( src ) {
			interactives[src].push.apply(interactives[src], Module.pending);
			Module.pending = [];
		}
	})

	// This is used for packaged scripts.  As the packaged script executes, we grab the
	// dependencies that have come so far and assign them to the loaded script
	st.preexecuted = h.before(st.preexecuted, function( stel ) {
		// check if disabled by st.loading()
		if (!h.support.interactive ) {
			return;
		}

		// get the src name
		var src = stel.options.src,
			// and the src of the current interactive script
			interactiveSrc = getCachedInteractiveScript().src;

		interactives[src] = interactives[interactiveSrc];
		interactives[interactiveSrc] = null;

	})
}