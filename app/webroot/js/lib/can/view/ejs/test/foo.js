/*global list,animals*/
var ___v1ew = [];
___v1ew.push('<div>my<b>favorite</b>animals:');
___v1ew.push(can.view.txt(0, 'div', 0, this, function () {
	var ___v1ew = [];
	list(animals, function (animal) {
		___v1ew.push('<label>Animal=</label> <span>');
		___v1ew.push(can.view.txt(1, 'span', 0, this, function () {
			return animal;
		}));
		___v1ew.push('</span>');
	});
	return ___v1ew.join('');
}));
___v1ew.push('!</div>');
