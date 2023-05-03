<?php

require (WP_MLM_PLUGIN_DIR . '/gateway/paypal-sdk-v2/vendor/autoload.php');
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

function wpmlm_ajax_user_profile() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $table_name1 = $wpdb->prefix . "users";
    $user_id = intval($_POST['user_id']);
    if (isset($_POST['user_form3_nonce']) && wp_verify_nonce($_POST['user_form3_nonce'], 'user_form3')) {

        $user_address = sanitize_text_field($_POST['address1']);
        $user_email = sanitize_email($_POST['user_email']);
        $user_dob = sanitize_text_field($_POST['dob']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_mobile = sanitize_text_field($_POST['contact_no']);

        $user_details = array(
            'user_address' => $user_address,
            'user_email' => $user_email,
            'user_dob' => $user_dob,
            'user_city' => $user_city,
            'user_state' => $user_state,
            'user_country' => $user_country,
            'user_zip' => $user_zip,
            'user_mobile' => $user_mobile
        );
        $condition = array('user_ref_id' => $user_id);
        $wpdb->update($table_name, $user_details, $condition);

        $user_details1 = array(
            'user_email' => $user_email
        );
        $condition1 = array('ID' => $user_id);
        $result = $wpdb->update($table_name1, $user_details1, $condition1);


        _e("Updated Successfully","wpmlm-unilevel");
        exit();
    }


    if (isset($_POST['user_form4_admin_nonce']) && wp_verify_nonce($_POST['user_form4_admin_nonce'], 'user_form4_admin')) {

        $newpassword = $_POST['password_admin'];
        wp_set_password($newpassword, $user_id);
        //echo 'Password Updated Successfully';
        _e("Password Updated Successfully","wpmlm-unilevel");
        exit();
    }
}

//Ajax function for General settings
function wpmlm_ajax_general_settings() {
    
    global $wpdb;
    $msg = '';
    if (isset($_POST['general_add_nonce']) && wp_verify_nonce($_POST['general_add_nonce'], 'general_add')) {

        $company_name = sanitize_text_field($_POST['company_name']);
        $company_address = sanitize_text_field($_POST['company_address']);
        $company_email = sanitize_email($_POST['company_email']);
        $company_phone = sanitize_text_field($_POST['company_phone']);
        $company_currency = sanitize_text_field($_POST['company_currency']);
        $user_dash = sanitize_text_field($_POST['user_dash']);
        $user_registration = sanitize_text_field($_POST['user_registration']);
        $site_logo = sanitize_text_field($_POST['site_logo']);

        if (!empty($_FILES['company_logo']['name'])) {
            $uploaddir = WP_MLM_PLUGIN_DIR . '/uploads/';
            $file = $uploaddir . basename($_FILES['company_logo']['name']);


            if (file_exists($file)) {
                $duplicate_filename = TRUE;
                $i = 0;
                while ($duplicate_filename) {
                    $filename_data = explode(".", $_FILES['company_logo']['name']);
                    $new_filename = $filename_data[0] . "_" . $i . "." . $filename_data[1];
                    $_FILES['company_logo']['name'] = $new_filename;
                    $file = $uploaddir . basename($_FILES['company_logo']['name']);
                    if (file_exists($file)) {
                        $i++;
                    } else {
                        $duplicate_filename = FALSE;
                    }
                }
            }

            $company_logo = $_FILES['company_logo']['name'];
            if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $file)) {
                $msg .='';
            } else {
                $msg .="Image Uploading Error";
            }
        } else {
            $company_logo = sanitize_text_field($_POST['image']);
        }
        if ($company_logo == '') {
            $company_logo = 'default_logo.png';
        }


        if (empty($msg)) {
            $company = array(
                'company_name' => $company_name,
                'company_address' => $company_address,
                'company_logo' => $company_logo,
                'company_email' => $company_email,
                'company_phone' => $company_phone,
                'company_currency' => $company_currency,
                'user_dash' => $user_dash,
                'user_registration' => $user_registration,
                'site_logo' => $site_logo
            );
            $table_name = $wpdb->prefix . "wpmlm_general_information";

            $condition = array('id' => 1);
            $wpdb->update($table_name, $company, $condition);
            $msg = __('Successfully Updated',"wpmlm-unilevel");

            $company_settings_style = '';
            if (!empty($msg)) {
                echo nl2br($msg);
                exit();
            } else {
                // _e($msg,"wpmlm-unilevel");
                echo $msg;
                exit();
            }
        }
    }

    if (isset($_POST['reg_type'])) {
        $reg_type = $_POST['reg_type'];
        $company = array(
            'registration_type' => $reg_type
        );
        $table_name = $wpdb->prefix . "wpmlm_general_information";
        $condition = array('id' => 1);
        $result = $wpdb->update($table_name, $company, $condition);

        echo $result;
        exit();
    }

    if (isset($_POST['act_inact'])) {
        $act_inact = sanitize_text_field($_POST['act_inact']);
        $company = array(
            'site_logo' => $act_inact
        );
        $table_name = $wpdb->prefix . "wpmlm_general_information";
        $condition = array('id' => 1);
        $result = $wpdb->update($table_name, $company, $condition);
        echo $result;
        exit();
    }
}

function wpmlm_ajax_ewallet_management() {

    $msg = '';
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    if (isset($_POST['fund_management_add_nonce']) && wp_verify_nonce($_POST['fund_management_add_nonce'], 'fund_management_add')) {


        $ewallet_user_name = sanitize_text_field($_POST['ewallet_user_name']);
        $usersStatus = wpmlm_ifactiveUser($ewallet_user_name);
        $ewallet_user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$ewallet_user_name'");
        if (!$ewallet_user_name || !$usersStatus) {
            _e("Sorry! The specified user is not available","wpmlm-unilevel");
            exit();
        }


        $fund_amount = sanitize_text_field($_POST['fund_amount']);
        $fund_action = sanitize_text_field($_POST['fund_action']);
        $transaction_note = sanitize_text_field($_POST['transaction_note']);

        $from_user_id = get_current_user_id();
        $the_user = get_user_by('login', $ewallet_user_name);
        $to_user_id = $the_user->ID;
        $transaction_id = wpmlm_getUniqueTransactionId();


        $date = date('Y-m-d H:i:s');
        $fund_details = array(
            'from_user_id' => $from_user_id,
            'to_user_id' => $to_user_id,
            'amount' => $fund_amount,
            'date' => $date,
            'amount_type' => $fund_action,
            'transaction_concept' => $transaction_note,
            'transaction_id' => $transaction_id
        );

        $bal_amount_arr = wpmlm_getBalanceAmount($to_user_id);
        $bal_amount = $bal_amount_arr->balance_amount;


        if ($fund_action == 'admin_debit') {

            if (is_numeric($fund_amount) && $fund_amount > 0 && $bal_amount >= $fund_amount) {
                wpmlm_insert_fund_transfer_details($fund_details);
                $lastid = $wpdb->insert_id;

                $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $to_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'fund_transfer',
                    'amount' => $fund_amount,
                    'amount_type' => $fund_action,
                    'type' => ($fund_action == 'admin_debit') ? 'debit' : 'credit',
                    'date_added' => $date,
                    'transaction_note' => $transaction_note,
                    'transaction_id' => $transaction_id
                );

                wpmlm_updateBalanceAmountDetailsFrom($to_user_id, $fund_amount);
                $res = wpmlm_addEwalletHistory($ewallet_details);
            } else {
                _e("Sorry! Insufficient Balance","wpmlm-unilevel");
                exit();
            }
        }


        if ($fund_action == 'admin_credit') {

            if (is_numeric($fund_amount) && $fund_amount > 0) {
                wpmlm_insert_fund_transfer_details($fund_details);
                $lastid = $wpdb->insert_id;

                $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $to_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'fund_transfer',
                    'amount' => $fund_amount,
                    'amount_type' => $fund_action,
                    'type' => ($fund_action == 'admin_debit') ? 'debit' : 'credit',
                    'date_added' => $date,
                    'transaction_note' => $transaction_note,
                    'transaction_id' => $transaction_id
                );

                wpmlm_updateBalanceAmountDetailsTo($to_user_id, $fund_amount);
                $res = wpmlm_addEwalletHistory($ewallet_details);
            } else {
                _e("Sorry! Insufficient Balance","wpmlm-unilevel");
                exit();
            }
        }

        if ($res) {
            _e("Transaction Completed Successfully","wpmlm-unilevel");
            exit();
        } else {
            _e("Transaction Failed","wpmlm-unilevel");
            exit();
        }
    }

// Level Commission 
if (isset($_POST['level_commission_add_nonce']) && wp_verify_nonce($_POST['level_commission_add_nonce'], 'level_commission_add')) {

    $user_name = sanitize_text_field($_POST['user_name']);
    $pack_id = sanitize_text_field($_POST['trade_amount']);
    $package_id = wpmlm_select_package_by_id( $pack_id);
    $trade_amount = $package_id->package_price; 
    
    $date = date('Y-m-d H:i:s');
    $usersStatus = wpmlm_ifactiveUser($user_name);
    $is_user_name_exit = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$user_name'");
    if (!$is_user_name_exit || !$usersStatus) {
        _e("Sorry! The specified user is not available.","wpmlm-unilevel");
        exit();
    }
    $the_user = get_user_by('login', $user_name);
    $to_user_id = $the_user->ID;
    $res = wpmlm_insert_leg_amount_new($to_user_id, $trade_amount,$package_id);
    if ($res) {
        _e("Level Commission Updated Successfully","wpmlm-unilevel");
        exit();
    } else {
        _e("Error Occured","wpmlm-unilevel");
        exit();
    }
}



