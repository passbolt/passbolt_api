import $ from "jquery";
import mad from "mad/mad";
import Confirm from "mad/component/confirm";

$(function() {
    $('#show-confirm-dialog').click(function() {
        var confirm = new mad.component.Confirm(
            null, {
                label: 'Do you want to delete the password ?',
                content: 'This is a content test',
                action:function() {
                    alert('action is performed');
                }
            }).start();
        return false;
    });
});