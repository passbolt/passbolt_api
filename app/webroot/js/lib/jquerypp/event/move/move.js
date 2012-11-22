steal('jquery', 'jquery/event/reverse', function($) {
	$.event.reverse('move');
	return $;
});