//End Level Commission



    if (isset($_POST['fund_transfer_add_nonce']) && wp_verify_nonce($_POST['fund_transfer_add_nonce'], 'fund_transfer_add')) {

        $transaction_password = $_POST['transaction_password'];
        $ewallet_user_name = sanitize_text_field($_POST['ewallet_user_name']);
        $ewallet_user_name_to = sanitize_text_field($_POST['ewallet_user_name_to']);
        $fund_transfer_amount = sanitize_text_field($_POST['fund_transfer_amount']);
        $transaction_note = sanitize_text_field($_POST['transaction_note1']);
        $date = date('Y-m-d H:i:s');


        if ($ewallet_user_name == $ewallet_user_name_to) {
            echo '1';
            exit();
        }

        $usersStatus = wpmlm_ifactiveUser($ewallet_user_name);
        $ewallet_user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$ewallet_user_name'");
        if (!$ewallet_user_name || !$usersStatus) {
            echo 'Sorry! The specified user is not available.';
            exit();
        }



        $usersStatus1 = wpmlm_ifactiveUser($ewallet_user_name_to);
        $ewallet_user_name_to = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$ewallet_user_name_to'");
        if (!$ewallet_user_name_to || !$usersStatus1) {
            echo 'Sorry! The specified transfer to user is not available.';
            exit();
        }


        $the_user = get_user_by('login', $ewallet_user_name);
        $from_user_id = $the_user->ID;


        $bal_amount_arr = wpmlm_getBalanceAmount($from_user_id);
        $bal_amount = $bal_amount_arr->balance_amount;

        if ($fund_transfer_amount > $bal_amount) {
            echo 'Sorry! Insufficient Balance';
            exit();
        }


        if (isset($_POST['ewallet_user_id'])) {
            $from_user_id = intval($_POST['ewallet_user_id']);
        }

        $the_user1 = get_user_by('login', $ewallet_user_name_to);
        $to_user_id = $the_user1->ID;

        $tran_pass_arr = wpmlm_getUserPasscode($from_user_id);
        $tran_password = $tran_pass_arr->tran_password;


        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);

        if ($wp_hasher->CheckPassword($transaction_password, $tran_password)) {
            $transaction_id = wpmlm_getUniqueTransactionId();
            $date = date('Y-m-d H:i:s');

            $fund_details = array(
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'amount' => $fund_transfer_amount,
                'date' => $date,
                'amount_type' => 'user_credit',
                'transaction_concept' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_insert_fund_transfer_details($fund_details);
            $lastid = $wpdb->insert_id;


            $ewallet_details = array(
                'from_id' => $from_user_id,
                'user_id' => $to_user_id,
                'ewallet_id' => $lastid,
                'ewallet_type' => 'fund_transfer',
                'amount' => $fund_transfer_amount,
                'amount_type' => 'user_credit',
                'type' => 'credit',
                'date_added' => $date,
                'transaction_note' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_updateBalanceAmountDetailsTo($to_user_id, $fund_transfer_amount);
            wpmlm_addEwalletHistory($ewallet_details);



            $fund_details = array(
                'from_user_id' => $to_user_id,
                'to_user_id' => $from_user_id,
                'amount' => $fund_transfer_amount,
                'date' => $date,
                'amount_type' => 'user_debit',
                'transaction_concept' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_insert_fund_transfer_details($fund_details);
            $lastid = $wpdb->insert_id;


            $ewallet_details = array(
                'from_id' => $to_user_id,
                'user_id' => $from_user_id,
                'ewallet_id' => $lastid,
                'ewallet_type' => 'fund_transfer',
                'amount' => $fund_transfer_amount,
                'amount_type' => 'user_debit',
                'type' => 'debit',
                'date_added' => $date,
                'transaction_note' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_updateBalanceAmountDetailsFrom($from_user_id, $fund_transfer_amount);
            $res = wpmlm_addEwalletHistory($ewallet_details);
            if ($res) {
                echo 'Transaction Completed Successfully';
                exit();
            } else {
                echo 'Transaction Failed';
                exit();
            }
        } else {
            echo '0';
            exit();
        }
    }


    if (isset($_POST['transfer_details_nonce']) && wp_verify_nonce($_POST['transfer_details_nonce'], 'transfer_details')) {

        $start_date = sanitize_text_field($_POST['start_date1']);
        $end_date = sanitize_text_field($_POST['end_date1']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";

        if (isset($_POST['transer_user_id'])) {
            $user_id = intval($_POST['transer_user_id']);
        } else {

            $search = sanitize_text_field($_POST['search1']);
            $user = get_userdatabylogin($search);
            $user_id = $user->ID;
        }
        $results = wpmlm_getTransferDetails($user_id, $start_date_1, $end_date_1);

        $result2 = wpmlm_get_general_information();



        if (count($results) > 0) {
            ?>

            <table id="transaction_details_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _e('Username','wpmlm-unilevel'); ?></th>
                        <th><?php _e("Transaction Id","wpmlm-unilevel"); ?></th>       
                        <th><?php _e("Amount","wpmlm-unilevel"); ?></th>
                        <th><?php _e("Transfer Type","wpmlm-unilevel"); ?></th>
                        <th><?php _e("Transfer Note","wpmlm-unilevel"); ?></th>
                        <th><?php _e("Date","wpmlm-unilevel"); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($results as $res) {
                        $count++;

                        $from_id = $res->from_user_id;
                        $the_user = get_user_by('ID', $from_id);
                        $username = $the_user->user_login;

                        echo '<tr>
            <td>' . $count . '</td>
            <td>' . $username . '</td>
            <td>' . $res->transaction_id . '</td>
            <td>' . $result2->company_currency . ' ' . $res->amount . '</td>
            <td>' . ucwords(str_replace("_", " ", $res->amount_type)) . '</td>
            <td>' . $res->transaction_concept . '</td>  
            <td>' . date("Y/m/d", strtotime($res->date)) . '</td>
            </tr>';
                    }
                    ?>
                </tbody> 
            </table>

            <script>
                jQuery(document).ready(function ($) {
                    $('#transaction_details_table').DataTable({
                        "pageLength": 10
                    });
                });

            </script>
            <?php
        } else {
            echo '<div class="no-data">'. __("No Data","wpmlm-unilevel") .' </div>';
        }
        exit();
    }


    if (isset($_POST['ewallet_user_name'])) {
        $user_name = sanitize_text_field($_POST['ewallet_user_name']);
        $usersStatus = wpmlm_ifactiveUser($user_name);
        $user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$user_name'");
        if (!$user_name || !$usersStatus) {
             _e("Sorry! The specified user is not available.","wpmlm-unilevel");
        } else {
            echo '1';
        }
        exit();
    }
    if (isset($_POST['ewallet_user_name_to'])) {
        $user_name = sanitize_text_field($_POST['ewallet_user_name_to']);
        $usersStatus = wpmlm_ifactiveUser($user_name);
        $user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$user_name'");
        if (!$user_name || !$usersStatus) {
            _e("Sorry! The specified to user is not available.","wpmlm-unilevel");
        } else {
            echo '1';
        }
        exit();
    }


    if (isset($_POST['ewallet_user_balance'])) {
        $general = wpmlm_get_general_information();
        $user_name = sanitize_text_field($_POST['ewallet_user_balance']);
        $the_user = get_user_by('login', $user_name);
        $user_id = $the_user->ID;
        $bal_amount_arr = wpmlm_getBalanceAmount($user_id);
        $bal_amount = $general->company_currency . $bal_amount_arr->balance_amount;

        if ($bal_amount_arr) {
            echo $bal_amount;
            exit();
        } else {
            _e("No Data","wpmlm-unilevel");
            exit();
        }
    }
}

function wpmlm_level_bonus() {
    global $wpdb;
    if (isset($_POST['depth'])) {
        $depth = wpmlm_get_level_depth();       
        $depth_new = intval($_POST['depth']);
        if ($depth_new != $depth) {
            wpmlm_setLevel($depth_new);
        }
        _e("Level Bonus Depth Updated","wpmlm-unilevel");
        exit();
    }

    if (isset($_POST['level_commission_nonce']) && wp_verify_nonce($_POST['level_commission_nonce'], 'level_commission')) {
        $level_commission = $_POST['level_commission'];
        $reg_pack = $_POST['reg_commission'];          
        $level_type = sanitize_text_field($_POST['level_type']);
        wpmlm_update_level_commission($level_commission);
        wpmlm_update_reg_pack_level_commission($reg_pack);
        wpmlm_update_level_commission_type($level_type);
        _e("Level Bonus Updated","wpmlm-unilevel");
        exit();
    }
}

function wpmlm_ajax_transaction_password() {
    global $wpdb;
    if (isset($_POST['change_tran_pass_nonce']) && wp_verify_nonce($_POST['change_tran_pass_nonce'], 'change_tran_pass')) {


        $current_tran_pass = $_POST['current_tran_pass'];
        $new_tran_pass = $_POST['new_tran_pass'];
        $confirm_tran_pass = $_POST['confirm_tran_pass'];
        $user_id = get_current_user_id();
        $pass = wpmlm_getUserPasscode($user_id);

        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
        $err = '';
        if (!$wp_hasher->CheckPassword($current_tran_pass, $pass->tran_password)) {
            $err.='<p>' . __("Your current transaction password is incorrect","wpmlm-unilevel") . '</p>';
        }

        if (strlen($new_tran_pass) < 8) {
            $err.='<p>'. __("New Password should be at least 8 characters in length","wpmlm-unilevel") . '</p>';
        }
        if ($new_tran_pass != $confirm_tran_pass) {
            $err.='<p>' . __("New and confirm password miss match","wpmlm-unilevel") . '</p>';
        }

        if (empty($err)) {

            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);

            if ($update) {
                echo '1';
                exit();
            } else {
                $err.='<p>' . __("Password updation failed","wpmlm-unilevel") . '</p>';
                exit();
            }
        } else {
            echo $err;
            exit();
        }
    }


    if (isset($_POST['change_user_tran_pass_nonce']) && wp_verify_nonce($_POST['change_user_tran_pass_nonce'], 'change_user_tran_pass')) {


        $username = sanitize_text_field($_POST['change_tran_pass_user']);
        $new_tran_pass = $_POST['new_user_tran_pass'];
        $confirm_tran_pass = $_POST['confirm_user_tran_pass'];

        $the_user = get_user_by('login', $username);
        $user_id = $the_user->ID;
        $err = '';


        if (!$user_id) {
            $err.='<p>Sorry! Username not exist</p>';
        }
        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
        if (strlen($new_tran_pass) < 8) {
            $err.='<p>New Password should be at least 8 characters in length</p>';
        }
        if ($new_tran_pass != $confirm_tran_pass) {
            $err.='<p>New and confirm password miss match</p>';
        }
        if (empty($err)) {
            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {
                echo '1';
                exit();
            } else {
                $err.='<p>' . __("Password updation failed","wpmlm-unilevel") . '</p>';
                echo $err;
                exit();
            }
        } else {
            echo $err;
            exit();
        }
    }


    if (isset($_POST['send_tran_pass_nonce']) && wp_verify_nonce($_POST['send_tran_pass_nonce'], 'send_tran_pass')) {
        $username = sanitize_text_field($_POST['tran_user_name']);


        $the_user = get_user_by('login', $username);
        $user_id = $the_user->ID;
        $err = '';


        if (!$user_id) {
            $err.='<p>'. __("Sorry! Username not exist","wpmlm-unilevel") . '</p>';
            echo $err;
            exit();
        } else {

            $new_tran_pass = wpmlm_getRandStrPassword();
            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {

                $to = $the_user->user_email;
                $mail = wpmlm_sendMailTransactionPass($to, $new_tran_pass);

                if ($mail) {
                    echo '1';
                    exit();
                } else {
                    $err.='<p>' . __("Mail sending failed","wpmlm-unilevel") . '</p>';
                    //_e($err,"wpmlm-unilevel");
                    echo $err;
                    exit();
                }
            } else {
                $err.='<p>' . __("Password sending failed","wpmlm-unilevel") . '</p>';
                echo $err;
                exit();
            }
        }
    }



    if (isset($_POST['forgot_tran_pass_nonce']) && wp_verify_nonce($_POST['forgot_tran_pass_nonce'], 'forgot_tran_pass')) {
        global $wpdb;

            $the_user = wp_get_current_user();
            $user_id = $the_user->ID;

            $new_tran_pass = wpmlm_getRandStrPassword();
            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {

                $to = $the_user->user_email;
                $mail = wpmlm_sendMailTransactionPass($to, $new_tran_pass);

                if ($mail) {
                    echo '1';
                    exit();
                } else {
                    $err.='<p>'.__("Mail sending failed","wpmlm-unilevel").'</p>';
                    echo $err;
                    exit();
                }
            } else {
                $err.='<p>'. __("Password sending failed","wpmlm-unilevel") .'</p>';
                echo $err;
                exit();
            }
        
    }


    if (isset($_POST['user_password_admin_nonce']) && wp_verify_nonce($_POST['user_password_admin_nonce'], 'user_password_admin')) {
        $newpassword = $_POST['password_user'];
        $username = $_POST['username_pwd'];
        $the_user = get_user_by('login', $username);
        
        
        $user_id = $the_user->ID;

        if (!$user_id) {
            _e('Sorry! Username not exist',"wpmlm-unilevel");
            exit();
        }
        wp_set_password($newpassword, $user_id);
        _e('Password Updated Successfully',"wpmlm-unilevel");
        exit();
    }
}

function wpmlm_ajax_payment_option() {
    global $wpdb;

    if (isset($_POST['payment_submit']) && wp_verify_nonce($_POST['payment_submit'], 'payment_action')) {


        $paypal_client_id = sanitize_text_field($_POST['paypal_client_id']);
        $paypal_client_secret = $_POST['paypal_client_secret'];
        $paypal_currency = $_POST['paypal_currency'];
        $paypal_mode = sanitize_text_field($_POST['paypal_mode']);

        $paypal = array(
            'paypal_client_id' => $paypal_client_id,
            'paypal_client_secret' => $paypal_client_secret,
            'paypal_currency' => $paypal_currency,
            'paypal_mode' => $paypal_mode
        );

        global $wpdb;
        $table_name = $wpdb->prefix . "wpmlm_paypal";

        $sql1 = "TRUNCATE TABLE {$table_name} ";
        $wpdb->query($sql1);

        $result = $wpdb->insert($table_name, $paypal);
        if ($result) {
            _e('Successfully Updated',"wpmlm-unilevel");
            exit();
        } else {
            _e('Error in database insert',"wpmlm-unilevel");
            exit();
        }
    }

    if (isset($_POST['reg_submit']) && wp_verify_nonce($_POST['reg_submit'], 'register_action')) {
        $type = array_map( 'sanitize_text_field', wp_unslash( $_POST['reg_type'] ) );
        wpmlm_insert_reg_type($type);
        _e('Successfully Updated',"wpmlm-unilevel");
        exit();
    }
}

function wpmlm_ajax_package_settings() {

    $msg = '';
    if (isset($_POST['package_add_nonce']) && wp_verify_nonce($_POST['package_add_nonce'], 'package_add')) {

        $package_name = sanitize_text_field($_POST['package_name']);
        $package_price = sanitize_text_field($_POST['package_price']);

        if (inputFieldBlankCheck($package_name))
            $msg .= "<p>". __('Please enter registration package name','wpmlm-unilevel')."</p>";
        if (inputFieldBlankCheck($package_price))
            $msg .= "<p>". __('Please enter your registration package price','wpmlm-unilevel')."</p>";



        if (empty($msg)) {
            $package = array(
                'package_name' => $package_name,
                'package_price' => $package_price
            );
            global $wpdb;
            $table_name = $wpdb->prefix . "wpmlm_registration_packages";

            if ($_POST['submit-action'] == '') {

                $count = wpmlm_package_name_check($package_name);


                if ($count > 0) {

                    $msg .="__('Package name already exists','wpmlm-unilevel')";
                } else {

                    $result = $wpdb->insert($table_name, $package);
                    $lastid = $wpdb->insert_id;
                    if ($result) {
                        //wpmlm_update_table_column_levelcommission($lastid);
                        $msg = '1';
                    } else {
                        $msg .="__('Error in database insert','wpmlm-unilevel')";
                    }
                }
            } else {
                $package_id = intval($_POST['package_id']);
                $condition = array('id' => $package_id);
                $wpdb->update($table_name, $package, $condition);
                $msg = '2';
            }
            $package_settings_style = '';
            if (!empty($msg)) {
                echo nl2br($msg);
                exit();
            } else {
                echo $msg;
                exit();
            }
        }
    } else if (isset($_POST['package_id'])) {
        $package_id = intval($_POST['package_id']);
        $result = wpmlm_select_package_by_id($package_id);
        echo json_encode($result);
        exit();
    } else if (isset($_POST['package_delete_id'])) {

        $package_id = intval($_POST['package_delete_id']);
        $result = wpmlm_delete_package_by_id($package_id);
        //to delete level commission column
        //wpmlm_delete_table_column_levelcommission($package_id);
        if ($result) {
            echo "1";
        }
        exit();
    }



    if (isset($_POST['reg_amt_add_nonce']) && wp_verify_nonce($_POST['reg_amt_add_nonce'], 'reg_amt_add')) {

        $reg_amt = sanitize_text_field($_POST['reg_amt']);
        $data = array(
            'registration_amt' => $reg_amt
        );
        global $wpdb;


        $general = wpmlm_get_general_information();
        $reg_amt_old = $general->registration_amt;
        if ($reg_amt_old != $reg_amt) {

            $table_name = $wpdb->prefix . "wpmlm_general_information";

            $condition = array('id' => 1);
            echo $result = $wpdb->update($table_name, $data, $condition);
            exit();
        } else {
            echo '2';
            exit();
        }
    }
}

function wpmlm_ajax_profile_report() {

    if (isset($_POST['default_profile']) && ($_POST['default_profile'] == 'profile_report_all')) {

        $result = wpmlm_get_all_user_details_join();


        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '<div class="row row-bottom">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <div class="report-header">
                        <img src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        
                    </div>
                </div>
                <div class="col-sm-8">

                    <table class="report-header-right">
                        <tr height="20px">
                            <td  colspan="3" align="center">
                                <h1>
                                    <font face="Arial, Helvetica, sans-serif">
                                    <h3> ' . $res->company_name . '</h3></font>
                                </h1>
                            </td>
                        </tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000"></font>' . $res->company_address . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">'. __('Phone','wpmlm-unilevel').':</font> ' . $res->company_phone . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">'. __('Email','wpmlm-unilevel').':</font> ' . $res->company_email . '</b></td></tr>
                    </table>
                </div>
                <div class="col-sm-2">
                    <b>'. __('Date','wpmlm-unilevel').': ' . date("Y-m-d") . '</b>
                </div>
            </div>
        </div>
        <hr />
        <h2 style="text-align: center;">'. __("Profile Report","wpmlm-unilevel") . '</h2>
            <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                                            
    <tr>
    <th>'. __('No','wpmlm-unilevel').'</th>
    <th>'. __('Name','wpmlm-unilevel').'</th>              
    <th>'. __('Username','wpmlm-unilevel').'</th>
    <th>'. __('Sponsor Name','wpmlm-unilevel').'</th>
    <th>'. __('Date of Birth','wpmlm-unilevel').'</th>
    <th>'. __('Address','wpmlm-unilevel').'</th>
    <th>'. __('Zip Code','wpmlm-unilevel').'</th>
    <th>'. __('Mobile No','wpmlm-unilevel').'</th>        
    <th>'. __('Email','wpmlm-unilevel').'</th>
    <th>'. __('Date of Joining','wpmlm-unilevel').'</th>
</tr>';

            $count = 1;
            foreach ($result as $res) {
                $sponsor_id = $res->user_parent_id;
                $res1 = wpmlm_get_user_details_by_id($sponsor_id);
                $data.='<tr><td>' . $count++ . '</td>
        <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
        <td>' . $res->user_login . '</td>
        <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
        <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>
        <td>' . ($res->user_address ? "$res->user_address" : "NA") . '</td>
        <td>' . ($res->user_zip ? "$res->user_zip" : "NA") . '</td>
        <td>' . ($res->user_mobile ? "$res->user_mobile" : "NA") . '</td>
        <td>' . $res->user_email . '</td>
        <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td></tr>';
            }
            '</table>';
            echo $data;
            exit();
            ?>

            <?php
        } else {
            echo 0;
            exit();
        }
    }


    if (isset($_POST['search']) || isset($_POST['search_type'])) {

        $search = $_POST['search'];
       
        $search_type = $_POST['search_type'];
        if ($search_type == 'all') {
            $result = wpmlm_get_all_user_details_join();
        } else {
            if (username_exists($search)) {               

                $user = get_userdatabylogin($search);
                $user_id = $user->ID;

                $result = wpmlm_get_user_details_by_id_join($user_id);


            } else {
                echo 'no-user';
                exit;
            }

        }

        $res = wpmlm_get_general_information();

        if (count($result) > 0) {


            $data = '<div class="row row-bottom">
            <div class="col-sm-12" >
                <div class="col-sm-2" >
                    <div class="report-header">
                        <img src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        
                    </div>
                </div>
                <div class="col-sm-8" >

                    <table class="report-header-right">
                        <tr height="20px">
                            <td  colspan="3" align="center" class="company_td">
                                <h1>
                                    <font face="Arial, Helvetica, sans-serif">
                                    <h3> ' . $res->company_name . '</h3></font>
                                </h1>
                            </td>
                        </tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000"></font>' . $res->company_address . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">'. __("Phone","wpmlm-unilevel") . ':</font> ' . $res->company_phone . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">'. __("Email","wpmlm-unilevel") . ':</font> ' . $res->company_email . '</b></td></tr>
                    </table>
                </div>
                <div class="col-sm-2 ">
                    <b>'. __("Date","wpmlm-unilevel") . ': ' . date("Y-m-d") . '</b>
                </div>
            </div>
        </div>
        <hr />
        <h2 style="text-align: center;">'.__("Profile Report","wpmlm-unilevel") . '</h2>
            <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                                            
    <tr>
    <th>#</th>
    <th>' . __("Name","wpmlm-unilevel") . '</th>              
    <th>' . __("Username","wpmlm-unilevel") . '</th>
    <th>' . __("Sponsor Name","wpmlm-unilevel") . '</th>
    <th>' . __("Date of Birth","wpmlm-unilevel") . '</th>
    <th>' . __("Address","wpmlm-unilevel") . '</th>
    <th>' . __("Zip Code","wpmlm-unilevel") . '</th>
    <th>' . __("Mobile No","wpmlm-unilevel") . '</th>        
    <th>' . __("Email","wpmlm-unilevel") . '</th>
    <th>' . __("Date of Joining","wpmlm-unilevel") . '</th>
</tr>';

            $count = 1;

            foreach ($result as $res) {
                $sponsor_id = $res->user_parent_id;

                $res1 = wpmlm_get_user_details_by_id($sponsor_id);
                $data.='<tr><td>' . $count++ . '</td>
        <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
        <td>' . $res->user_login . '</td>
        <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
        <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>
        <td>' . ($res->user_address ? "$res->user_address" : "NA") . '</td>
        <td>' . ($res->user_zip ? "$res->user_zip" : "NA") . '</td>
        <td>' . ($res->user_mobile ? "$res->user_mobile" : "NA") . '</td>
        <td>' . $res->user_email . '</td>
        <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td></tr>';
            }
            '</table>';

            echo $data;
            exit();

            
        } else {
            echo 0;
            exit();
        }
    }
}

function wpmlm_ajax_joining_report() {
    if (isset($_POST['joining_report_nonce']) && wp_verify_nonce($_POST['joining_report_nonce'], 'joining_report')) {

        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";
        $result = wpmlm_get_all_user_details_by_date_join($start_date_1, $end_date_1);

        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '<div class="row row-bottom">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <div class="report-header">
                        <img src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        
                    </div>
                </div>
                <div class="col-sm-8">

                    <table class="report-header-right">
                        <tr height="20px">
                            <td  colspan="3" align="center">
                                <h1>
                                    <font face="Arial, Helvetica, sans-serif">
                                    <h3>' . $res->company_name . '</h3></font>
                                </h1>
                            </td>
                        </tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000"></font>' . $res->company_address . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Phone","wpmlm-unilevel") . ':</font> ' . $res->company_phone . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Email","wpmlm-unilevel") . ':</font> ' . $res->company_email . '</b></td></tr>
                    </table>
                </div>
                <div class="col-sm-2">
                    <b>' . __("Date","wpmlm-unilevel") . ': ' . date("Y-m-d") . '</b>
                </div>
            </div>
        </div>
        <hr />
        <h2 style="text-align: center;">' . __("Joining Report","wpmlm-unilevel") . '</h2>
        
            <h6 style="text-align: center;">' . $start_date . ' to ' . $end_date . '</h6>
            
            <table id="joining_report_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">

                                            
    <tr>
    <th>#</th>
    <th>' . __("Name","wpmlm-unilevel") . '</th>              
    <th>' . __("Username","wpmlm-unilevel") . '</th>
    <th>' . __("Sponsor Name","wpmlm-unilevel") . '</th>
    <th>' . __("Date of Birth","wpmlm-unilevel") . '</th>           
    <th>' . __("Email","wpmlm-unilevel") . '</th>
    <th>' . __("Date of Joining","wpmlm-unilevel") . '</th>
</tr>';

            $count = 1;
            foreach ($result as $res) {
                $sponsor_id = $res->user_parent_id;
                $res1 = wpmlm_get_user_details_by_id($sponsor_id);


                $data.='<tr><td>' . $count++ . '</td>
        <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
        <td>' . $res->user_login . '</td>
        <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
        <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>       
        <td>' . $res->user_email . '</td>
        <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td></tr>';
            }
            '</table>';
            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }



    if (isset($_POST['default_joining']) && ($_POST['default_joining'] == 'joining_report_all')) {

        $result = wpmlm_get_all_user_details_join();
        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '<div class="row row-bottom">
            <div class="col-sm-12">
                <div class="col-sm-2" >
                    <div class="report-header">
                        <img src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        
                    </div>
                </div>
                <div class="col-sm-8">

                    <table class="report-header-right">
                        <tr height="20px">
                            <td  colspan="3" align="center">
                                <h1>
                                    <font face="Arial, Helvetica, sans-serif">
                                    <h3>' . $res->company_name . '</h3></font>
                                </h1>
                            </td>
                        </tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000"></font>' . $res->company_address . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Phone","wpmlm-unilevel") . ':</font> ' . $res->company_phone . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Email","wpmlm-unilevel") . ':</font> ' . $res->company_email . '</b></td></tr>
                    </table>
                </div>
                <div class="col-sm-2">
                    <b>' . __("Date","wpmlm-unilevel") . ': ' . date("Y-m-d") . '</b>
                </div>
            </div>
        </div>
        <hr />
        <h2 style="text-align: center;">' . __("Joining Report","wpmlm-unilevel") . '</h2>
        
            
            
            <table id="joining_report_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">

                                            
    <tr>
    <th>' . __("No","wpmlm-unilevel") . '</th>
    <th>' . __("Name","wpmlm-unilevel") . '</th>              
    <th>' . __("Username","wpmlm-unilevel") . '</th>
    <th>' . __("Sponsor Name","wpmlm-unilevel") . '</th>
    <th>' . __("Date of Birth","wpmlm-unilevel") . '</th>           
    <th>' . __("Email","wpmlm-unilevel") . '</th>
    <th>' . __("Date of Joining","wpmlm-unilevel") . '</th>
</tr>';

            $count = 1;
            foreach ($result as $res) {
                $sponsor_id = $res->user_parent_id;
                $res1 = wpmlm_get_user_details_by_id($sponsor_id);
                $data.='<tr><td>' . $count++ . '</td>
        <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
        <td>' . $res->user_login . '</td>
        <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
        <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>       
        <td>' . $res->user_email . '</td>
        <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td></tr>';
            }
            '</table>';
            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }
}

