jQuery("#dob").datepicker({
    endDate: '-18y',
    autoclose: true
});

var plugin_url = path.pluginsUrl;


jQuery( document ).ready( function( $ ) {
    
    $("#sname").change(function () {
        $(".err_msg_sponsor").remove();

        var sname = $(this).val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {action:'wpmlm_ajax_user_check',sponsor: sname},
            beforeSend: function () {
                $("#sname").parent().append('<div class="err_msg_sponsor"><img src=' + plugin_url + '/images/loader.gif></div>');
            },
            success: function (data) {
                
                $(".err_msg_sponsor").remove();
                if ($.trim(data) === "1") {
                    $("#sname").removeClass('invalid');
                    $("#sname").removeClass('error');
                    $("#sname").addClass('valid');
                    $("#sname").attr('aria-invalid', 'false');
                    $("#reg_submit").attr('disabled', false);
                    $("#reg_submit").css('opacity','1');
                    $("#reg_submit").css('cursor','pointer');

                } else {
                    $("#sname").parent().append('<div class="err_msg_sponsor">' + data + '</div>');
                    $("#sname").addClass('invalid');
                    $("#sname").removeClass('valid');
                    $("#sname").addClass('error');
                    $("#sname").attr('aria-invalid', 'true');
                    $("#reg_submit").attr('disabled', true);
                    $("#reg_submit").css('opacity','0.5');
                    $("#reg_submit").css('cursor','not-allowed');
                }   

            }

        });
    });
    $("#username").change(function () {
        $(".err_msg_username").remove();

        var username = $(this).val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {action:'wpmlm_ajax_user_check',username: username},
            beforeSend: function () {
                $("#username").parent().append('<div class="err_msg_username"><img src=' + plugin_url + '/images/loader.gif></div>');
            },
            success: function (data) {
                $(".err_msg_username").remove();
                if ($.trim(data) === "1") {
                    $("#username").removeClass('invalid');
                    $("#username").removeClass('error');
                    $("#username").addClass('valid');
                    $("#username").attr('aria-invalid', 'false');
                    $("#reg_submit").attr('disabled', false);
                    $("#reg_submit").css('opacity','1');
                    $("#reg_submit").css('cursor','pointer');

                } else {

                    $("#username").parent().append('<div class="err_msg_username">' + data + '</div>');
                    $("#username").addClass('invalid');
                    $("#username").removeClass('valid');
                    $("#username").addClass('error');
                    $("#username").attr('aria-invalid', 'true');
                    $("#reg_submit").attr('disabled', true);
                    $("#reg_submit").css('opacity','0.5');
                    $("#reg_submit").css('cursor','not-allowed');
                }

            }

        });
    });
    
    $("#email").change(function () {
        $(".err_msg_email").remove();

        var email = $(this).val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {action:'wpmlm_ajax_user_check',email: email},
            beforeSend: function () {
                $("#email").parent().append('<div class="err_msg_email"><img src=' + plugin_url + '/images/loader.gif></div>');
            },
            success: function (data) {
                $(".err_msg_email").remove();
                if ($.trim(data) === "1") {
                    $("#email").removeClass('invalid');
                    $("#email").removeClass('error');
                    $("#email").addClass('valid');
                    $("#email").attr('aria-invalid', 'false');
                    $("#reg_submit").attr('disabled', false);
                    $("#reg_submit").css('opacity','1');
                    $("#reg_submit").css('cursor','pointer');

                } else {
                    $("#email").parent().append('<div class="err_msg_email">' + data + '</div>');
                    $("#email").addClass('invalid');
                    $("#email").removeClass('valid');
                    $("#email").addClass('error');
                    $("#email").attr('aria-invalid', 'true');
                    $("#reg_submit").attr('disabled', true);
                    $("#reg_submit").css('opacity','0.5');
                    $("#reg_submit").css('cursor','not-allowed');
                }

            }

        });
    });

    // $("#wpmlm-registration-form").submit(function () {
           
    //        // $(".submit_message1").html('');
    //        // $(".submit_message1").show();
           
    //        var formData = new FormData(this);
    //        formData.append('action', 'wpmlm_registration_page');
    //        isValid = true;
   
    //        // $(".commission_input").each(function () {
    //        //     var element = $(this);
    //        //     if ((element.val() == '')|| (element.val() < 0)) {
    //        //         $(this).addClass("invalid");
    //        //         isValid = false;
    //        //     }
    //        // });
           
           
   
    //        if (isValid) {
   
    //            $.ajax({
    //                type: "POST",
    //                url: ajaxurl,
    //                data: formData,
    //                cache: false,
    //                contentType: false,
    //                processData: false,
    //                success: function (data) {
    //                     alert('hi');
    //                    $(".submit_message1").html('<div class="alert alert-info">' + data + '</div>');
    //                    setTimeout(function () {
    //                        $(".submit_message1").hide();
   
    //                    }, 1000);
   
    //                }
    //            });
   
    //        }
    //        return false;
   
    //    });
});


