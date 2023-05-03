<?php

function wpmlm_admin_scripts() {
    wp_enqueue_style('wp-mlm-bootstrap-css', plugins_url('css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('wp-mlm-font-awesome-css', plugins_url('css/font-awesome.min.css', __FILE__));
    wp_enqueue_style('wp-mlm-orgchart-style-css', plugins_url('css/orgchart-style.css', __FILE__));
    wp_enqueue_style('orgchart-css', plugins_url('css/jquery.orgchart.css', __FILE__));
    wp_enqueue_style('wp-mlm-datepicker', plugins_url('css/datepicker.css', __FILE__));
    wp_enqueue_style('wp-mlm-dataTables-css', plugins_url('css/dataTables.bootstrap.min.css', __FILE__));    
    wp_enqueue_script('wp-mlm-jquery-js', plugins_url('/js/jquery.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-js', plugins_url('/js/bootstrap.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-datepicker', plugins_url('/js/bootstrap-datepicker.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-session-js', plugins_url('/js/jquery.session.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-orgchart-js', plugins_url('/js/jquery.orgchart.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-dataTables', plugins_url('/js/jquery.dataTables.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_style('wp-mlm-jstree-css', plugins_url('jstree/dist/themes/default/style.min.css', __FILE__));
    wp_enqueue_script('wp-mlm-jstree-js', plugins_url('/jstree/dist/jstree.min.js', __FILE__), array(
        'jquery'
    ));
    wp_enqueue_script('wp-mlm-bootstrap-dataTables', plugins_url('/js/dataTables.bootstrap.min.js', __FILE__), array( 'jquery' ));

    wp_enqueue_script('wp-mlm-chart-js', plugins_url('/js/Chart.min.js', __FILE__), array( 'jquery' ));


    wp_enqueue_style('admin-wp-mlm-style', plugins_url('css/style.css', __FILE__));
    wp_register_script('wp-mlm-my-script', plugins_url('/js/custom.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-my-script');
    wp_localize_script('wp-mlm-my-script', 'path', array('pluginsUrl' => plugins_url(WP_MLM_PLUGIN_NAME),));
    wp_localize_script("wp-mlm-my-script", "site", array("siteUrl" => site_url()));
}




function wpmlm_user_shortcode() {
    
    echo '<div class="col-md-12" id="mlm-main-div">' . wpmlm_user_area() . '</div>';
}

function my_scripts_method(){
    wp_enqueue_style('wp-mlm-bootstrap-css', plugins_url('/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('wp-mlm-font-awesome-css', plugins_url('/css/font-awesome.min.css', __FILE__));  
    wp_enqueue_script('wp-mlm-jquery-js', plugins_url('/js/jquery.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_style('wp-mlm-orgchart-style-css', plugins_url('css/orgchart-style.css', __FILE__));
    wp_enqueue_style('orgchart-css', plugins_url('/css/jquery.orgchart.css', __FILE__));
    wp_enqueue_style('wp-mlm-datepicker', plugins_url('/css/datepicker.css', __FILE__));
    wp_enqueue_style('wp-mlm-dataTables-css', plugins_url('/css/dataTables.bootstrap.min.css', __FILE__));
    wp_enqueue_script('wp-mlm-bootstrap-js', plugins_url('/js/bootstrap.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-datepicker', plugins_url('/js/bootstrap-datepicker.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-session-js', plugins_url('/js/jquery.session.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-orgchart-js', plugins_url('/js/jquery.orgchart.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-dataTables', plugins_url('/js/jquery.dataTables.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-dataTables', plugins_url('/js/dataTables.bootstrap.min.js', __FILE__), array( 'jquery' ));

    wp_enqueue_script('wp-mlm-chart-js', plugins_url('/js/Chart.min.js', __FILE__), array( 'jquery' ));


    wp_enqueue_style('admin-wp-mlm-style', plugins_url('css/style.css', __FILE__));
    wp_register_script('wp-mlm-my-script', plugins_url('/js/custom.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-my-script');
    wp_localize_script('wp-mlm-my-script', 'path', array('pluginsUrl' => plugins_url(WP_MLM_PLUGIN_NAME),));
    wp_localize_script("wp-mlm-my-script", "site", array("siteUrl" => site_url()));
}

add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

function wpmlm_login_style() {
    echo '<style type="text/css">
    #login #nav a{
    background: #0073aa !important;
    padding: 10px !important;
    color:#fff !important;
    }
    .login #nav {text-align: center!important;}
        #login #nav a {    background: #2e85ba !important;
    border-radius: 4px;}
        #login #nav a:hover {background-color: #2e85ba !important;
    border: 1px solid #006799 !important;}
</style>';
}

function wpmlm_custom_loginlogo() {

    $result = wpmlm_get_general_information();
    if ($result->site_logo == 'active') {
        echo '<style type="text/css">
#login h1 a {background-image: url(' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result->company_logo . ') !important; }
    
</style>';
    }
}

function wpmlm_register_menu() {
    if (current_user_can('subscriber') || (current_user_can('contributor'))) {
        add_action('admin_menu', 'wpmlm_admin_actions_user');
    } else {
        add_action('admin_menu', 'wpmlm_admin_actions');
    }
}

function wpmlm_admin_settings() {
    include('wp-mlm-admin.php');
}

function wpmlm_user_settings() {
    include('wp-mlm-user.php');
}

function wpmlm_admin_actions() {
    $icon_url = plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/icon-01.png";
    add_menu_page('WP MLM ADMIN', 'WP MLM', 'manage_options', 'mlm-admin-settings', 'wpmlm_admin_settings', $icon_url);
    
}

function wpmlm_admin_actions_user() {
    $icon_url = plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/icon-01.png";
    add_menu_page('WP MLM ADMIN', 'WP MLM', 'contributor', 'mlm-user-settings', 'wpmlm_user_settings', $icon_url);
}

add_action('admin_init', 'wpmlm_remove_menu_pages');

function wpmlm_remove_menu_pages() {

    global $user_ID;

    if (current_user_can('contributor') || (current_user_can('subscriber'))) {
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php');
   remove_menu_page('plugins.php');
   remove_menu_page('themes.php');
   remove_menu_page('tools.php');
   remove_menu_page('nav-menus.php');
   remove_menu_page('widgets.php');
   remove_menu_page('user-new.php');
   remove_menu_page('customize.php');
   remove_menu_page( 'woocommerce' );
   remove_menu_page( 'admin.php?page=wc-admin&path=%2Fanalytics%2Frevenue' );
   remove_menu_page( 'wc-admin' );
   remove_menu_page( 'edit.php?post_type=product' );
   remove_menu_page( 'edit.php?post_type=page' );
   remove_menu_page( 'options-privacy.php' );
   remove_menu_page( 'edit.php?post_type=elementor_library&tabs_group=library' );
   remove_menu_page( 'admin.php?page=elementor' );
   remove_menu_page( 'elementor' );
   remove_menu_page( 'admin.php?page=yith_wcqv_panel' );
   remove_menu_page( 'yith_wcqv_panel' );
   remove_menu_page( 'edit.php?post_type=elementor_library' );


   remove_action( 'welcome_panel', 'wp_welcome_panel' );
    // Remove the rest of the dashboard widgets
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'health_check_status', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
    remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box( 'themebeez_toolkit_dashboard_blog_feeds', 'dashboard', 'normal');
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box( 'yith_dashboard_products_news', 'dashboard', 'normal');
    remove_meta_box( 'yith_dashboard_blog_news', 'dashboard', 'normal');
    remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');

        
        
    }
}


function wpmlm_admin_notice() {
    global $pagenow;

    if (current_user_can('administrator')) {
        if ($pagenow == 'index.php') {

            echo '<div class="notice notice-info is-dismissible">
          <p>';?><?php _e('Click','wpmlm-unilevel') .'<a href="admin.php?page=mlm-admin-settings">';?><?php _e(' here','wpmlm-unilevel') .'</a>';?><?php _e(' to view the WP MLM Dashboard','wpmlm-unilevel') . '</p>
         </div>';
        }
    } else {

        if ($pagenow == 'index.php') {

            echo '<div class="notice notice-info is-dismissible">
          <p>';?><?php _e('Click','wpmlm-unilevel') .'<a href="admin.php?page=mlm-user-settings">';?><?php _e(' here','wpmlm-unilevel') .'</a>';?><?php _e(' to view the WP MLM Dashboard','wpmlm-unilevel') . '</p>
         </div>';
        }
    }
}

add_action('admin_notices', 'wpmlm_admin_notice');
add_filter('pre_option_default_role', function($default_role){
    return 'contributor'; 
});


add_action('wp_head', 'wpmlm_ajaxurl');

function wpmlm_ajaxurl() {
   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action( 'wp_ajax_wpmlm_ajax_general_settings', 'wpmlm_ajax_general_settings' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_general_settings', 'wpmlm_ajax_general_settings' );
add_action( 'wp_ajax_wpmlm_ajax_ewallet_management', 'wpmlm_ajax_ewallet_management' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_ewallet_management', 'wpmlm_ajax_ewallet_management' );
add_action( 'wp_ajax_wpmlm_auto_fill_user', 'wpmlm_auto_fill_user' );
add_action( 'wp_ajax_nopriv_wpmlm_auto_fill_user', 'wpmlm_auto_fill_user' );
add_action( 'wp_ajax_wpmlm_level_bonus', 'wpmlm_level_bonus' );
add_action( 'wp_ajax_nopriv_wpmlm_level_bonus', 'wpmlm_level_bonus' );
add_action( 'wp_ajax_wpmlm_ajax_transaction_password', 'wpmlm_ajax_transaction_password' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_transaction_password', 'wpmlm_ajax_transaction_password' );
add_action( 'wp_ajax_wpmlm_ajax_payment_option', 'wpmlm_ajax_payment_option' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_payment_option', 'wpmlm_ajax_payment_option' );
add_action( 'wp_ajax_wpmlm_ajax_package_settings', 'wpmlm_ajax_package_settings' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_package_settings', 'wpmlm_ajax_package_settings' );
add_action( 'wp_ajax_wpmlm_ajax_profile_report', 'wpmlm_ajax_profile_report' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_profile_report', 'wpmlm_ajax_profile_report' );
add_action( 'wp_ajax_wpmlm_ajax_joining_report', 'wpmlm_ajax_joining_report' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_joining_report', 'wpmlm_ajax_joining_report' );
add_action( 'wp_ajax_wpmlm_ajax_bonus_report', 'wpmlm_ajax_bonus_report' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_bonus_report', 'wpmlm_ajax_bonus_report' );
add_action( 'wp_ajax_wpmlm_ajax_user_details', 'wpmlm_ajax_user_details' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_user_details', 'wpmlm_ajax_user_details' );
add_action( 'wp_ajax_wpmlm_ajax_user_profile', 'wpmlm_ajax_user_profile' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_user_profile', 'wpmlm_ajax_user_profile' );
add_action( 'wp_ajax_wpmlm_ajax_session', 'wpmlm_ajax_session' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_session', 'wpmlm_ajax_session' );
add_action( 'wp_ajax_wpmlm_ajax_user_check', 'wpmlm_ajax_user_check' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_user_check', 'wpmlm_ajax_user_check' );
add_action( 'wp_ajax_wpmlm_contact_form_registration', 'wpmlm_contact_form_registration' );
add_action( 'wp_ajax_nopriv_wpmlm_contact_form_registration', 'wpmlm_contact_form_registration' );
add_action( 'wp_ajax_wpmlm_registration_page', 'wpmlm_registration_page' );
add_action( 'wp_ajax_nopriv_wpmlm_registration_page', 'wpmlm_registration_page' );
add_action( 'wp_ajax_wpmlm_ajax_tax_details', 'wpmlm_ajax_tax_details' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_tax_details', 'wpmlm_ajax_tax_details' );
add_action( 'wp_ajax_wpmlm_ajax_comment_history', 'wpmlm_ajax_comment_history' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_comment_history', 'wpmlm_ajax_comment_history' );
add_action( 'wp_ajax_wpmlm_ajax_comment_delete', 'wpmlm_ajax_comment_delete' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_comment_delete', 'wpmlm_ajax_comment_delete' );
add_action( 'wp_ajax_wpmlm_ajax_comment_edit', 'wpmlm_ajax_comment_edit' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_comment_edit', 'wpmlm_ajax_comment_edit' );
add_action( 'wp_ajax_wpmlm_ajax_comment_update', 'wpmlm_ajax_comment_update' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_comment_update', 'wpmlm_ajax_comment_update' );
add_action( 'wp_ajax_wpmlm_ajax_document_vault', 'wpmlm_ajax_document_vault' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_document_vault', 'wpmlm_ajax_document_vault' );
add_action( 'wp_ajax_wpmlm_ajax_comment_user_delete', 'wpmlm_ajax_comment_user_delete' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_comment_user_delete', 'wpmlm_ajax_comment_user_delete' );
add_action( 'wp_ajax_wpmlm_ajax_activate_user', 'wpmlm_ajax_activate_user' );
add_action( 'wp_ajax_nopriv_wpmlm_ajax_activate_user', 'wpmlm_ajax_activate_user' );

function wpmlm_registration_page_shortcode() {
    wp_enqueue_style('wp-mlm-datepicker', plugins_url('css/datepicker.css', __FILE__));
    wp_enqueue_style('wp-mlm-custom-style', plugins_url('css/custom-style.css', __FILE__));

    wp_enqueue_script('wp-mlm-jquery-js', plugins_url('/js/jquery.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-jquery-validate', plugins_url( '/js/jquery.validate.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('wp-mlm-bootstrap-datepicker1', plugins_url('/js/bootstrap-datepicker.js', __FILE__), array( 'jquery' ));

    wp_enqueue_script('wp-mlm-my-script1', plugins_url('/js/script.js', __FILE__), array( 'jquery' ));

    wp_localize_script('wp-mlm-my-script1', 'path', array('pluginsUrl' => plugins_url(WP_MLM_PLUGIN_NAME),));
    
    if(isset($_GET['payment_method'])){
        if($_GET['payment_method']=='paypal'){
             $payment_obj = new WPMLM_Payment_Method();
            echo $payment_obj->wpmlm_paypal_method();
        }
       
    }
    

    session_start();
    global $wpdb;
    global $current_user;
    $table_prefix = $wpdb->prefix;

    $result = wpmlm_get_general_information();
    $reg_pack_type = $result->registration_type;
    $current_user_name = $current_user->user_login;
    $reg_amt = $result->registration_amt;
    $reg_amt_currency = $result->company_currency;


    
    
    $country_query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."wpmlm_country WHERE 1 ORDER BY name ASC");
    $countries = $wpdb->get_results($country_query);


$wp_nonce_code = wp_nonce_field("wpmlm_registration", "wpmlm_registration_nonce");

$sponsor = (isset($_SESSION["sponsor"]) ? $_SESSION["sponsor"]:"");

$arr= '<div class="col-md-12" id="mlm-main-div"><div class="container-1"><div class="alert info submit_message1"></div>
   <div class="wpmlm-registration-form">
        <div class="info-card">
            <div class="info-card-head">
                <h2>'. __("WP MLM User Registration","wpmlm-unilevel").'</h2>
                <small class="form-text text-muted">'. __("CUSTOMER INFORMATION","wpmlm-unilevel") .'</small>
            </div>
            <div class="info-card-body">
                <form class="info-card-form" id="wpmlm-registration-form" method="post">

                    <br><h4>'. __("User Info","wpmlm-unilevel").'</h4>
                    <div class="form-group">
                        <label for="sname">'. __("SPONSOR NAME","wpmlm-unilevel").':</label>
                        <input type="text" name="sname" class="form-control main_input" id="sname" value="'.$sponsor.'" placeholder="'. __('Enter Sponsor name','wpmlm-unilevel').'" required>
                    </div>';
        
                   if ($reg_pack_type != "with_out_package") {
                        $packages = wpmlm_select_all_packages();
                     if (count($packages) > 0) {
                            $result2 = wpmlm_get_general_information();
                        
             $arr.=    '<div class="form-row row">
                            <div class="form-group width-col-12">
                                <select class="form-control" name="package_select" id="package_select" required>
                                    <option value="" tabindex="1">'.__("Select Package","wpmlm-unilevel").'</option>';
                                   
                             $results = wpmlm_select_all_packages();
                                    foreach ($results as $res) {
                                    
                              $arr.= '<option value="'. $res->id.'">'. $res->package_name . ' - ' . $result2->company_currency . $res->package_price.'</option>' ;
                              } 
                        $arr.= '</select>
                            </div>
                        </div>';
                        } 
                    }
                    
                    if ($reg_pack_type == "with_out_package") {
                    
             $arr.='<div class="form-group">
                        <label for="reg_amount">'. __("REGISTRATION AMOUNT","wpmlm-unilevel").':</label>
                        <input type="text" name="reg_amount" class="form-control" id="reg_amount" value="'.(isset($_SESSION["package_price"])) ? $_SESSION["package_price"] : $reg_amt . $reg_amt_currency.'" readonly required>
                    </div>
                    ';
                    }
                  
             $arr.='<div class="form-row row">
                        <div class="form-group width-col-4">
                            <label for="fname">'. __("FIRST NAME","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control form-control" name="fname" id="fname" placeholder="'. __("Enter First Name","wpmlm-unilevel").'" required>
                        </div>                            
                        <div class="form-group width-col-4">
                            <label for="lname">'. __("LAST NAME","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control" name="lname" id="lname" placeholder="'. __("Enter Last Name","wpmlm-unilevel").'">
                        </div>
                        <div class="form-group width-col-4">
                            <label for="dob">'. __("DOB","wpmlm-unilevel").':</label>
                            
                                <input type="date" class="form-control date_of_birth" name="date_of_birth" placeholder="'. __("Enter DOB","wpmlm-unilevel").'" required/>
                                
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group width-col-6 ">
                            <label for="username">'. __("USERNAME","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control main_input" name="username" id="username" placeholder="'. __("Enter Username","wpmlm-unilevel").'" required>
                        </div>
                        <div class="form-group width-col-6 ">
                            <label for="password">'. __("PASSWORD","wpmlm-unilevel").':</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="'. __("Enter Password","wpmlm-unilevel").'" required>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group width-col-6 ">
                            <label for="email">'. __("EMAIL","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control main_input" name="email" id="email" placeholder="'. __("Enter Email","wpmlm-unilevel").'" required>
                        </div>
                        <div class="form-group width-col-6 ">
                            <label for="contact_no">'. __("MOBILE NUMBER","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="'. __("Mobile Number","wpmlm-unilevel").'" required>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group width-col-12">
                            <label for="address1">'. __("ADDRESS","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control" name="address1" id="address1" placeholder="'. __("Enter Address","wpmlm-unilevel").'" required>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group width-col-6">
                            <label for="country">'. __("COUNTRY","wpmlm-unilevel") .':</label>
                            <select class="form-control" name="country" id="country" required>
                                <option value="">'. __("Select Country","wpmlm-unilevel") .'</option>';
                                foreach ($countries as $country) {
                                    if($country){
                                $arr.='<option value="'.$country->id.'">'.$country->name.'</option>'; 
                                }else{
                                $arr.='<option value="">'. __("Country not available","wpmlm-unilevel") .'</option>';
                                    }
                                }
                                
                    $arr.= '</select>
                        </div>
                        <div class="form-group width-col-6">
                            <label for="state">'. __("STATE","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control" name="state" id="state" placeholder="'. __("State","wpmlm-unilevel").'" required>
                             
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group width-col-6">
                            <label for="city">'. __("CITY","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control" name="city" id="city" placeholder="'. __("City","wpmlm-unilevel").'" required>
                        </div>
                        <div class="form-group width-col-6">
                            <label for="zip">'. __("ZIP","wpmlm-unilevel").':</label>
                            <input type="text" class="form-control" name="zip" id="zip" placeholder="'. __("ZIP Code","wpmlm-unilevel").'" required>
                        </div>
                    </div>';
                    $arr.= '<div class="form-row row">
                            
                            <br><h4>'. __("Payment Mode","wpmlm-unilevel").'</h4>';
                        
                        $results = wpmlm_select_reg_type();
                        $ckd = 0;
                        $reg_type = "paypal";
                        foreach($results as $res) {
                            $ckd++;
                            if ($ckd == 1) {
                                $ckd = "checked";
                            } else {
                                $ckd = "";
                            }
                        
                
                            if ($res->reg_type == "free_join") {
                  $arr.= '  <div class="form-check form-check-inline width-col-12">
                                <input class="form-check-input free_join" type="radio" name="user_registration_type" id="user_registration_type" value="'. $res->reg_type .'" required '. $ckd .'>
                                <label class="form-check-label" for="user_registration_type">'.  ucwords(str_replace("_", " ", $res->reg_type)).'</label>
                            </div>';
                            
                            $reg_type = "";
                            } else {
                             

                    $arr.= '<div class="form-check form-check-inline paypal-radio width-col-12">
                                <input class="form-check-input paid_join" type="radio" name="user_registration_type" id="user_registration_type" value="paypal"  required '. $ckd. '>
                                <label class="form-check-label" for="user_registration_type"><img src='. plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/gateway/paypal-sdk-v2/paypal.svg".'></label>
                            </div>';
                            } 
                        }
                    
         $arr.= '</div>
                <div class="form-row row">
                    <div class="form-group width-col-12">
                    <div class="form-group">
                        <br>
                        <button type="submit" class="btn btn-primary" id="reg_submit" name="reg_submit" value="reg_submit" >'. __("Submit","wpmlm-unilevel").'</button></div>
                    </div>
                </div>'.$wp_nonce_code.'
                
               </form>
            </div>
        </div>
    </div>
</div></div>';

$arr .= <<<EOT
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#wpmlm-registration-form').on('submit', function(e){
           
           var formData = new FormData(this);
           formData.append('action','wpmlm_registration_page');
           formData.append('reg_submit','1');


           isValid = true;
           $(".form-control").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
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
                    
                        if(data.payment_type != "free_join") {
                            window.location.href = data.payment_link;  
                        }else{
                            window.location.href = data.redirect_link;
                        }
                        
                   }
               });
   
           }
           return false;
   
       });
});
</script>
EOT;
// $(".mlm-main-div").html(data);window.location.href = "../mlm-user";

return $arr;

} 
add_shortcode('wp_affiliate_registration_form', 'wpmlm_registration_page_shortcode');



add_shortcode('wp_affiliate_user_dashboard', 'wpmlm_user_shortcode');

add_action('plugins_loaded', 'plugin_initt'); 

function plugin_initt() {

        load_plugin_textdomain( 'wpmlm-unilevel', false, dirname(plugin_basename(__FILE__)).'/languages/' );

        }
function spark_change_language( $locale ){
    $locale = 'pt_PT';
    return $locale;
}
//add_filter( 'locale', 'spark_change_language' );


function my_restrict_wpadmin_access() {
    
    if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
        $user = wp_get_current_user();
        //print_r($user->roles);die('user');
        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            if ( in_array('administrator', $user->roles) ) {
                $data_login = get_option('axl_jsa_login_wid_setup');
                return admin_url();
            }
            else{
                $dash_result = wpmlm_get_general_information();
                $dash_slug_id = get_post($dash_result->user_dash); 
                $dash_slug = $dash_slug_id->post_name;
                //my_trim_function( $trim_me );
                wp_redirect( home_url('/'.$dash_slug));
                die;
            }
        }
    }
}
add_action( 'admin_init', 'my_restrict_wpadmin_access' );

// Add Events page on activation:

// function install_events_pg(){
        
//         $new_page_title =  'sshlohlhoh';

//         $new_page_content = 'This is your page content that automatically gets inserted into the Events page!';
//         $new_page_template = ''; //ex. template-custom.php. Leave blank if you don’t want a custom page template.
//         //don’t change the code below, unless you know what you’re doing
//         $page_check = get_page_by_title($new_page_title);
//         $new_page = array(
//                 'post_type' => 'page',
//                 'post_title' => $new_page_title,
//                 'post_content' => $new_page_content,
//                 'post_status' => 'publish',
//                 'post_author' => 1,
//         );
//         if(!isset($page_check->ID)){
//                 $new_page_id = wp_insert_post($new_page);
//                 if(!empty($new_page_template)){
//                         update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
//                 }
//         }
// }//end install_events_pg function to add page to wp on plugin activation
//register_activation_hook(__FILE__, 'install_events_pg');


?>  