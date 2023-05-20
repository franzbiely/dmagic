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
        <ul class="subsubsub">
            <li class="all"><a href="http://localhost:8000/wp-admin/admin.php?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=all" aria-current="page">All <span class="count"></span></a> |</li>
            <li class="moderated"><a href="http://localhost:8000/wp-admin/admin.php?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=moderated">Pending <span class="count"></span></a> |</li>
            <li class="approved"><a href="http://localhost:8000/wp-admin/admin.php?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=approved">Approved <span class="count"></span></a> |</li>
            <li class="trash"><a href="http://localhost:8000/wp-admin/admin.php?page=request-widthrawal&comment_type=widthrawal-request&amp;comment_status=trash">Trash <span class="count"></span></a></li>
        </ul>
        <table class="wp-list-table widefat fixed striped">
        <thead>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"></th>
                    <th scope="col" class="manage-column column-comment">Request</th>
                    <th scope="col" class="manage-column column-date">Date</th>
                    <th scope="col" class="manage-column column-date">Status</th>
                    <th scope="col" class="manage-column column-actions">Actions</th>
                </tr>
            </thead>
            <tbody id="the-comment-list">
                <?php
                foreach ($comments as $comment) { ?>
                <tr>
                    <td></td>
                    <td><?php echo nl2br(get_comment_text($comment->comment_ID)) ?></td>
                    <td><?php echo get_comment_date(get_option('date_format'), $comment->comment_ID) ?></td>
                    <td><?php echo get_comment_status($comment->comment_approved) ?></td>
                    <td>
                        <?php if(!isset($_GET['comment_status']) || $_GET['comment_status'] !=='approved') : ?>
                        <a href="?page=request-widthrawal&action=approve&c=<?php echo $comment->comment_ID ?>">Approve</a>
                        <?php endif; ?>
                        <?php if(!isset($_GET['comment_status']) || $_GET['comment_status'] !=='moderated') : ?> | 
                        <a href="?page=request-widthrawal&action=unapprove&c=<?php echo $comment->comment_ID ?>">Unapprove</a>
                        <?php endif; ?>
                        <?php if(!isset($_GET['comment_status']) || $_GET['comment_status'] !=='trash') : ?> | 
                        <a href="?page=request-widthrawal&action=trash&c=<?php echo $comment->comment_ID ?>">Decline</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column">&nbsp;</th>
                    <th scope="col" class="manage-column column-comment">&nbsp;</th>
                    <th scope="col" class="manage-column column-date">&nbsp;</th>
                    <th scope="col" class="manage-column column-date">&nbsp;</th>
                    <th scope="col" class="manage-column column-actions">&nbsp;</th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}