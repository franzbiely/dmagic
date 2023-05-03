<?php

function wpmlm_user_ewallet_management() {
    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);
    $bal_amount_arr = wpmlm_getBalanceAmount($user_id);
    $bal_amount = $bal_amount_arr->balance_amount;
    $result2 = wpmlm_get_general_information();
    ?>
    <div class="panel-border-heading">
        <h4><i class="fa fa-suitcase" aria-hidden="true"></i> <?php _e("E-Wallet Management","wpmlm-unilevel"); ?></h4>
    </div>

    <div id="all-reports">
        <div class="panel-border col-md-12">
            <div  class="col-md-12" id="exTab4">
                <div class="col-md-3 ">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        <li class="active"><a href="#ewallet-details" data-toggle="tab" class="ewallet-details"><?php _e("E-wallet Details","wpmlm-unilevel"); ?></a></li>

                        <li ><a href="#fund-transfer" class="fund-transfer" data-toggle="tab"><?php _e("Fund Transfer","wpmlm-unilevel"); ?></a></li>
                        <li ><a href="#transfer-details" data-toggle="tab" class="transfer-details"><?php _e("Transfer Details","wpmlm-unilevel"); ?></a></li>

                    </ul>
                </div>

                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div class="tab-pane active" id="ewallet-details">

                            <?php echo wpmlm_user_ewallet_details($user_id); ?>

                        </div>
                        <!-- Tab 2 Content-->
                        <div class="tab-pane" id="fund-transfer">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e("Fund Transfer","wpmlm-unilevel"); ?></span></h4>

                                </div>
                                <div class="panel-border">

                                    <div id ="fund-transfer-form-div">
                                        <div class="submit_message"></div>

                                        <form id="fund-transfer-form" class="form-horizontal " method="post">
                                            <div id="fund-step-1">
                                                <div class="form-group">
                                                    <label class="control-label  col-md-4"><?php _e("Step: 1","wpmlm-unilevel"); ?></label><br>
                                                </div>

                                                <div class="form-group balance_amount_div">
                                                    <label class="control-label col-md-3" for="balance_amount"><?php _e("Balance Amount","wpmlm-unilevel"); ?>:</label>
                                                    <div class="col-md-6">
                                                        <label class="control-label" style="float:left;"><?php echo $result2->company_currency; ?>&nbsp; </label> <div class="balance_inner_div"><label class="control-label  balance_amount" for="balance_amount" style="float:left;"><?php echo $bal_amount; ?></label></div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="control-label col-md-3" for="ewallet_user_name_to"><?php _e("Transfer To (User Name)","wpmlm-unilevel"); ?>:</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control fund_transfer_input" name="ewallet_user_name_to" id="ewallet_user_name_to" placeholder="<?php _e('Enter transfer to','wpmlm-unilevel'); ?>">
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="control-label col-md-3" for="fund_amount"><?php _e("Amount","wpmlm-unilevel"); ?>:</label>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control fund_transfer_input" name="fund_transfer_amount" id="fund_transfer_amount" placeholder="<?php _e('Enter Amount','wpmlm-unilevel'); ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3" for="transaction_note"><?php _e("Transaction Note","wpmlm-unilevel"); ?>:</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control fund_transfer_input" name="transaction_note1" id="transaction_note1" placeholder="<?php _e('Enter Transaction Note','wpmlm-unilevel'); ?>">
                                                    </div>


                                                </div>


                                                <div class="form-group">
                                                    .
                                                    <label class="col-sm-offset-3 col-sm-6 control-label"><a class="forgot_trans_pass" style="color:#31afde"><?php _e("Forgot your transaction password?","wpmlm-unilevel"); ?></a></label>

                                                </div>





                                                <div class="form-group"> 
                                                    <div class="col-sm-offset-3 col-sm-6">

                                                        <input type="hidden" name="fund_action" id="fund-action" value="">
                                                        <button id="fund-transfer-continue"  class="btn btn-danger fund-transfer-continue" > <?php _e("Continue","wpmlm-unilevel"); ?></button>

                                                    </div>
                                                </div>
                                            </div>

                                            <div id="fund-step-2" style="display: none"> 
                                                <div class="form-group">
                                                    <label class="control-label  col-md-4"><?php _e("Step: 2","wpmlm-unilevel"); ?></label><br>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="ewallet_balance"><?php _e("Ewallet Balance","wpmlm-unilevel"); ?> :</label><label class="control-label" style="float:left;"><?php echo $result2->company_currency; ?>&nbsp; </label><label class="control-label ewallet_balance" style="float:left;"></label></div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="ewallet_user_name_to"><?php _e("Receiver","wpmlm-unilevel"); ?>:</label><label class="control-label ewallet_user_name_to" style="float:left;"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="amount_to_transfer"><?php _e("Amount to transfer","wpmlm-unilevel"); ?> :</label><label class="control-label" style="float:left;"><?php echo $result2->company_currency; ?>&nbsp; </label><label class="control-label amount_to_transfer" style="float:left;"></label></div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="transaction_note"><?php _e("Transaction Note","wpmlm-unilevel"); ?> :</label><label class="control-label transaction_note" style="float:left;"></label></div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="transaction_password"><?php _e("Transaction Password","wpmlm-unilevel"); ?>:</label>
                                                    <div class="col-md-6">
                                                        <input type="password" class="form-control" name="transaction_password" id="transaction_password" placeholder="<?php _e('Enter Transaction Password','wpmlm-unilevel'); ?>">
                                                    </div>
                                                </div>





                                                <div class="form-group"> 
                                                    <div class="col-sm-offset-4 col-sm-6">                                                
                                                        <input type="hidden" name="ewallet_user_id" value="<?php echo $user_id; ?>">
                                                        <input type="hidden" name="ewallet_user_name" value="<?php echo $user->user_login; ?>">



                                                        <button id="fund-transfer-send" type="submit" class="btn btn-danger fund-transfer-send" > <?php _e("Send","wpmlm-unilevel"); ?></button>

                                                    </div>
                                                </div>

                                            </div>

                                            <?php wp_nonce_field('fund_transfer_add', 'fund_transfer_add_nonce'); ?>   
                                        </form>


                                    </div>


                                    <div id="forgot-trans-pass-div" style="display:none">
                                        <div class="submit_message"></div>
                                        <p style="color: #31afde"><?php _e("Note : Transaction password will be sent to your registered Email id","wpmlm-unilevel"); ?></p>
                                        <form id="forgot-tran-pass-form" class="form-horizontal " method="post">
                                            <!-- <div class="form-group">
                                                <label class="control-label col-md-3" for="forgot_tran_user_name">User Name:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="forgot_tran_user_name" id="forgot_tran_user_name">
                                                </div>
                                            </div> -->

                                            <div class="form-group"> 
                                                <div class="col-sm-offset-3 col-sm-6">
                                                    <?php wp_nonce_field('forgot_tran_pass', 'forgot_tran_pass_nonce'); ?>

                                                    <button id="forgot-tran-pass-button" type="submit" class="btn btn-danger forgot-tran-pass-button" > <?php _e("Send Password","wpmlm-unilevel"); ?></button>

                                                </div>
                                            </div>
                                        </form>  

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="transfer-details">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e("Transfer Details","wpmlm-unilevel"); ?></span></h4>

                                </div>

                                <div class="panel-border">
                                    <form name="transfer-details-form" id="transfer-details-form" >
                                        <div id="transfer-date-error"></div>
                                        <div class="row">
                                            <div class="form-group ewallet-date">
                                                <label class="col-md-3 user-dt control-label" for="start_date1">
                                                    <?php _e("From Date","wpmlm-unilevel"); ?>: <span class="symbol required"></span>
                                                </label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker transfer_input" name="start_date1" id="start_date1" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                        <label for="week_date1" class="input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                                    </div>                        </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group ewallet-date">
                                                <label class="col-md-3 user-dt control-label" for="end_date1">
                                                    <?php _e("To Date","wpmlm-unilevel"); ?>:<span class="symbol required"></span>
                                                </label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker transfer_input" name="end_date1" id="end_date1" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                        <label for="week_date2" class="input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                                    </div>                        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group" style="margin-top: 10px;"> 
                                                <div class="col-sm-offset-3 col-sm-6">
                                                    <input type="hidden" name="transer_user_id" value="<?php echo $user_id; ?>">

                                                    <?php wp_nonce_field('transfer_details', 'transfer_details_nonce'); ?>
                                                    <button class="btn btn btn-danger" tabindex="5" name="weekdate" type="submit" value="Submit"> <?php _e("Submit","wpmlm-unilevel"); ?></button>

                                                </div>
                                            </div>
                                        </div>
                                    </form>



                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- Transfer Details Ajax Data Start-->

                <div class="clearfix"></div>

                <div class="row" style="margin-top:20px;display:none;" id="tranfer-detail-main-div">

                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4><i class="fa fa-external-link-square"></i> <span class="tranfer-detail-caption"></span></h4>

                            </div>
                            <div class="no-data"></div>
                            <div  id="profile_print_area" style="overflow: auto; padding: 10px;" class="transfer-details-data" >


                            </div>

                        </div>
                    </div>
                </div>

                <!-- Transfer Details Ajax Data End-->


            </div>

        </div>
    </div>

    <script>
