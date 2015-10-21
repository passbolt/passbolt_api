steal('can/view/mustache', 'can/test/benchmarks.js', function (can, benchmarks) {
	/* jshint ignore:start */
	benchmarks.add(
		"can/view/mustache Updating elements",
		function () {

			var template = can.view.mustache(
				"{{#each boxes}}" +
				"<div class='box-view'>" +
				"<div class='box' id='box-{{number}}'  style='top: {{top}}px; left: {{left}}px; background: rgb(0,0,{{color}});'>" +
				"{{content}}" +
				"</div>" +
				"</div>" +
				"{{/each}}");

			var boxes = [],
				Box = can.Map.extend({
					count: 0,
					content: 0,
					tick: function () {
						var count = this.attr("count") + 1;
						this.attr({
							count: count,
							left: Math.cos(count / 10) * 10,
							top: Math.sin(count / 10) * 10,
							color: count % 255,
							content: count
						});
					}
				});

			for (var i = 0; i < 100; i++) {
				boxes.push(new Box({
					number: i
				}));
			}

			var frag = template({
				boxes: boxes
			});
			var div = document.createElement("div");
			document.body.appendChild(div);
			div.appendChild(frag);
		},
		function () {
			for (var j = 0; j < 2; j++) {
				for (var n = 0; n < boxes.length; n++) {
					boxes[n].tick();
				}
			}
		},
		function () {
			document.body.removeChild(div);
		});
	/* jshint ignore:end */
});
