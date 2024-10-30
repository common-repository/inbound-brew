<?php
$ajaxurl = admin_url('admin-ajax.php');
?>
<script type='text/javascript'>
    jQuery(document).ready(function ($) {
        //Check CTA Click activity
        $('a').on('click', function (e) {
            var click_id = $(this).attr('id');
            var ahref = $(this).attr("href");
            if ($(this).data('role') == 'cta-button') {
                var cta_id = click_id.split("_");
                e.preventDefault();
                if (cta_id[2] > 0) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: <?php echo "'" . $ajaxurl . "'"; ?>,
                        data: {
                            action: 'cta_click_event',
                            current_page: <?php echo $_SESSION['current_page_id']; ?>,
                            cta_id: cta_id[2],
                            lead_id: getCookie('lead_id')
                        },
                        success: function (data) {
                            window.location.href = ahref;
                        },
                        error: function (data) {
                            window.location.href = ahref;
                        }
                    });
                }
            }
        });
        //Check For visit Activity
        var counter = 0;
        var interval = setInterval(function () {
            counter++;
            if (counter == 5) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: <?php echo "'" . $ajaxurl . "'"; ?>,
                    data: {
                        action: 'page_visit_event',
                        current_page: <?php echo $_SESSION['current_page_id']; ?>,
                        lead_id: getCookie('lead_id')
                    },
                    success: function (data) {
                    },
                    error: function (data) {

                    }
                });
                clearInterval(interval);
            }
        }, 1000);

        /**
         * Get cookies
         * @param string cname
         */
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    });
</script>