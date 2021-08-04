jQuery(document).ready(function($) {
    'use strict';
    $('#my-lovely-users-list a').on('click', function(e) {
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
                console.log(jqXhr);
                console.log(textStatus);
                console.log(errorMessage);
                $("#status").html('<div class="msg-danger">There was an error while fetching the data, please try again</div>');

            }
        });
    })

});