function wpmlm_ajax_bonus_report() {
    if (isset($_POST['commission_report_nonce']) && wp_verify_nonce($_POST['commission_report_nonce'], 'commission_report')) {

        $start_date = sanitize_text_field($_POST['commission_start_date']);
        $end_date = sanitize_text_field($_POST['commission_end_date']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";
        $result = wpmlm_get_leg_amount_details($start_date_1, $end_date_1);
        $result1 = wpmlm_get_total_leg_amount($start_date_1, $end_date_1);
        $result2 = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '<div class="row row-bottom">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <div class="report-header">
                        <img src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result2->company_logo . ' align="left"  >
                        
                    </div>
                </div>
                <div class="col-sm-8">

                    <table class="report-header-right">
                        <tr height="20px">
                            <td  colspan="3" align="center">
                                <h1>
                                    <font face="Arial, Helvetica, sans-serif">
                                    <h3> ' . $result2->company_name . '</h3></font>
                                </h1>
                            </td>
                        </tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000"></font>' . $result2->company_address . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Phone","wpmlm-unilevel") . ':</font> ' . $result2->company_phone . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Email","wpmlm-unilevel") . ':</font> ' . $result2->company_email . '</b></td></tr>
                    </table>
                </div>
                <div class="col-sm-2">
                    <b>' . __("Date","wpmlm-unilevel") . ': ' . date("Y-m-d") . '</b>
                </div>
            </div>
        </div>
        <hr />
        <h2 style="text-align: center;">' . __("Bonus Report","wpmlm-unilevel") . '</h2>
        
            <h6 style="text-align: center;">' . $start_date . ' to ' . $end_date . '</h6>
            <table id="commission_report_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                                            
    <tr>
    <th>#</th>
    <th>' . __("Username","wpmlm-unilevel") . '</th>              
    <th>' . __("Fullname","wpmlm-unilevel") . '</th>
    <th>' . __("Amount Type","wpmlm-unilevel") . '</th>  
    <th>' . __("Total Amount","wpmlm-unilevel") . '</th>
</tr>';

            $count = 1;
            foreach ($result as $res) {
                $data.='<tr><td>' . $count++ . '</td>
        <td>' . $res->user_login . '</td>
        <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>        
        <td>' . ucwords(str_replace("_", " ", $res->amount_type)) . '</td>        
        <td>' . $result2->company_currency . ' ' . $res->total_amount . '</td></tr>';
            }
            $data.='<tr><td colspan="4" style="text-align: right;">Total Amount :</td><td>' . $result2->company_currency . ' ' . $result1->total_amount . '</td></tr>';
            '</table>';

            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }


    if (isset($_POST['default_commission']) && ($_POST['default_commission'] == 'commission_report_all')) {

        $result = wpmlm_get_leg_amount_details_all();
        $result1 = wpmlm_get_total_leg_amount_all();
        $result2 = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '<div class="row row-bottom">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <div class="report-header">
                        <img src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result2->company_logo . ' align="left"  >
                        
                    </div>
                </div>
                <div class="col-sm-8">

                    <table class="report-header-right">
                        <tr height="20px">
                            <td  colspan="3" align="center">
                                <h1>
                                    <font face="Arial, Helvetica, sans-serif">
                                    <h3> ' . $result2->company_name . '</h3></font>
                                </h1>
                            </td>
                        </tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000"></font>' . $result2->company_address . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Phone","wpmlm-unilevel") . ':</font> ' . $result2->company_phone . '</b></td></tr>
                        <tr height="20px"><td  colspan="3" align="center"><b><font color="#ff0000">' . __("Email","wpmlm-unilevel") . ':</font> ' . $result2->company_email . '</b></td></tr>
                    </table>
                </div>
                <div class="col-sm-2">
                    <b>' . __("Date","wpmlm-unilevel") . ': ' . date("Y-m-d") . '</b>
                </div>
            </div>
        </div>
        <hr />
        <h2 style="text-align: center;">' . __("Bonus Report","wpmlm-unilevel") . '</h2>
        
            
            <table id="commission_report_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                                            
    <tr>
    <th>' . __("No","wpmlm-unilevel") . '</th>
    <th>' . __("Username","wpmlm-unilevel") . '</th>              
    <th>' . __("Fullname","wpmlm-unilevel") . '</th>
    <th>' . __("Amount Type","wpmlm-unilevel") . '</th>  
    <th>' . __("Total Amount","wpmlm-unilevel") . '</th>
</tr>';

            $count = 1;
            foreach ($result as $res) {
                $data.='<tr><td>' . $count++ . '</td>
        <td>' . $res->user_login . '</td>
        <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>        
        <td>' . ucwords(str_replace("_", " ", $res->amount_type)) . '</td>        
        <td>' . $result2->company_currency . ' ' . $res->total_amount . '</td></tr>';
            }
            $data.='<tr><td colspan="4" style="text-align: right;">Total Amount :</td><td>' . $result2->company_currency . ' ' . $result1->total_amount . '</td></tr>';
            '</table>';

            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }
}

