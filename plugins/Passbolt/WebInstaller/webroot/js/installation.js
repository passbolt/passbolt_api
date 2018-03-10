$(function () {
    var details = [
        'Installing database',
        'Validating GPG keys',
        'Setting up keys',
        'Collecting fairy dust',
        'Setting up SMTP',
        'Locating Elon Musk\'s car. Don\'t panic.',
        'Checking options',
        'Writing configuration file',
        'Brewing pale ale',
        'Checking status'
    ];
    var i = 0;
    var installed = 0;

    /**
     * Display status.
     */
    function displayStatus()
    {
        if (details[i] !== undefined) {
            $('.install-details').text(details[i]);
            setTimeout(function () {
                displayStatus() }, 1000);
            i++;
        } else {
            checkInstalled();
        }
    }

    /**
     * Check if passbolt is installed.
     * As long as it is not, keep looping.
     */
    function checkInstalled()
    {
        if (installed == 0) {
            setTimeout(function () {
                checkInstalled() }, 200);

            return;
        }
        document.location.href = $('#redirect-url').val();
    }

    /**
     * Install passbolt.
     */
    function install()
    {
        $.ajax(
            $('#install-url').val(),
            {
                success: function (data, status, xhr) {
                    installed = data;
                }
            }
        )
    }

    install();
    displayStatus();
});