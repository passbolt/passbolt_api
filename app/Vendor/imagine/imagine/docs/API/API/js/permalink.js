jQuery(document).ready(
    function () {
        var hash = window.location.hash.replace(/^#/, '');

        if (hash) {
            jQuery('#main-frame').attr('src', hash);

            if (history.pushState) {
                history.pushState(
                    '',
                    document.title,
                    window.location.pathname + window.location.search
                );
            }
        }
    }
);