function wpmlm_ajax_user_details() {

    $user_id = $_GET['user_id'];
    session_start();
    $_SESSION['selected_user_id']= $user_id;
    $user_details = wpmlm_get_user_details($user_id);
    $user = get_user_by('id', $user_id);
    $parent_id = $user_details->user_parent_id;
    ?>
    <div class="panel-border col-md-12">
        <div id="exTab5" >
            <div class="col-md-3 ">
                <ul  class="nav nav-tabs tabs-right mlm-user-tabs">            
                    <li class="active"><a  href="#1c" data-toggle="tab"><?php _e('User Profile','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#2c" data-toggle="tab"><?php _e('E-wallet Details','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#3c" data-toggle="tab"><?php _e('Bonus Details','wpmlm-unilevel'); ?></a></li>
                    <li><a href="#4c" data-toggle="tab"><?php _e('Referral Details','wpmlm-unilevel'); ?></a></li>
                    <!-- <li><a href="#5c" data-toggle="tab"><?php// _e('Tax Account Details','wpmlm-unilevel'); ?></a></li> -->
                    <li><a href="#6c" data-toggle="tab"><?php _e('Comment History','wpmlm-unilevel'); ?></a></li>
                </ul>
            </div>

            <div class="tab-content clearfix col-md-9">

                <div class="tab-pane active" id="1c">
                    <div><?php echo wpmlm_user_profile_admin($user_id); ?></div>
                </div>
                <div class="tab-pane" id="2c">
                    <div><?php echo wpmlm_user_ewallet_details($user_id); ?></div>
                </div>
                <div class="tab-pane" id="3c">
                    <div><?php echo wpmlm_user_income_details($user_id); ?></div>
                </div>
                <div class="tab-pane" id="4c">
                    <div><?php echo wpmlm_user_referrals($user_id); ?></div>
                </div>
                <!-- <div class="tab-pane" id="5c">
                    <div><?php// echo wpmlm_user_tax_details($user_id); ?></div>
                </div> -->
                <div class="tab-pane" id="6c">
                    <div><?php echo wpmlm_user_comment_history($user_id); ?></div>
                </div>


            </div>
        </div>
    </div>
    <script>

        jQuery(".user-details-tab").click(function ($) {
            jQuery("#user-div").show();
            jQuery("#exTab5").hide();
            jQuery(".user-details").hide();


            


        });
    </script>
    <?php
    exit();
}

function wpmlm_ajax_session() {
    session_start();
    if (isset($_POST['session_pkg_id'])) {
        $session_pkg_id = intval($_POST['session_pkg_id']);
        $pkg = wpmlm_select_package_by_id($session_pkg_id);
        $_SESSION['package_name'] = $package->package_name;
        $_SESSION['session_pkg_id'] = $session_pkg_id;
        $package['package_name'] = $pkg->package_name;
        $package['package_price'] = $pkg->package_price;
        echo json_encode($package);
        exit();
    }
}

function wpmlm_ajax_user_check() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    $table_prefix = $wpdb->prefix;
    if (isset($_POST['username'])) {
        $username = sanitize_text_field($_POST['username']);
        $username = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$username'");
        if ($username) {
            _e('Sorry! Username already taken','wpmlm-unilevel');
        } else {
            echo '1';
        }
        exit();
    }

    if (isset($_POST['sponsor'])) {
        $sponsor = sanitize_text_field($_POST['sponsor']);
        $usersStatus = wpmlm_ifactiveUser($sponsor);        
        $sponsor = $wpdb->get_var("SELECT a.*,b.* FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id AND a.user_login = '" . $sponsor . "'");
        // echo $wpdb->last_query;die;
        // $user_id = get_current_user_id();
        // $the_user = get_user_by('login', $sponsor);
        
        
        if (!$sponsor || !$usersStatus) {
            _e('Sorry! The specified sponsor is not available for registration.','wpmlm-unilevel');
        } else {
           echo '1';
        }
        exit();
    }


    if (isset($_POST['email'])) {
        $user_email = sanitize_email($_POST['email']);
        if (email_exists($user_email)) {
            _e('Sorry! The Email Address is Already Registered.','wpmlm-unilevel');
        } else {
            echo '1';
        }
        exit();
    }


    if (isset($_POST['user_email'])) {

        $user_id = intval($_POST['user_id']);
        $the_user = get_user_by('ID', $user_id);
        $email = $the_user->user_email;

        $user_email = sanitize_email($_POST['user_email']);


        if ($email == $user_email) {
            echo '1';
            exit();
        } else {
            if (email_exists($user_email)) {
                echo '0';
                exit();
            } else {
                echo '1';
                exit();
            }
        }
    }
}