// $("#wpmlm-registration-form").submit(function () {
//     $(".main_input").each(function () {
//         var element = $(this);
//         if (element.hasClass( "invalid" )) {
//             $("#reg_submit").attr('disabled', true);
//             $("#reg_submit").css('opacity','0.5');
//             $("#reg_submit").css('cursor','not-allowed');
//             alert('Sorry! Registration Failed, Please check the values & try again');
//             return false;
//         } else {
//             $("#reg_submit").attr('disabled', false);
//             $("#reg_submit").css('opacity','1');
//             $("#reg_submit").css('cursor','pointer');
//         }
//     });
// });

$(".response_message").html('');

$.validator.addMethod("loginRegexPopup", function(value, element) {
    return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
}, "Username must contain only letters, numbers, or dashes.");

$("#wpmlm-registration-form").validate({
    rules: {
        sname: {
            required: true,
            loginRegexPopup: true,
            minlength:4,
            maxlength: 50
        },
        username: {
            required: true,
            loginRegexPopup: true,
            minlength:4,
            maxlength: 50
        },
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength:6,
            maxlength: 15
        }
    },
    messages: {
        sname: {
            required: "Username is required",
            loginRegexPopup: "Letters and Numbers only",
            minlength: "Minimum 4 characters required!",
            maxlength: "Maximum 50 characters allowed"
        },
        username: {
            required: "Username is required",
            loginRegexPopup: "Letters and Numbers only",
            minlength: "Minimum 4 characters required!",
            maxlength: "Maximum 50 characters allowed"
        },
        email: {
            required: "Email is required",
            email: "Inavlid email address"
        },
        password: {
            required: "Password is required",
            minlength: "Minimum 6 characters required!",
            maxlength: "Maximum 15 characters allowed"
        }
    },
    submitHandler: function (form) { 
        // $(".response_message").html('');
        // var formData = new FormData(form);

        // // var userRegistrationType  = formData.get('user_registration_type');

        // formData.append('action', 'wpmlm_contact_form_registration');

        // $.ajax({
        //     type: 'POST',
        //     url: ajaxurl,
        //     data: formData,
        //     dataType: 'JSON',
        //     cache: false,
        //     contentType: false,
        //     processData: false,
        //     success: function(data) {
        //         console.log(data);
        //         if(data.msg === 1){
        //             $(".response_message").html("<div class='alert alert-success'><strong>Registration Completed Successfully</strong></div>");
        //             setTimeout(function () {
        //                 window.location.href = data.activation_link;
        //             }, 5000);
        //         }else{
        //             $(".response_message").html("<div class='alert alert-danger'><strong>Sorry! Registration Failed, Please try again</strong></div>");
        //         }
        //     },
        //     error: function(xhr, error){
        //         console.debug(xhr); console.debug(error);
        //     },
        // });
        return true;
    }
});


