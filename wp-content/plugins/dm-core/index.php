<?php
/*
Plugin Name: DivineMagic Functions
Description: 
Author: Artificers
Version: 1.0.0
*/

function enqueue_custom_script() {
    // Enqueue the script
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/custom.js', array(), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_custom_script');

// Auto generate code for processing orders in woocomerce
add_action('woocommerce_order_status_processing', 'request_generate_code');
function request_generate_code($order_id) {
    $order = wc_get_order( $order_id );
    $sponsor = $order->get_meta('Sponsor');
    
    $total_quantity = 0;
    foreach ( $order->get_items() as $item ) {
        $total_quantity += $item->get_quantity();
    }
    $note = "You can now invite friends to join your network using this ";
    $note .= "<a href='https://divinemagic.com.ph/affiliate-user-registration?sponsor=" . $sponsor ."'>REGISTRATION LINK</a>. <br /><br />";
    $note .= "Since you placed a total of ${total_quantity} product(s), you have attained the following codes that you can use in the future : ";
    
    for($x=0; $x<$total_quantity; $x++) {
        $random_string = substr(md5(microtime()),rand(0,26),5);
        if(add_user_meta(1, 'regcodes', $random_string)) {
            $note .= ' <br /><strong>' . $random_string . "</strong>,";
        }
    }
    $note = substr($note, 0, -1);
    $order->add_order_note( $note ); // this set a note viewable by admin
    $customer = $order->get_user();
    if ( $customer ) {
        $customer->add_order_note( $note );  // this note is sent to customer email
    }
    
}

function catch_sponsor_from_billing( $order, $data ) {
    if ( isset( $data['sponsor'] ) ) {

        $sponsor = sanitize_text_field( $data['sponsor'] );
        $order->update_meta_data( 'Sponsor', $sponsor );
    }
}
add_action( 'woocommerce_checkout_create_order', 'catch_sponsor_from_billing', 10, 2 );