function wpmlm_auto_fill_user() {
    global $wpdb;
    if ((isset($_POST['query'])) && (isset($_POST['sponsor']) )) {


        $keyword = sanitize_text_field($_POST['query']);
        $sponsor = sanitize_text_field($_POST['sponsor']);
        $result = wpmlm_get_all_user_like_except_current($keyword, $sponsor);
        if (count($result) > 0) {
            foreach ($result as $res) {
                $users[] = $res->user_login;
            }
            echo json_encode($users);
            exit();
        }
    } else {


        $keyword = sanitize_text_field($_POST['query']);
        $result = wpmlm_get_all_user_like($keyword);
        if (count($result) > 0) {
            foreach ($result as $res) {
                $users[] = $res->user_login;
            }
            echo json_encode($users);
            exit();
        }
    }
}




function paymentCancelled() 
{
        
    if(!empty($this->session->userdata('from_replica'))) {
        $redirect_url = "replica/replica_register";
    } else {
        $redirect_url = "register/user_register";
    }
    
    $token = $this->input->get('token');
    
    $this->load->model('payment_model');
    $ppl_history_id = $this->payment_model->getPaypalPaymentHistoryIdFromToken($token);

    $this->payment_model->updatePaypalPaymentHistory($ppl_history_id, [
        'payer_id' => $this->input->get('payerID'),
        'status' => 'cancelled'
    ]);
    //$msg = lang('payment_cancelled');
    $msg = __('payment_cancelled','wpmlm-unilevel');
    $this->redirect($msg, $redirect_url, FALSE);
}

