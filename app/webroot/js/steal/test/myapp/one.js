steal("./two.js").then("./three.js").then(
		function() {
			TWO.digit = 2;
			THREE.digit = 3;
			document.body.appendChild(document.createTextNode(JSON
					.stringify(TWO)
					+ JSON.stringify(THREE)
					+ JSON.stringify(COMMON)
					+ "production.js loading test - PASS"));
		});