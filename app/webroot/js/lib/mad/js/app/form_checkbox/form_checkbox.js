import $ from "jquery";
import mad from "mad/mad";
import Checkbox from "mad/form/element/checkbox";

var checkbox = new mad.form.Checkbox($('#checkbox'), {
    availableValues: {
        1: 'Option 1',
        2: 'Option 2',
        3: 'Option 3'
    }
});
checkbox.start();

$('#checkbox').on('changed', function() {
    $('li.count .value').html(checkbox.getValue().length);
    $('li.values .value').html(checkbox.getValue().join(', '));
});
