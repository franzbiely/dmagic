<?php
function wpmlm_payment_options() {
$result = wpmlm_get_paypal_details();    
$result1 = wpmlm_select_reg_type_name();
$arr = explode(',', $result1->reg_type);
$result2 = wpmlm_get_general_information();
?>
<div id="registration-type-settings">
    <div class="panel panel-default">

        <div class="panel-heading">
            <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Payment Settings','wpmlm-unilevel'); ?></span></h4>
        </div>

        <div class="panel-border">
            <h5><?php _e('Registration Type','wpmlm-unilevel'); ?></h5>
            <div class="submit-message1"></div>
            <form id="registration-type-settings-form" class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-2">
                        <input class="form-control reg_type reg_type_checkbox" name="reg_type[]" type="checkbox" <?php
                        if (in_array('free_join', $arr)) {
                            echo 'checked';
                        }
                        ?> value="free_join">
                        <label class="control-label" for="free_join"><?php _e('Free Join','wpmlm-unilevel'); ?></label>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-2">
                        <input class="form-control reg_type reg_type_checkbox" name="reg_type[]"  type="checkbox"value="paid_join" id="paid_join" <?php
                        if (in_array('paid_join', $arr)) {
                            echo 'checked';
                        }
                        ?>>
                        <label class="control-label" for="paid_join"><?php _e('Paid Join','wpmlm-unilevel'); ?></label>

                    </div>

                </div>
                <div class="form-group"> 
                    <div class="col-sm-2">
                        <button  name="reg-type-submit" class="btn btn-danger" id="reg-type-submit"><?php _e('Save','wpmlm-unilevel'); ?></button>
                    </div>
                </div>
                <?php wp_nonce_field('register_action', 'reg_submit'); ?>
            </form>
        </div>


        <?php 
        if (in_array('paid_join', $arr)) {
            $style="display:block;";       
        }else{
            $style="display:none;";
        } 
        ?>
        <div id="paypal-settings" style=<?php echo $style;?> >
            <div class="panel-border">
                <h3><img style="width:150px;" src=<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/gateway/paypal-sdk-v2/paypal.svg'; ?>></h3>
                <h5><?php _e('PayPal Credentials','wpmlm-unilevel'); ?></h5>
                <div class="submit-message"></div>
                <form id="payment-type-settings-form" class="form-horizontal " method="post">


                    <div class="form-group">
                        <div class="col-md-2"><label class="control-label" for="paypal_client_id"><?php _e('Client ID','wpmlm-unilevel'); ?></label></div>

                        <div class="col-md-6">
                            <input class="paypal_input form-control reg_type" name="paypal_client_id"  type="text"placeholder="<?php _e('Client ID','wpmlm-unilevel'); ?>" value="<?php echo $result->paypal_client_id; ?>">
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-md-2"><label class="control-label" for="paypal_client_secret"><?php _e('Client Secret','wpmlm-unilevel'); ?></label></div>

                        <div class="col-md-6">
                            <input class="paypal_input form-control reg_type" name="paypal_client_secret"  type="password" placeholder="<?php _e('Client Secret','wpmlm-unilevel'); ?>" value="<?php echo $result->paypal_client_secret; ?>">
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-md-2"><label class="control-label" for="paypal_currency"><?php _e('Currency','wpmlm-unilevel'); ?></label></div>
                        <div class="col-md-6">
                            <select class="form-control" name="paypal_currency" id="paypal_currency">
                                <option value="" tabindex="1"><?php _e('Select Currency','wpmlm-unilevel'); ?></option>
                                <option value="AUD" <?php echo ($result->paypal_currency == 'AUD') ? 'selected' : '' ;?>><?php _e('Australian dollar','wpmlm-unilevel'); ?></option>
                                <option value="BRL" <?php echo ($result->paypal_currency == 'BRL') ? 'selected' : '' ;?>><?php _e('Brazilian real','wpmlm-unilevel'); ?></option>
                                <option value="CAD" <?php echo ($result->paypal_currency == 'CAD') ? 'selected' : '' ;?>><?php _e('Canadian dollar','wpmlm-unilevel'); ?></option>
                                <option value="CNY" <?php echo ($result->paypal_currency == 'CNY') ? 'selected' : '' ;?>><?php _e('Chinese Renmenbi','wpmlm-unilevel'); ?></option>
                                <option value="CZK" <?php echo ($result->paypal_currency == 'CZK') ? 'selected' : '' ;?>><?php _e('Czech koruna','wpmlm-unilevel'); ?></option>
                                <option value="DKK" <?php echo ($result->paypal_currency == 'DKK') ? 'selected' : '' ;?>><?php _e('Danish krone','wpmlm-unilevel'); ?></option>
                                <option value="EUR" <?php echo ($result->paypal_currency == 'EUR') ? 'selected' : '' ;?>><?php _e('Euro','wpmlm-unilevel'); ?></option>
                                <option value="HKD" <?php echo ($result->paypal_currency == 'HKD') ? 'selected' : '' ;?>><?php _e('Hong Kong dollar','wpmlm-unilevel'); ?></option>
                                <option value="HUF" <?php echo ($result->paypal_currency == 'HUF') ? 'selected' : '' ;?>><?php _e('Hungarian forint','wpmlm-unilevel'); ?></option>
                                <option value="INR" <?php echo ($result->paypal_currency == 'INR') ? 'selected' : '' ;?>><?php _e('Indian rupee','wpmlm-unilevel'); ?></option>
                                <option value="ILS" <?php echo ($result->paypal_currency == 'ILS') ? 'selected' : '' ;?>><?php _e('Israeli new shekel','wpmlm-unilevel'); ?></option>
                                <option value="JPY" <?php echo ($result->paypal_currency == 'JPY') ? 'selected' : '' ;?>><?php _e('Japanese yen','wpmlm-unilevel'); ?></option>
                                <option value="MYR" <?php echo ($result->paypal_currency == 'MYR') ? 'selected' : '' ;?>><?php _e('Malaysian ringgit','wpmlm-unilevel'); ?></option>
                                <option value="MXN" <?php echo ($result->paypal_currency == 'MXN') ? 'selected' : '' ;?>><?php _e('Mexican peso','wpmlm-unilevel'); ?></option>
                                <option value="TWD" <?php echo ($result->paypal_currency == 'TWD') ? 'selected' : '' ;?>><?php _e('New Taiwan dollar','wpmlm-unilevel'); ?></option>
                                <option value="NOK" <?php echo ($result->paypal_currency == 'NOK') ? 'selected' : '' ;?>><?php _e('Norwegian krone','wpmlm-unilevel'); ?></option>
                                <option value="PHP" <?php echo ($result->paypal_currency == 'PHP') ? 'selected' : '' ;?>><?php _e('Philippine peso','wpmlm-unilevel'); ?></option>
                                <option value="PLN" <?php echo ($result->paypal_currency == 'PLN') ? 'selected' : '' ;?>><?php _e('Polish złoty','wpmlm-unilevel'); ?></option>
                                <option value="GBP" <?php echo ($result->paypal_currency == 'GBP') ? 'selected' : '' ;?>><?php _e('Pound sterling','wpmlm-unilevel'); ?></option>
                                <option value="RUB" <?php echo ($result->paypal_currency == 'RUB') ? 'selected' : '' ;?>><?php _e('Russian ruble','wpmlm-unilevel'); ?></option>
                                <option value="SGD" <?php echo ($result->paypal_currency == 'SGD') ? 'selected' : '' ;?>><?php _e('Singapore dollar','wpmlm-unilevel'); ?></option>
                                <option value="SEK" <?php echo ($result->paypal_currency == 'SEK') ? 'selected' : '' ;?>><?php _e('Swedish krona','wpmlm-unilevel'); ?></option>
                                <option value="CHF" <?php echo ($result->paypal_currency == 'CHF') ? 'selected' : '' ;?>><?php _e('Swiss franc','wpmlm-unilevel'); ?></option>
                                <option value="THB" <?php echo ($result->paypal_currency == 'THB') ? 'selected' : '' ;?>><?php _e('Thai baht','wpmlm-unilevel'); ?></option>
                                <option value="USD" <?php echo ($result->paypal_currency == 'USD') ? 'selected' : '' ;?>><?php _e('US dollar','wpmlm-unilevel'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-2"><label class="control-label" for="paypal_mode"><?php _e('Paypal Mode','wpmlm-unilevel'); ?></label></div>
                        <div class="col-md-2">
                            <input class="form-control reg_type" name="paypal_mode" type="radio" <?php if ($result->paypal_mode == 'sandbox') { echo 'checked'; } ?> checked value="sandbox"  >
                            <label class="control-label" for="test">&nbsp;<?php _e('Sandbox','wpmlm-unilevel'); ?></label>
                        </div>
                        <div class="col-md-2">
                            <input class="form-control reg_type" name="paypal_mode" type="radio"  value="live" <?php if ($result->paypal_mode == 'live') { echo 'checked'; } ?> >
                            <label class="control-label" for="live">&nbsp;<?php _e('Live','wpmlm-unilevel'); ?></label>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <div class="col-sm-offset-2 col-sm-2">
                            <button  name="payment-type-submit" class="btn btn-danger" id="payment-type-submit"><?php _e('Save','wpmlm-unilevel'); ?></button>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $result2->registration_type;?>" id="reg_type">
                    <?php wp_nonce_field('payment_action', 'payment_submit'); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {

        $(document).on('click','#paid_join',function () {
            
            if ($("#paid_join").is(':checked')) {                                       
                $("#paypal-settings").show();
            } else {
                $("#paypal-settings").hide();
            }
        });          

        var plugin_url = path.pluginsUrl;
  
        $("#registration-type-settings-form").submit(function () {
            $(".submit-message1").show(); 
            var formData = new FormData(this);
            formData.append('action', 'wpmlm_ajax_payment_option');
            
            isValid = true;
            
            if ($('.reg_type:checkbox:checked').length == 0) {
                $(".submit-message1").html('<div class="alert alert-danger">Please select atleast one registration type</div>');
                setTimeout(function () {
                         $(".submit-message1").hide('slow');   

                        }, 3000);
                
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
                        $(".submit-message1").show();
                        $(".submit-message1").html('<div class="alert alert-info">' + data + '</div>');
                        setTimeout(function () {
                            $(".submit-message1").hide();
                        }, 2000);
                    }
                });
            }
            return false;
        });
        
        $("#payment-type-settings-form").submit(function () {
            isValid = true;
            var formData = new FormData(this);
            formData.append('action', 'wpmlm_ajax_payment_option');
            $(".paypal_input").each(function () {
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
                        $(".submit-message").show();
                        $(".submit-message").html('<div class="alert alert-info">' + data + '</div>');
                        setTimeout(function () {
                            $(".submit-message").hide();
                        }, 2000);
                    }
                });
            }
            return false;
        });

    });

</script>
<?php
}