function payment_success() {

    if(!empty($this->session->userdata('from_replica'))) {
        $redirect_url = "replica/replica_register";
    } else {
        $redirect_url = "register/user_register";
    }

    $token = $this->input->get('token');
    $this->load->model('payment_model');
    $ppl_history_id = $this->payment_model->getPaypalPaymentHistoryIdFromToken($token);
    
    $this->load->model('payout_optional_model');
    $paypal_config = $this->payout_optional_model->getPaypalConfigDetails();

    $request = new OrdersCaptureRequest($token);
    $request->prefer('return=representation');

    try {
        if($paypal_config['mode'] == 'test') {
           $environment = new SandboxEnvironment($paypal_config['client_id'], $paypal_config['client_sec']);
        } else {
            $environment = new ProductionEnvironment($paypal_config['client_id'], $paypal_config['client_sec']);
        }

        $client = new PayPalHttpClient($environment);
        $response = $client->execute($request);

        if(isset($response->statusCode) && $response->statusCode == '201') {

            $this->payment_model->updatePaypalPaymentHistory($ppl_history_id, [
                'response' => json_encode($response),
                'payer_id' => $response->result->payer->payer_id,
                'status' => 'success'
            ]);

            $ppl_history = $this->payment_model->getPaypalPaymentHistoryDetails($ppl_history_id, [ 'user_id', 'amount', 'data' ]);

            $total_amount = $ppl_history['amount'];
            $regr = json_decode($ppl_history['data'], true);
            
            $module_status = $this->MODULE_STATUS;
            $pending_signup_status = $regr['approval_required'];
            $payment_type = $regr['payment_type'];
            
            $this->register_model->begin();
            $status = $this->register_model->confirmRegister($regr, $module_status, $pending_signup_status);  ;
            
            if ($status['status']) {
                
                $this->load->model('mail_model');
                $email_otp_sess_array = $this->session->userdata('verify_email');
                if ($email_otp_sess_array) {
                    $this->mail_model->updateEmailOTPStatus($email_otp_sess_array['otp_id'], $email_otp_sess_array['verified_time']);
                }
                
                $user_name = $status['user_name'];
                $user_id = $status['user_id'];
                $transaction_password = $pending_signup_status ? '' : $status['transaction_password'];

                $this->register_model->create_wp_user($user_id, $regr);

                if ($module_status['product_status'] == "yes") {
                    $insert_into_sales = $this->register_model->insertIntoSalesOrder($user_id, $regr['product_id'], $payment_type, $pending_signup_status);
                }

                $this->register_model->commit();

                $this->validation_model->insertUserActivity($this->LOG_USER_ID, 'New user registered', $user_id);

                // Employee Activity History
                if ($this->LOG_USER_TYPE == 'employee') {
                    $this->validation_model->insertEmployeeActivity($this->LOG_USER_ID, $this->LOG_USER_ID, 'user_register', 'New user registered', $pending_signup_status);
                }

                $this->session->unset_userdata(['inf_regr','inf_reg_post_array','from_replica']);

                $id_encode = $this->encrypt->encode($user_name);
                $id_encode = str_replace("/", "_", $id_encode);
                $user_name_encrypt = urlencode($id_encode);
                if ($pending_signup_status) {

                    $this->session->unset_userdata('file');

                    $msg = "<span><b>" . lang('sponsor_will_approve_reg_request') . "!</b> " . lang("User_Name") . ": {$user_name}";
                } else {
                    $this->session->unset_userdata('file');
                    $msg = "<span><b>" . lang('registration_completed_successfully') . "!</b> " . lang("User_Name") . ": {$user_name}</span>";
                }
                
                if($redirect_url == 'register/replica_register') {
                    $this->redirect($msg, "register/replica_preview/{$user_name_encrypt}", true);
                } else {
                    $this->redirect($msg, "register/preview/{$user_name_encrypt}", true);
                }
                
            } else {
                $this->register_model->rollback();
                $msg = lang('Registration failed');
                $this->redirect($msg, $redirect_url, FALSE);
            }

        } else {
            $this->payment_model->updatePaypalPaymentHistory($ppl_history_id, [
                'response' => json_encode($response),
                'payer_id' => $this->input->get('payerID'),
                'status' => 'failed'
            ]);
            $msg = lang('payment_failed');
            $this->redirect($msg, $redirect_url, FALSE);
        }

    } catch (HttpException $ex) {
        $this->payment_model->updatePaypalPaymentHistory($ppl_history_id, [
            'payer_id' => $this->input->get('payerID'),
            'status' => 'failed'
        ]);
        $msg = lang('payment_failed');
        $this->redirect($msg, $redirect_url, FALSE);
    }
}

