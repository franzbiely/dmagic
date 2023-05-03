<?php

function wpmlm_ewallet_management() {
    $reg_pack = wpmlm_select_all_packages();
  
    ?>
    <div class="panel-border-heading">
        <h4><i class="fa fa-suitcase" aria-hidden="true"></i> <?php _e('E-Wallet Management','wpmlm-unilevel'); ?></h4>
    </div>
    <div id="all-reports">
        <div class="panel-border col-md-12">
            <div id="exTab4">
                <div class="col-md-3">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        <li class="active"><a href="#fund-management" data-toggle="tab" class="fund_management"><?php _e('Fund Management','wpmlm-unilevel'); ?></a></li>
                        <li><a href="#fund-transfer" data-toggle="tab" class="fund-transfer"><?php _e('Fund Transfer','wpmlm-unilevel'); ?></a></li>
                        <li><a href="#transfer-details" data-toggle="tab" class="transfer-details"><?php _e('Transfer Details','wpmlm-unilevel'); ?></a></li>
                        <li><a href="#Level-Commission" data-toggle="tab" class="Level-Commission"><?php _e('Level Commission','wpmlm-unilevel'); ?></a></li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="fund-management">
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Fund Management','wpmlm-unilevel'); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                    <div class="submit_message"></div>
                                    <form id="fund-management-form" class="form-horizontal " method="post">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 user-dt" for="ewallet_user_name"><?php _e('User Name','wpmlm-unilevel'); ?>:</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control fund_input" name="ewallet_user_name" id="ewallet_user_name" placeholder="<?php _e('Enter User Name','wpmlm-unilevel'); ?>" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 user-dt" for="fund_amount"><?php _e('Amount','wpmlm-unilevel'); ?>:</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control fund_input" name="fund_amount" id="fund_amount" placeholder="<?php _e('Enter Amount','wpmlm-unilevel'); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 user-dt" for="transaction_note"><?php _e('Transaction Note','wpmlm-unilevel'); ?>:</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control fund_input" name="transaction_note" id="transaction_note" placeholder="<?php _e('Enter Transaction Note','wpmlm-unilevel'); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group"> 
                                            <div class="col-sm-offset-3 col-lg-6 col-sm-6 col-md-6 col-xs-6 fund-manage-btn">
                                                <?php wp_nonce_field('fund_management_add', 'fund_management_add_nonce'); ?>
                                                <input type="hidden" name="fund_action" class="fund-action" value="">
                                                <button id="fund-management-add" type="submit" class="btn btn-danger fund-management-button" data-title="admin_credit"> <?php _e('Add','wpmlm-unilevel'); ?></button>
                                                <button id="fund-management-deduct" type="submit" class="btn btn-danger fund-management-button" data-title="admin_debit"> <?php _e('Deduct','wpmlm-unilevel'); ?></button>
                                            </div>
                                        </div>
                                    </form>  
                                </div>
                            </div>
                        </div>
                        <!-- Tab 2 Content-->
                        <div class="tab-pane" id="fund-transfer">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Fund Transfer','wpmlm-unilevel'); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                    <div class="submit_message"></div>
                                    <form id="fund-transfer-form" class="form-horizontal " method="post">
                                        <div id="fund-step-1">
                                            <div class="form-group">
                                                <label class="control-label  col-md-4 user-dt"><?php _e('Step 1','wpmlm-unilevel'); ?> :</label><br>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="ewallet_user_name1"><?php _e('User Name','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control fund_transfer_input" name="ewallet_user_name" id="ewallet_user_name1" placeholder="<?php _e('Enter Username','wpmlm-unilevel'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group balance_amount_div" style="display: none">
                                                <label class="control-label col-md-3 user-dt" for="balance_amount"><?php _e('Balance Amount','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <label class="control-label  balance_amount" for="balance_amount" style="float:left;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="ewallet_user_name_to"><?php _e('Transfer To (User Name)','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control fund_transfer_input" name="ewallet_user_name_to" id="ewallet_user_name_to" placeholder="<?php _e('Enter transfer to','wpmlm-unilevel'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="fund_amount"><?php _e('Amount','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control fund_transfer_input" name="fund_transfer_amount" id="fund_transfer_amount" placeholder="<?php _e('Enter Amount','wpmlm-unilevel'); ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="transaction_note"><?php _e('Transaction Note','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control fund_transfer_input" name="transaction_note1" id="transaction_note1" placeholder="<?php _e('Enter Transaction Note','wpmlm-unilevel'); ?>">
                                                </div>
                                            </div>
                                            <div class="form-group"> 
                                                <div class="col-sm-offset-3 col-sm-6 fund-transfer-btn">
                                                    <input type="hidden" name="fund_action" class="fund-action" value="">
                                                    <button id="fund-transfer-continue"  class="btn btn-danger fund-transfer-continue" > <?php _e('Continue','wpmlm-unilevel'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="fund-step-2" style="display: none"> 
                                            <div class="form-group">
                                                <label class="control-label  col-md-4"><?php _e('Step 2','wpmlm-unilevel'); ?></label><br>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4 user-dt" for="ewallet_balance"><?php _e('Ewallet Balance','wpmlm-unilevel'); ?> :</label><label class="control-label col-md-2 ewallet_balance" style="text-align:left;"></label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4 user-dt" for="ewallet_user_name_to"><?php _e('Receiver','wpmlm-unilevel'); ?>:</label><label class="control-label col-md-2 ewallet_user_name_to" style="text-align:left;"></label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4 user-dt" for="amount_to_transfer"><?php _e('Amount to transfer','wpmlm-unilevel'); ?> :</label><label class="control-label col-md-2 amount_to_transfer" style="text-align:left;"></label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4 user-dt" for="transaction_note"><?php _e('Transaction Note','wpmlm-unilevel'); ?> :</label><label class="control-label col-md-8 transaction_note" style="text-align:left;"></label></div>

                                            <div class="form-group">
                                                <label class="control-label col-md-4 user-dt" for="transaction_password"><?php _e('Transaction Password','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="password" class="form-control" name="transaction_password" id="transaction_password" placeholder="<?php _e('Enter Transaction Password','wpmlm-unilevel'); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group"> 
                                                <div class="col-sm-offset-4 col-sm-6">                                                
                                                    <button id="fund-transfer-send" type="submit" class="btn btn-danger fund-transfer-send" > <?php _e('Send','wpmlm-unilevel'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php wp_nonce_field('fund_transfer_add', 'fund_transfer_add_nonce'); ?>   
                                    </form> 
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="transfer-details">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Transfer Details','wpmlm-unilevel'); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                    <form name="transfer-details-form" id="transfer-details-form">
                                        <div id="transfer-date-error"></div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="search1"><?php _e('User Name','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="transfer_input form-control typeahead" name="search1" id="search1" placeholder="<?php _e('search','wpmlm-unilevel'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row"><div class="form-group ewallet-date">
                                                <label class="control-label col-md-3 user-dt" for="start_date1">
                                                    <?php _e('From Date','wpmlm-unilevel'); ?>: <span class="symbol required"></span>
                                                </label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker transfer_input" name="start_date1" id="start_date1" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                        <label for="week_date1" class="input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                                    </div>                       
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row"><div class="form-group ewallet-date" style="margin-top: 0px !important;">
                                                <label class="control-label col-md-3 user-dt" for="end_date1">
                                                    <?php _e('To Date','wpmlm-unilevel'); ?>:<span class="symbol required"></span>
                                                </label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker transfer_input" name="end_date1" id="end_date1" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                        <label for="week_date2" class="input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                                    </div>                        
                                                </div>
                                                
                                            </div>                        
                                        </div>
                                        <div class="row"><div class="form-group"> 
                                                <div class="col-sm-offset-3 col-sm-6 transfer-details-btn"> 
                                                    <?php wp_nonce_field('transfer_details', 'transfer_details_nonce'); ?>
                                                    <button class="btn btn btn-danger" tabindex="5" name="weekdate" type="submit" value="Submit"> <?php _e('Submit','wpmlm-unilevel'); ?></button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="Level-Commission">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span><?php _e('Level Commission','wpmlm-unilevel'); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                    <div class="submit_message"></div>
                                    <form id="Level-Commission-form" class="form-horizontal " method="post">
                                        <div id="fund-step-1">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="user_name"><?php _e('User Name','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control level_commission_input" name="user_name" id="user_name" placeholder="<?php _e('Enter Username','wpmlm-unilevel'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label class="control-label col-md-3 user-dt" for="trade_amount"><?php _e('Trading Amount','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control level_commission_input" name="trade_amount" id="trade_amount" placeholder="<?php _e('Enter Amount','wpmlm-unilevel'); ?>">
                                                </div>
                                            </div> -->
                                            <div class="form-group ">
                                                <label class="control-label col-md-3 user-dt" for="trade_amount"><?php _e('Package','wpmlm-unilevel'); ?>:</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="trade_amount" id="trade_amount">
                                                        <?php 
                                                        foreach ($reg_pack as $reg) { ?>                                                                      
                                                            <option value=<?php echo $reg->id ?>> <?php echo $reg->package_name .'('.$reg->package_price .')' ?></option>
                                                            <?php  } ?>                                                 
                                                    </select> 
                                                </div>                                              
                                            </div>
                                            <div class="form-group"> 
                                                <div class="col-sm-offset-3 col-sm-6 fund-transfer-btn">
                                                    <input type="hidden" name="fund_action" class="fund-action" value="">
                                                    <button id="Level-Commission-submit"  class="btn btn-danger Level-Commission-submit" > <?php _e('Submit','wpmlm-unilevel'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php wp_nonce_field('level_commission_add', 'level_commission_add_nonce'); ?>   
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
        jQuery("#start_date1").datepicker({
            autoclose: true
        });
        jQuery("#end_date1").datepicker({
            autoclose: true
        });
        jQuery(document).ready(function ($) {

            $("#exTab4 li").click(function () {
                $("#tranfer-detail-main-div").hide();
            });
            

            $("#ewallet_user_name,#ewallet_user_name1,#ewallet_user_name_to").change(function () {

                var id = $(this).attr('id');
                $(".err_msg").remove();

                var ewallet_user_name = $(this).val();
                if (id == 'ewallet_user_name1') {

                    $.ajax({
                        type: "post",
                        url: ajaxurl,
                        data: {'ewallet_user_balance': ewallet_user_name,'action':'wpmlm_ajax_ewallet_management'},
                        success: function (data) {
                            if ($.trim(data) != "no-data") {
                                $(".balance_amount_div").show();
                                $(".balance_amount").html(data);
                            } else {
                                $(".balance_amount_div").hide();
                            }

                        }
                    });
                }
            });

            $('.fund-transfer-continue').click(function () {
                isValid = true;
                $(".submit_message").show();
                $(".submit_message").html('');
                var ewallet_user_name1 = $("#ewallet_user_name1").val();
                var ewallet_user_name_to = $("#ewallet_user_name_to").val();

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
                    $(".submit_message").html('<div class="alert alert-danger">Insufficient Balance</div>');
                    setTimeout(function () {
                        $(".submit_message").hide();
                    }, 2000);
                    isValid = false;
                }
                if (($("#ewallet_user_name1").val() != '') && ($("#ewallet_user_name1").val() == $("#ewallet_user_name_to").val())) {
                    $(".submit_message").html('<div class="alert alert-danger">OOPS! Wrong receiver </div>');
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
                $(".fund-action").val(action);
            });

            // Level commission Ajax Function 

            $("#Level-Commission-form").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                isValid = true;
                $(".level_commission_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

                if (isValid) {
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
                                $("#Level-Commission-form")[0].reset();

                            }, 2000);
                        }
                    });
                }
                return false;
            })
            $(".level_commission_input").focus(function () {
                $(this).removeClass("invalid");
            })

            // Fund Management Ajax Function

            
            $("#fund-management-form").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                isValid = true;
                $(".fund_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

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


            // Fund Transfer Ajax Function

            
            $("#fund-transfer-form").submit(function () {
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

                            } else {

                                $(".submit_message").show();
                                $(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#fund-transfer-form")[0].reset();
                                    $("#fund-step-1").show();
                                    $("#fund-step-2").hide();
                                    $(".balance_amount_div").hide();


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
            })



            // Fund Transfer Details Ajax Function

            
            $("#transfer-details-form").submit(function () {

                //$(".submit_message").show();
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
            })

            $(".transfer_input").focus(function () {
                $(this).removeClass("invalid");
            });

        });

    </script>    
    <?php
}