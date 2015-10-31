var url = url.substring(0, url.indexOf('#'));
var demos = [
    'control'
];
for (var i in demos) {
    $('#demo-html').append('<a href="' + url + '#' + demos[i] + '" target="_self">' + demos[i] + '</a>')
}
$('a').on('click', function(){
    location.href = $(this).attr('href');
    location.reload();
});