function wpmlm_contact_form_registration() {

    session_start();
    global $wpdb;
    global $current_user;
    $table_prefix = $wpdb->prefix;
     
    $result = wpmlm_get_general_information();
    $reg_pack_type = $result->registration_type;
    $current_user_name = $current_user->user_login;
    $reg_amt = $result->registration_amt;

    if (isset($_POST['wpmlm_registration_nonce']) && wp_verify_nonce($_POST['wpmlm_registration_nonce'], 'wpmlm_registration')) {

        if($_POST['user_registration_type'] == 'paypal' ) {

            $accessToken = getAccessToken();
            
            $response = PaypalApiCall($accessToken, 'v2/checkout/orders', [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                      "amount" => [
                        "currency_code" => "USD",
                        "value" => "100.00"
                      ]
                    ]
                ],
                'return_url' => home_url('payment_success'),
                'cancel_url' => home_url('paymentcancelled')
            ]);

            echo '<pre>';print_r($response );die;
            if(isset($response->status) && $response->status == 'CREATED') {
                $payment_link = $response->links[1]->href;
                echo json_encode([
                    'status' => true,
                    'link' => $payment_link
                ]);
                exit();
            } else {

                echo json_encode([
                    'status' => false
                ]);
                exit();
            }

            echo '<pre>';print_r($response );

        }
            

        $sponsor = sanitize_text_field($_POST['sname']);
        $user_first_name = sanitize_text_field($_POST['fname']);
        $user_second_name = sanitize_text_field($_POST['lname']);
        $user_dob = sanitize_text_field($_POST['date_of_birth']);
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $email = sanitize_text_field($_POST['email']);
        $user_mobile = sanitize_text_field($_POST['contact_no']);
        $user_address = sanitize_text_field($_POST['address1']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_registration_type = sanitize_text_field($_POST['user_registration_type']);

        $the_user = get_user_by('login', $sponsor);
        $user_parent_id = $the_user->ID;

        $invalid_usernames = array('admin');
        // $username = sanitize_user($username);

        $user_level = wpmlm_get_user_level_by_parent_id($user_parent_id);
        $user_ref = wp_create_user($username, $password, $email );
        $_SESSION['user_ref'] = $user_ref;

        // First get the user details
        $user = get_user_by('login', $username );
         
        // If no error received, set the WP Cookie
        if ( !is_wp_error( $user ) ) {

            wp_clear_auth_cookie();
            wp_set_current_user ( $user->ID ); // Set the current user detail
            wp_set_auth_cookie  ( $user->ID ); // Set auth details in cookie

        } else {

            $message = "Failed to log in";
            
        }
         
        // echo $message;

        $user_info = get_userdata($user_ref);
        $user_email = $user_info->user_email;
        $_SESSION['user_email'] = $user_email;

        $user_details = array(
            'user_ref_id' => $user_ref,
            'user_parent_id' => $user_parent_id,
            'user_first_name' => $user_first_name,
            'user_second_name' => $user_second_name,
            'user_address' => $user_address,
            'user_city' => $user_city,
            'user_state' => $user_state,
            'user_country' => $user_country,
            'user_zip' => $user_zip,
            'user_mobile' => $user_mobile,
            'user_email' => $user_email,
            'user_dob' => $user_dob,
            'user_level' => $user_level,
            'user_registration_type' => $user_registration_type,
            'join_date' => date("Y-m-d H:i:s"),
            'user_status' => 1,
            'package_id' => $_SESSION['session_pkg_id']
        );


        $_SESSION['user_details'] = $user_details;
        if ($user_registration_type == 'free_join') {
            wp_update_user(array('ID' => $user_ref, 'role' => 'contributor'));
            $success_msg = wpmlm_insert_user_registration_details($user_details);

            $res = array();

            if ($success_msg) {
                if ($reg_amt != 0) {
                    wpmlm_insert_leg_amount($user_ref, $_SESSION['session_pkg_id']);
                }
                $tran_pass = wpmlm_getRandTransPasscode(8);
                $hash_tran_pass = wp_hash_password($tran_pass);
                $tran_pass_details = array(
                    'user_id' => $user_ref,
                    'tran_password' => $hash_tran_pass
                );
                wpmlm_insert_tran_password($tran_pass_details);
                wpmlm_insertBalanceAmount($user_ref);

                unset($_SESSION['session_pkg_id']);

                $current_url = admin_url();
               
                $res['msg'] = 1;
                $res['activation_link'] = $current_url;

            } else {

                $res['msg'] = 0;
            }

            echo json_encode($res);
            exit();
        }
    }
}

function wpmlm_registration_page(){
    
    if (isset($_POST['reg_submit']) && wp_verify_nonce($_POST['wpmlm_registration_nonce'], 'wpmlm_registration')) {


        $paypal_result = wpmlm_get_paypal_details();
        // define('PAYPAL_BASEURL', 'https://api.sandbox.paypal.com');
        define('PAYPAL_CNT_ID', $paypal_result->paypal_client_id);
        define('PAYPAL_CNT_SEC', $paypal_result->paypal_client_secret);


        session_start();
        $result = wpmlm_get_general_information();
        $reg_pack_type = $result->registration_type;
        $current_user_name = $current_user->user_login;
        $reg_amt = $result->registration_amt;
        $reg_amt_currency = $result->company_currency;

        global $post;
        $post_slug = $post->post_name;
        $redirect_url = home_url($post_slug);
        $paypal_congig_mode = $paypal_result->paypal_mode;

        $sponsor = sanitize_text_field($_POST['sname']);
        $user_first_name = sanitize_text_field($_POST['fname']);
        $user_second_name = sanitize_text_field($_POST['lname']);
        $user_dob = sanitize_text_field($_POST['date_of_birth']);
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $email = sanitize_text_field($_POST['email']);
        $user_mobile = sanitize_text_field($_POST['contact_no']);
        $user_address = sanitize_text_field($_POST['address1']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_registration_type = sanitize_text_field($_POST['user_registration_type']);
        $package_select = sanitize_text_field($_POST['package_select']);

        $pkg = wpmlm_select_package_by_id($package_select);

        $_SESSION['package_name'] = $pkg->package_name;
        if($reg_pack_type != 'with_out_package'){
            $_SESSION['package_price'] = $pkg->package_price;
        } else {
            $_SESSION['package_price'] = $reg_amt;
        }

        $_SESSION['session_pkg_id'] = $package_select;

        $the_user = get_user_by('login', $sponsor);
        $user_parent_id = $the_user->ID;

        $invalid_usernames = array('admin');
        $username = sanitize_user($username);

        $user_level = wpmlm_get_user_level_by_parent_id($user_parent_id);
        // $user_ref = get_current_user_id();
        if ( username_exists( $username ) || email_exists( $email ) ) {
            return false;
            wp_redirect($redirect_url);
            exit();
        }
        $user_ref = wp_create_user($username, $password, $email );
        $_SESSION['user_ref'] = $user_ref;

        $user_info = get_userdata($user_ref);
        $user_email = $user_info->user_email;
        $_SESSION['user_email'] = $user_email;

        $user_details = array(
            'user_ref_id' => $user_ref,
            'user_parent_id' => $user_parent_id,
            'user_first_name' => $user_first_name,
            'user_second_name' => $user_second_name,
            'user_address' => $user_address,
            'user_city' => $user_city,
            'user_state' => $user_state,
            'user_country' => $user_country,
            'user_zip' => $user_zip,
            'user_mobile' => $user_mobile,
            'user_email' => $user_email,
            'user_dob' => $user_dob,
            'user_level' => $user_level,
            'user_registration_type' => $user_registration_type,
            'join_date' => date("Y-m-d H:i:s"),
            'user_status' => 1,
            'package_id' => $_SESSION['session_pkg_id']
        );

        $_SESSION['user_details'] = $user_details;
        $current_url = site_url('/');
        if ($user_registration_type == 'free_join') {
            wp_update_user(array('ID' => $user_ref, 'role' => 'contributor'));
            $success_msg = wpmlm_insert_user_registration_details($user_details);
            //print_r($success_msg);die('fgdfw');
            if ($success_msg) {
                if ( ($reg_amt != 0) || ($reg_pack_type == 'with_package')) {
                    wpmlm_insert_leg_amount($user_ref, $_SESSION['session_pkg_id']);
                   
                }
                $tran_pass = wpmlm_getRandTransPasscode(8);
                $hash_tran_pass = wp_hash_password($tran_pass);
                $tran_pass_details = array(
                    'user_id' => $user_ref,
                    'tran_password' => $hash_tran_pass
                );
                wpmlm_insert_tran_password($tran_pass_details);
                wpmlm_insertBalanceAmount($user_ref);
                //sendMailRegistration($user_email, $username, $password, $user_first_name, $user_second_name);
                //sendMailTransactionPass($user_email, $tran_pass);
                unset($_SESSION['user_ref']);
                unset($_SESSION['user_details']);
                unset($_SESSION['session_pkg_id']);
                unset($_SESSION['user_email']);
                unset($_SESSION['package_price']);
                unset($_SESSION['package_name']);
                unset($_SESSION['sponsor']);
                unset($_SESSION['.']);

                // First get the user details
                $user = get_user_by('login', $username );
                // If no error received, set the WP Cookie
                if ( !is_wp_error( $user ) ) {

                    wp_clear_auth_cookie();
                    wp_set_current_user ( $user->ID ); // Set the current user detail
                    wp_set_auth_cookie  ( $user->ID ); // Set auth details in cookie

                } else {

                    $message = "Failed to log in";
                    
                }

                $dash_result = wpmlm_get_general_information();
                $dash_slug_id = get_post($dash_result->user_dash); 
                $dash_slug = $dash_slug_id->post_name;
                $reg_slug_id = get_post($dash_result->user_registration); 
                $reg_slug = $reg_slug_id->post_name;

                $reg_msg = base64_encode('Registration Completed Successfully!');
                header('Content-Type: application/json');
                echo json_encode([
                            'redirect_link' => "{$current_url}{$dash_slug}&?reg_status={$reg_msg}",
                            'payment_type' => 'free_join',
                        ]);
                exit();
                //wp_redirect();

            } else {

                $reg_msg = base64_encode('Sorry! Registration Failed, Please try again');
                wp_redirect($current_url .''. $reg_slug.'&?reg_failed=' . $reg_msg);
                exit();

            }

        } else {

            if($_POST['user_registration_type'] == 'paypal' ) {

                if($paypal_congig_mode == 'sandbox') {
                    $environment = new SandboxEnvironment(PAYPAL_CNT_ID, PAYPAL_CNT_SEC);
                } else {
                    $environment = new ProductionEnvironment(PAYPAL_CNT_ID, PAYPAL_CNT_SEC);
                }
                
                $client = new PayPalHttpClient($environment);

                
                $user_id = NULL;
                $total_amount = $_SESSION['package_price'];
                $currency = $paypal_result->paypal_currency;
                
                if($total_amount <= 0) {
                    wp_redirect($redirect_url);
                }

                $reg_result = wpmlm_get_general_information();
                $reg_slug_id = get_post($reg_result->user_registration); 
                $reg_slug = $reg_slug_id->post_name;
                //my_trim_function( $trim_me );


                $request = new OrdersCreateRequest();
                $request->prefer('return=representation');
                $request->body = [
                    "intent" => "CAPTURE",
                    "purchase_units" => [[
                        "amount" => [
                            "value" => $total_amount,
                            "currency_code" => $currency
                        ]
                    ]],
                    "application_context" => [
                        "cancel_url" => home_url($reg_slug.'?payment_method=paypal&status=cancel'),
                        "return_url" => home_url($reg_slug.'?payment_method=paypal&status=return')
                    ] 
                ];

                try {

                    $response = $client->execute($request);
                    
                    if(isset($response->statusCode) && $response->statusCode == '201') {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'payment_link' => $response->result->links[1]->href,
                            'payment_type' => 'paypal',
                        ]);
                        exit();
                    } else {
                        wp_redirect($redirect_url);
                    }
                } catch (HttpException $ex) {
                    wp_redirect($redirect_url);
                    echo $ex->statusCode;
                    print_r($ex->getMessage());
                }    

            }
        }
    }
   
}