jQuery(document).ready(function ($) {
        $("#start_date1").datepicker({
            autoclose: true
        });
        $("#end_date1").datepicker({
            autoclose: true
        });
    });

        jQuery(document).ready(function ($) {
            $(document).on('click', '.forgot_trans_pass', function () {
                $("#fund-transfer-form-div").hide();
                $("#forgot-trans-pass-div").show();

            });

            $(".fund-transfer").click(function () {
                $("#fund-transfer-form-div").show();
                $("#forgot-trans-pass-div").hide();
            });


            // Send transaction password
            


            $("#forgot-tran-pass-form").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_transaction_password');
            

                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if ($.trim(data) === "1") {
                                $(".submit_message").html('<div class="alert alert-info">Transaction Password Sent Successfully</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#forgot-tran-pass-form")[0].reset();

                                }, 3000);

                            } else {

                                $(".submit_message").html('<div class="alert alert-danger">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $(".submit_message").html('');

                                }, 3000);
                            }

                        }
                    });
                
                return false;
            });

            $("#exTab4 li").click(function () {
                $("#tranfer-detail-main-div").hide();
            });

            $("#ewallet_user_name,#ewallet_user_name1,#ewallet_user_name_to").blur(function () {

                var id = $(this).attr('id');

                $(".err_msg").remove();

                var ewallet_user_name = $(this).val();
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {action:'wpmlm_ajax_ewallet_management',ewallet_user_name: ewallet_user_name},
                    beforeSend: function () {



                        $("#" + id).parent().append('<div class="err_msg"><img src=' + plugin_url + '/images/loader.gif></div>');
                    },
                    success: function (data) {
                        //alert(data);

                        $(".err_msg").remove();
                        if ($.trim(data) != "1") {
                            $("#" + id).parent().append('<div class="err_msg">' + data + '</div>');
                        }

                        if (id == 'ewallet_user_name1') {

                            $.ajax({
                                type: "post",
                                url: ajaxurl,
                                data: {action:'wpmlm_ajax_ewallet_management',ewallet_user_balance: ewallet_user_name},
                                success: function (data) {

                                    if ($.trim(data) != "no-data") {

                                        $(".balance_amount_div").show();
                                        $(".balance_amount").html(data);
                                    }

                                }
                            });
                        }
                    }

                });
            });

            $('.fund-transfer-continue').click(function () {
                isValid = true;
                $(".fund_transfer_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

                var amount = parseInt($("#fund_transfer_amount").val());
                var bal_amount = parseInt($(".balance_amount").html());
                if (bal_amount < amount) {
                    $("#fund_transfer_amount").addClass("invalid");
                    isValid = false;
                }

                if (bal_amount == 0) {
                    $(".submit_message").html('<div class="alert alert-info">Insufficient Balance</div>');
                    setTimeout(function () {
                        $(".submit_message").hide();
                    }, 2000);
                    isValid = false;
                }


                if ($("#ewallet_user_name1").val() == $("#ewallet_user_name_to").val()) {
                    $(".submit_message").html('<div class="alert alert-info">OOPS! Wrong receiver </div>');
                    setTimeout(function () {
                        $(".submit_message").hide();
                    }, 2000);
                    isValid = false;
                }



                if (isValid) {
                    $("#fund-step-1").hide();
                    $("#fund-step-2").show();

                    $('.ewallet_balance').html($(".balance_amount").html());
                    $('.ewallet_user_name_to').html($("#ewallet_user_name_to").val());
                    $('.amount_to_transfer').html($("#fund_transfer_amount").val());
                    $('.transaction_note').html($("#transaction_note1").val());

                }
                return false;
            });


            $(".fund_transfer_input").focus(function () {
                $(this).removeClass("invalid");
            })

            $('.fund-management-button').click(function () {
                var action = $(this).attr("data-title");
                $("#fund-action").val(action);
            });


            
            $("#fund-management-form").submit(function () {
                $(".submit_message").show();
                var formData = new FormData(this);
                isValid = true;
                $(".fund_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                if (isValid) {
                    $('#fund-management-add').prop('disabled', true);
                    $('#fund-management-deduct').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {

                            $(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $(".submit_message").hide();
                                $("#fund-management-form")[0].reset();
                                $('#fund-management-add').prop('disabled', false);
                                $('#fund-management-deduct').prop('disabled', false);

                            }, 2000);
                        }
                    });
                }
                return false;
            })
            $(".fund_input").focus(function () {
                $(this).removeClass("invalid");
            })


            $("#fund-transfer-form").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                isValid = true;


                if ($("#transaction_password").val() == '') {
                    $("#transaction_password").addClass("invalid");
                    isValid = false;
                }
                if (isValid) {
                    $('#fund-transfer-send').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if ($.trim(data) === "0") {
                                $(".submit_message").html('<div class="alert alert-danger">Incorrect Transaction Password</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();

                                }, 2000);

                            } else if ($.trim(data) === "1") {

                                $(".submit_message").html('<div class="alert alert-danger">OOPS! Wrong receiver.</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();

                                }, 2000);

                            } else {

                                $(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#fund-transfer-form")[0].reset();
                                    $("#fund-step-1").show();
                                    $("#fund-step-2").hide();

                                    $(".balance_inner_div").load(location.href + " .balance_amount");

                                }, 2000);
                            }
                            $('#fund-transfer-send').prop('disabled', false);
                        }
                    });
                }
                return false;
            })

            $("#transaction_password").focus(function () {
                $(this).removeClass("invalid");
            });


            // Fund Transfer Details Ajax Function

            
            $("#transfer-details-form").submit(function () {

                $(".submit_message").show();
                $("#transfer-date-error").html('');
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                isValid = true;

                $(".transfer_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

                var startDate = new Date($('#start_date1').val());
                var endDate = new Date($('#end_date1').val());

                if (startDate > endDate) {
                    $("#transfer-date-error").html('<p style="color:red">You must select an end date greater than start date</p>');
                    $("#tranfer-detail-main-div").hide();
                    return false;
                }

                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {

                            $("#tranfer-detail-main-div").show();
                            $(".tranfer-detail-caption").html('Transfer Details');
                            $(".transfer-details-data").html(data);
                            $("#transfer-details-form")[0].reset();

                        }
                    });
                }
                return false;
            });

            $(".transfer_input").focus(function () {
                $(this).removeClass("invalid");
            });

        });

    </script>
    <?php
}
