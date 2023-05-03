<?php
require (WP_MLM_PLUGIN_DIR . '/gateway/paypal-sdk-v2/vendor/autoload.php');
use PayPalCheckoutSdk\Core\PayPalHttpClient;
        use PayPalCheckoutSdk\Core\SandboxEnvironment;
        use PayPalCheckoutSdk\Core\ProductionEnvironment;
        use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
        use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
class WPMLM_Payment_Method{

  public function wpmlm_paypal_method(){
    
    session_start();
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    $payerID = isset($_GET['PayerID']) ? $_GET['PayerID'] : '';
    if ($token != '' && $payerID != '') {

        $request = new OrdersCaptureRequest($token);
        $request->prefer('return=representation');
        $result = wpmlm_get_general_information();
        $reg_pack_type = $result->registration_type;
        $reg_amt = $result->registration_amt;

        $user_ref = $_SESSION['user_ref'];
        $current_url = admin_url();
        
        try {
            $paypal_result = wpmlm_get_paypal_details();
            // define('PAYPAL_BASEURL', 'https://api.sandbox.paypal.com');
            define('PAYPAL_CNT_ID', $paypal_result->paypal_client_id);
            define('PAYPAL_CNT_SEC', $paypal_result->paypal_client_secret);
            $paypal_congig_mode = $paypal_result->paypal_mode;
            
            if($paypal_congig_mode == 'sandbox') {
                $environment = new SandboxEnvironment(PAYPAL_CNT_ID, PAYPAL_CNT_SEC);
            } else {
                $environment = new ProductionEnvironment(PAYPAL_CNT_ID, PAYPAL_CNT_SEC);
            }
            $client = new PayPalHttpClient($environment);
            $response = $client->execute($request);


            if(isset($response->statusCode) && $response->statusCode == '201') {

                if ($_SESSION['user_details']) {


                    wp_update_user(array('ID' => $user_ref, 'role' => 'contributor'));
                    $success_msg = wpmlm_insert_user_registration_details($_SESSION['user_details']);
                   
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
                        //sendMailRegistration($_SESSION['user_email'],$_SESSION['user_name'],$_SESSION['password'],$_SESSION['user_first_name'],$_SESSION['user_second_name']);                    
                        //sendMailTransactionPass($_SESSION['user_email'], $tran_pass);

                        unset($_SESSION['user_ref']);
                        unset($_SESSION['user_details']);
                        unset($_SESSION['session_pkg_id']);
                        unset($_SESSION['user_email']);
                        unset($_SESSION['package_price']);
                        unset($_SESSION['package_name']);
                        unset($_SESSION['.']);

                        $user_info = get_userdata($user_ref);
                        $username = $user_info->user_login;
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

                        
                        $reg_msg = base64_encode('Registration Completed Successfully!');
                        wp_redirect($current_url . 'admin.php?page=mlm-user-settings&reg_status=' . $reg_msg);
                        exit();
                    }
                }

            }

        } catch (HttpException $ex) {
            
            unset($_SESSION['user_ref']);
            unset($_SESSION['user_details']);
            unset($_SESSION['session_pkg_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['package_price']);
            unset($_SESSION['package_name']);
            unset($_SESSION['.']);

            $reg_msg = base64_encode('Sorry! Registration Failed, Please try again');
            wp_redirect($current_url . 'admin.php?page=mlm-user-settings&reg_failed=' . $reg_msg);
            exit();
        }
    }

    $status = isset($_GET['status']) ? $_GET['status'] : '';
    if ($status == 'cancel' && $token != '') {
        if (isset($_SESSION['user_ref'])) {

            require_once(ABSPATH.'wp-admin/includes/user.php');
            $user_ref = $_SESSION['user_ref'];
            wp_delete_user( $user_ref );
            
            unset($_SESSION['user_ref']);
            unset($_SESSION['user_details']);
            unset($_SESSION['session_pkg_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['package_price']);
            unset($_SESSION['package_name']);
            unset($_SESSION['.']);
            
        }
        
        echo '<script type="text/javascript">
        setTimeout(function () {
            window.location.href = "'.$redirect_url.'";
        }, 5000);
        </script>';
        
    }
    }

}