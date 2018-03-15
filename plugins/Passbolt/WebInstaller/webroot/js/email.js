$(function () {
    $('.see-trace').click(function () {
        if ($('.trace').hasClass('hidden')) {
            $('.trace').removeClass('hidden');
        } else {
            $('.trace').addClass('hidden');
        }

        return false;
    });
});