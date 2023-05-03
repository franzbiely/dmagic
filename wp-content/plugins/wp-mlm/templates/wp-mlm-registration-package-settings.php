<?php

function wpmlm_package_settings() {
    $result = wpmlm_get_general_information();

    if ($result->registration_type == 'with_out_package') {
        $checked = 'checked';
        $div_style = 'display:none';
        $reg_form_style = 'display:block';
    } else {
        $checked = '';
        $div_style = 'display:block';
        $reg_form_style = 'display:none';
    }
    ?>
    <div class="panel panel-default">

        <div class="panel-heading">
            <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Package Settings','wpmlm-unilevel'); ?></span></h4>

        </div>
        <div class="panel-border">
            <div>
                <input type="checkbox" <?php echo $checked; ?> class="form-control" name="reg_with_out_package" id="reg_with_out_package" ><label class="control-label reg_with_out_pkg_label" for="reg_with_out_package"><?php _e('Registration without using packages','wpmlm-unilevel'); ?></label>


                <div class="amt_submit_message"></div>
               <!-- <form id="reg-amt-form" class="form-horizontal " method="post" style="<?php echo $reg_form_style; ?>">
                    <div class="form-group">
                        <label class="control-label col-md-3 user-dt" for="reg_amt"><?php _e('Registration Amount','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control reg_amt" name="reg_amt" id="reg_amt" placeholder="<?php _e('Enter registration amount','wpmlm-unilevel'); ?>" value="<?php echo $result->registration_amt; ?>">
                        </div>
                    </div>

                    <div class="form-group"> 
                        <div class="col-sm-offset-3 col-sm-6">
                            <?php wp_nonce_field('reg_amt_add', 'reg_amt_add_nonce'); ?>
                            <button id="reg-amt-save" type="submit" class="btn btn-danger"> <?php _e('Save','wpmlm-unilevel'); ?></button>
                        </div>
                    </div>
                </form>-->

            </div>

            <button type="button" class="btn btn-danger btn-sm package-settings" style="margin-top: 10px;margin-bottom: 10px;<?php echo $div_style; ?>" ><?php _e('Add New Package','wpmlm-unilevel'); ?></button>

            <div class="col-md-12 please-wait" style="text-align: center; display: none"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/please-wait.gif'; ?>"></div>
        <div id="package-settings" class="panel-border" style="display:none;">  

            <div class="submit_message"></div>
            <form id="package-form" class="form-horizontal " method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-md-3 user-dt" for="package_name"><?php _e('Package Name','wpmlm-unilevel'); ?>:</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control package_input" name="package_name" id="package_name" placeholder="<?php _e('Enter Package Name','wpmlm-unilevel'); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 user-dt" for="package_price"><?php _e('Package Price','wpmlm-unilevel'); ?>:</label>
                    <div class="col-md-6">
                        <input type="number" class="form-control package_input" name="package_price" id="package_price" placeholder="<?php _e('Enter Package Price','wpmlm-unilevel'); ?>" step="0.01" min="0.01" >
                    </div>
                </div>

                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-6">
                        <input type="hidden" name="submit-action" value="" id="submit-action">
                        <input type="hidden" name="package_id" value="" id="package_id">                                <?php wp_nonce_field('package_add', 'package_add_nonce'); ?>
                        <button id="package-save" type="submit" class="btn btn-danger"> <?php _e('Save','wpmlm-unilevel'); ?></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="submit_message1"></div>

        <div  id="package-div" style="<?php echo $div_style; ?>"> 

                <table id="package-table" class="table table-striped table-responsive-lg table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php _e('Sl.No','wpmlm-unilevel'); ?></th>
                            <th><?php _e('Package Name','wpmlm-unilevel'); ?></th>
                            <th><?php _e('Price','wpmlm-unilevel'); ?></th>
                            <th><?php _e('Action','wpmlm-unilevel'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $results = wpmlm_select_all_packages();
                        $p_count = 0;
                        foreach ($results as $res) {

                            $p_count++;
                            echo '<tr>
            <th scope="row">' . $p_count . '</th>
            <td>' . $res->package_name . '</td>
            <td>' . $result->company_currency . $res->package_price . '</td>
            
            <td class="package_edit_td">
                <button type="button" class="btn btn-default btn-sm package_edit" edit-id="' . $res->id . '">'.__("Edit","wpmlm-unilevel").'</button>
                <button type="button" class="btn btn-default btn-sm package_delete" delete-id="' . $res->id . '">'.__("Delete","wpmlm-unilevel").'</button>
            </td>
        </tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>

        jQuery(document).ready(function ($) {


            $('#package-table').DataTable({
                "pageLength": 10,
                "bFilter": false
            });

            $(document).on('submit', '#reg-amt-form', function () {
                $(".amt_submit_message").show();
                $(".amt_submit_message").html('');

                isValid = true;
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_package_settings');

                if (($("#reg_amt").val() == '')|| ($("#reg_amt").val()<0)) {
                    $("#reg_amt").addClass("invalid");
                    isValid = false;
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
                            //alert(data);
                            if ($.trim(data) == '1') {
                                $(".amt_submit_message").html('<div class="alert alert-info"><?php _e("Amount Updated Successfully","wpmlm-unilevel");?></div>');
                                setTimeout(function () {
                                    $(".amt_submit_message").hide();
                                }, 1000);

                            }


                            if ($.trim(data) == '2') {
                                $(".amt_submit_message").html('<div class="alert alert-info"><?php _e("Already Updated","wpmlm-unilevel");?></div>');
                                setTimeout(function () {
                                    $(".amt_submit_message").hide();
                                }, 1000);

                            }

                        }
                    });
                }
                return false;
            });


            $(document).on('submit', '#package-form', function () {
                $(".submit_message").show();
                $(".submit_message").html('');
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_package_settings');
                isValid = true;
                $(".package_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

                if (isValid) {
                    $('#package-save').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            //alert(data);
                            if ($.trim(data) === "1") {
                               $(".submit_message").html('<div class="alert alert-info">Package Inserted Successfully</div>');
                                $("#package-div").load(location.href + " #package-table");
                                setTimeout(function () {
                                    $(".package-notice").hide();
                                    $(".submit_message").hide();
                                    $("#package-settings").hide('slow');
                                    $("#package-form")[0].reset();
                                    $('#package-save').prop('disabled', false);

                                }, 1000);

                            } else if ($.trim(data) === "2") {
                                $(".submit_message").html('<div class="alert alert-info">Package Updated Successfully</div>');
                                $("#package-div").load(location.href + " #package-table");
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#package-settings").hide('slow');

                                    $("#package-form")[0].reset();
                                    $('#package-save').prop('disabled', false);

                                }, 1000);


                            } else {
                                $('#package-save').prop('disabled', false);

                                $(".submit_message").html('<div class="alert alert-danger">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();

                                }, 3000);
                            }
                        }
                    });
                }
                return false;
            });
            $(".package_input").focus(function () {
                $(this).removeClass("invalid");
            });

            $(document).on('click', '#reg_with_out_package', function () {
                var reg_type;
                if ($("#reg_with_out_package").is(':checked')) {
                    $("#package-div").hide();
                    $(".package-settings").hide();
                    $("#package-settings").hide();

                    $("#reg-amt-form").show();
                    reg_type = 'with_out_package';

                } else {
                    $("#reg-amt-form").hide();
                    $("#package-div").show();
                    $(".package-settings").show();
                    reg_type = 'with_package';
                }
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {reg_type: reg_type, action: 'wpmlm_ajax_general_settings'},
                    success: function (data) {
                        if (data == 1) {

                        }

                    }
                });
            });

        });

    </script>
    <?php
}