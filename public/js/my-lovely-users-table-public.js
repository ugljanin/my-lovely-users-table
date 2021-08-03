(function($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $('a').on('click', function(e) {
        e.preventDefault();
        var userid = $(this).parent().parent().data('id');
        $.ajax({
            type: "POST",
            url: the_ajax_script.ajaxurl,
            data: {
                action: "load_user_details_ajax",
                user_details: userid,
                nonce: the_ajax_script.nonce
            },
            success: function(result, status, xhr) { // success callback function				
                $("#status").removeClass("msg-success msg-danger").hide();
                if (result.type == "success") {
                    $("#user-id").html(result.content.id);
                    $("#user-name").html(result.content.name);
                    $("#user-email").html(result.content.email);
                    $("#user-username").html(result.content.username);
                    $("#user-city").html(result.content.city);
                    $("#user-suite").html(result.content.suite);
                    $("#user-street").html(result.content.street);
                    $("#user-company").html(result.content.name);
                    $("#user-phone").html(result.content.phone);
                    $("#my-lovely-user-details").addClass("msg-success").removeClass("msg-danger").fadeIn();

                } else if (result.type == "danger") {
                    $("#status").removeClass("msg-success").addClass("msg-danger").html(result.content).fadeIn();
                }
            },
            beforeSend: function(data, status, xhr) { // success callback function
                $("#my-lovely-user-details").removeClass("msg-success msg-danger").hide();
                $("#status").html('<div class="lds-dual-ring"></div>').fadeIn();
            },
            error: function(jqXhr, textStatus, errorMessage) { // error callback 
                console.log("error");
                $("#status").html('<div class="msg-danger">There was an error while fetching the data, please try again</div>');

            }
        });
    })

})(jQuery);