//comments
function wpmlm_ajax_comment_history(){
    global $wpdb;
    $table_name = $wpdb->prefix . "comment_history";
    $userid = sanitize_text_field($_POST['userid']);
    
    if (isset($_POST['comment_form_nonce']) && wp_verify_nonce($_POST['comment_form_nonce'], 'comment_form')) {
        $admin_name = sanitize_text_field($_POST['admin_name']);
        $comment = sanitize_text_field($_POST['comment']);
        $date = date('Y-m-d H:i:s');

        $user_details = array(
            'user_id' => $userid,
            'date' => $date,
            'admin_name' => $admin_name,
            'comment' => $comment
        ); 
        $comment_insert = $wpdb->insert($table_name, $user_details);

        if($comment_insert){
             _e("Comment added Successfully","wpmlm-unilevel");
             exit();
        }
        else{
            _e("OOPS!! Try again","wpmlm-unilevel");
            exit();
        }
    }
}

function wpmlm_ajax_comment_delete()
{
    // global $wpdb;
    $did = $_POST['d_id']; 
    $delete_data = wpajax_delete_comment($did);
    if ($delete_data) {
     echo 'data deleted successfully';
     exit;
    }
    else{
        echo 'data deletion failed';
        exit;
    }
}

function wpmlm_ajax_comment_user_delete()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "comment_history";
    $did = $_POST['d_id']; 
    $user_details = array(
        'status' => '1',
    );
    $condition = array('id' => $did);

    $change_status = $wpdb->update($table_name, $user_details, $condition);
    if ($change_status) {
     echo 'comment deleted successfully';
     exit;
    }
    else{
        echo 'try again';
        exit;
    }
}

function wpmlm_ajax_comment_edit(){
        $uid = $_POST['uid'];
        $result1 = get_single_comment($uid);
        if($result1){
            $content1 = '';
            $content1 .= '<form class="form" id="ajax_form_update" method="post">';
            // foreach ( $result1 as $print ){
                $content1 .= '
                <input type="hidden" name="table_id" id="table_id" value="'.$result1->id.'">
                    <input type="hidden" name="userid" id="userid" value="'.$result1->user_id.'">
                        <div class="form-group">
                     <label class="control-label col-md-3" for="adminname">'. __("Admin Name","wpmlm-unilevel").':</label>
                     <div class="col-md-7">
                                <input type="text" class="cmt-dt" name="adminname" id="adminname" style="border: none;" value="'.$result1->admin_name.'">
                            </div>
                        </div>
                        <div class="form-group">
                     <label class="control-label col-md-3" for="comments">'. __("Comment","wpmlm-unilevel").':</label>
                     <div class="col-md-7">
                                <textarea class="cmt-dt" name="comments" id="comments" style="width: 100%;margin-top: 3%;" > '.$result1->comment.' </textarea>
                            </div>
                        </div>
                <div class="col-md-7" style="margin-left:25%;margin-top:1%;">
                    <button type="submit" id="form_up" class="btn btn-danger-sm ">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>';
                 
             // }

    $content1 .='</form>
    <script>
        
        $("#ajax_form_update").submit(function () {
        var formData = new FormData(this);
        
        formData.append("action","wpmlm_ajax_comment_update");

                    
     
                    isValid = true;
                  
                    if (isValid) {
                        
                        $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: formData,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                
                                //alert(data);
                                $("#comment_printarea").load(" #comment_tableview");
                            }
                        });
                    return false;
                    }

                });</script>';

        echo $content1; exit();
    }
}


function wpmlm_ajax_comment_update()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "comment_history";
    $table_id= $_POST['table_id'];
    $admin_name = sanitize_text_field($_POST['adminname']);
    $comment = sanitize_text_field($_POST['comments']);
    $date = date('Y-m-d H:i:s');
    $user_details = array(
        'date' => $date,
        'admin_name' => $admin_name,
        'comment' => $comment 
    );
    $condition = array('id' => $table_id);
    $comment_updt = $wpdb->update($table_name, $user_details, $condition);


    if($comment_updt){
        echo "data Updated successfully";
       exit();
    }
    else{
        echo "Oops..!! Try Again";
       exit();
    }
    exit();
    
}

function wpmlm_ajax_document_vault(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    $username = sanitize_text_field($_POST['user_name']);
    $usersStatus = wpmlm_ifactiveUser($username);
    $vault_username = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$username'");
        if (!$vault_username || !$usersStatus) {
            _e("Sorry! The specified user is not available","wpmlm-unilevel");
            exit();
        }

    if (!empty($_FILES['upload_file']['name'])) {
        $uploaddir = WP_MLM_PLUGIN_DIR . '/uploads/';
        $file = $uploaddir . basename($_FILES['upload_file']['name']);


        if (file_exists($file)) {
            $duplicate_filename = TRUE;
            $i = 0;
            while ($duplicate_filename) {
                $filename_data = explode(".", $_FILES['upload_file']['name']);
                $new_filename = $filename_data[0] . "_" . $i . "." . $filename_data[1];
                $_FILES['upload_file']['name'] = $new_filename;
                $file = $uploaddir . basename($_FILES['upload_file']['name']);
                if (file_exists($file)) {
                    $i++;
                } else {
                    $duplicate_filename = FALSE;
                }
            }
        }

        $upload_file = $_FILES['upload_file']['name'];
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $file)) {
            $msg .='';
        } else {
            $msg .="Image Uploading Error";
        }
    } else {
        $upload_file = sanitize_text_field($_POST['image']);
    }
    if ($upload_file == '') {
        _e("OOPS!! Try again","wpmlm-unilevel");
    }
    

    if (isset($_POST['document_add_vault_nonce']) && wp_verify_nonce($_POST['document_add_vault_nonce'], 'document_add_vault')) {
        //$docfile = sanitize_text_field($_POST['upload_file']);
        $caption = sanitize_text_field($_POST['caption']);

        $document = array(
            'username' => $vault_username,
            'caption' => $caption,
            'upload' => $upload_file,
        ); 
        $upload1 = upload_vault_doc($document);
        //echo $upload1;exit;

        if($upload1){
            //echo "string";

             _e("File uploaded successfully","wpmlm-unilevel");
             exit;
        }
        else{
            _e("OOPS!! Try again","wpmlm-unilevel");
            exit();
        }
    }
}

function wpmlm_ajax_activate_user(){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $user_id = $_POST['tgl_id'];
    $current_sts = $_POST['user_sts'];
        
    $status_details = array('active_inactive' => $current_sts );
    $condition = array('user_ref_id' => $user_id);
    $status_updt = $wpdb->update($table_name, $status_details, $condition);


    if($status_updt){
        echo "success";
       exit();
    }
    else{
        echo "Oops..!! Try Again";
       exit();
    }
    exit();

}