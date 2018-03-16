$(function () {
    if ($('#redirect-url').val() != '') {
        setTimeout(function () {
            document.location.href = $('#redirect-url').val();
        }, 5000);
    }
});