// Add sponsor field in woocomerce checkout
function add_sponsor_field( $fields ) {
    $default_sponsor = WC()->session->get( 'sponsor' );
    if ( empty( $default_sponsor ) ) {
        $default_sponsor = 'divmagic_admin';
    }

    $fields['billing']['sponsor'] = array(
        'label'       => 'Sponsor',
        'placeholder' => 'Enter your sponsor',
        'default'     => $default_sponsor,
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'add_sponsor_field' );

// Catch the sponsor from the URL from the shop page
function capture_sponsor_parameter() {
    if ( is_shop() ) {
        if(isset( $_GET['sponsor'] ) ) {
            $sponsor = sanitize_text_field( $_GET['sponsor'] );
            WC()->session->set( 'sponsor', $sponsor );
        }
        else {
            WC()->session->set( 'sponsor', '' );
        }
    }
}
add_action( 'wp', 'capture_sponsor_parameter' );

function exclude_pingbacks_from_admin_comments($clauses) {
    global $pagenow, $wpdb;

    // Check if we are on the comments page in the admin
    if ($pagenow === 'edit-comments.php') {
        // Exclude pingbacks
        $clauses['where'] .= " AND {$wpdb->comments}.comment_type != 'widthrawal-request'";
    }

    return $clauses;
}
add_filter('comments_clauses', 'exclude_pingbacks_from_admin_comments');


// Register a custom menu item in the admin dashboard
function custom_comment_management_menu() {
    add_menu_page(
        'Request Widthrawals', // Page title
        'Request Widthrawal', // Menu title
        'manage_options', // Capability required to access the menu item
        'request-widthrawal', // Menu slug
        'request_widthrawal_page', // Callback function to render the page
        'dashicons-admin-comments', // Icon URL or Dashicons class
        25 // Position in the admin menu
    );
}
add_action('admin_menu', 'custom_comment_management_menu');

// Render the custom comment management page
function get_comment_status($status) {
    if($status === '1') {
        return "Approved";
    }
    else if($status === '0') {
        return "Pending";
    }
    else {
        return "Unapproved";
    }
}


function setRequests() {
    if(isset($_GET['action'])) {
        switch($_GET['action']) {
            case 'approve':
                global $wpdb;
                $wpdb->update(
                    $wpdb->comments,
                    array('comment_approved' => 1),
                    array('comment_ID' => $_GET['c']),
                );
                break;
            case 'unapprove':
                global $wpdb;
                $wpdb->update(
                    $wpdb->comments,
                    array('comment_approved' => 0),
                    array('comment_ID' => $_GET['c']),
                );
                break;
            case 'trash':
                global $wpdb;
                $wpdb->update(
                    $wpdb->comments,
                    array('comment_approved' => 'trash'),
                    array('comment_ID' => $_GET['c']),
                );
                break;
        }
    }
}

function request_widthrawal_page() {
    // Include the necessary WordPress files
    require_once ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php';
    require_once ABSPATH . 'wp-admin/includes/template.php';

    setRequests();

    $_REQUEST['comment_type'] = 'widthrawal-request';
    // Create an instance of the WP_Comments_List_Table class
    $comments_table = new WP_Comments_List_Table();

    // Prepare comment query arguments
    
    $comment_args = array(
        'status'      => 'all',
        'comment_type' => 'widthrawal-request',
    );

    // Get the comments using the prepared arguments
    $comments_table->prepare_items($comment_args);
    $comments = $comments_table->items;
    ?>
    <div class="wrap">
        <h1>Request Widthrawals</h1>
        <div class="submit_message"></div>
        <ul class="subsubsub">
            <li class="all"><a href="?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=all" aria-current="page">All <span class="count"></span></a> |</li>
            <li class="moderated"><a href="?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=moderated">Pending <span class="count"></span></a> |</li>
            <li class="approved"><a href="?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=approved">Approved <span class="count"></span></a> |</li>
            <li class="trash"><a href="?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=trash">Trash <span class="count"></span></a></li>
        </ul>
        <table class="wp-list-table widefat fixed striped">
        <thead>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"></th>
                    <th scope="col" class="manage-column column-comment">Request</th>
                    <th scope="col" class="manage-column column-amount">Amount</th>
                    <th scope="col" class="manage-column column-date">Date</th>
                    <th scope="col" class="manage-column column-date">Status</th>
                    <th scope="col" class="manage-column column-actions">Actions</th>
                </tr>
            </thead>
            <tbody id="the-comment-list">
                <?php
                $totalAmount = 0;
                foreach ($comments as $comment) { 
                    $totalAmount += floatval(get_comment_meta($comment->comment_ID, 'amount', true));
                    ?>
                <tr>
                    <td></td>
                    <td><?php echo nl2br(get_comment_text($comment->comment_ID)) ?></td>
                    <td>₱<?php echo number_format((float) get_comment_meta($comment->comment_ID, 'amount', true), 2, '.', ','); ?></td>
                    <td><?php echo get_comment_date(get_option('date_format'), $comment->comment_ID) ?></td>
                    <td><?php echo get_comment_status($comment->comment_approved) ?></td>
                    <td>
                        <?php if(!isset($_GET['comment_status']) || $_GET['comment_status'] !=='approved') : ?>
                        <form class="approve-widthrawal-request" method="POST">
                            <input type="hidden" name="comment_id" value="<?php echo $comment->comment_ID ?>" />
                            <input type="hidden" name="user_id" value="<?php echo $comment->user_id ?>" />
                            <input type="hidden" name="ewallet_user_name" value="<?php echo $comment->comment_author ?>" />
                            <input type="hidden" name="fund_amount" value="<?php echo get_comment_meta($comment->comment_ID, 'amount', true) ?>" />
                            <input type="hidden" name="transaction_note" value="Withdrawal Request Approved" />
                            <?php wp_nonce_field('fund_management_add', 'fund_management_add_nonce') ?>
                            <button type="submit" class="btn btn-danger approve-widthrawal-request-approve" > <?php _e("Approve","wpmlm-unilevel"); ?></button>
                        </form>
                        <?php endif; ?>
                        <?php if(!isset($_GET['comment_status']) || $_GET['comment_status'] !=='moderated') : ?> | 
                        <a class="btn btn-danger" href="?page=request-widthrawal&action=unapprove&c=<?php echo $comment->comment_ID ?>">Unapprove</a>
                        <?php endif; ?>
                        <?php if(!isset($_GET['comment_status']) || $_GET['comment_status'] !=='trash') : ?> | 
                        <a class="btn btn-danger" href="?page=request-widthrawal&action=trash&c=<?php echo $comment->comment_ID ?>">Decline</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column">&nbsp;</th>
                    <th scope="col" class="manage-column column-comment">Total Amount:</th>
                    <th scope="col" class="manage-column column-amount">₱<?php echo number_format((float) $totalAmount, 2, '.', ','); ?></th>
                    <th scope="col" class="manage-column column-date">&nbsp;</th>
                    <th scope="col" class="manage-column column-actions">&nbsp;</th>
                    <th scope="col" class="manage-column column-date">&nbsp;</th>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            // Fund Transfer Ajax Function

            
            $(".approve-widthrawal-request").submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                formData.append('fund_action', 'admin_debit');
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if ($.trim(data) === "0") {
                            $(".submit_message").html('<div class="alert alert-danger">Something went wrong</div>');
                            setTimeout(function () {
                                $(".submit_message").hide();
                            }, 2000);

                        } else {

                            $(".submit_message").show();
                            $(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $(".submit_message").hide();
                                window.location.href='?page=request-widthrawal&action=approve&c=' + formData.get('comment_id')
                            }, 2000);

                        }
                        $('.approve-widthrawal-request-approve').prop('disabled', false);
                    }
                });
                return false;
            })
        });
    </script>
    <?php
}


add_action('admin_head', 'disable_admin_responsive_styles');

function disable_admin_responsive_styles() {
    echo '<style>
        @media (max-width: 782px) {
            body {
                overflow-x: auto !important;
            }
            #wpadminbar {
                display: none !important;
            }
            #adminmenuback, #adminmenuwrap, #adminmenu, #adminmenushadow {
                position: static !important;
                width: auto !important;
            }
            #adminmenu .wp-submenu,
            #adminmenu .wp-has-current-submenu .wp-submenu,
            #adminmenu .wp-has-current-submenu.opensub .wp-submenu {
                left: 0 !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
        }
    </style>';
}