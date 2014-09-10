jQuery(function ($) {

    $('.profile_item_select').on('click', function () {
        $('#server-response').html('<h3>Processing Backup.  You can leave this page and the backup will continue.</h3>');

        var profile = $(this).data('profile');
        console.log(profile);
        var data = {
            'action': 'cd_backup_buddy',
            'profile': profile
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data, function (response) {
            $('#server-response').html('<h3>' + response + '</h3>');
        });
    });
});