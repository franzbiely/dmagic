<?php

function wpmlm_user_area() {
    
    $user_id = get_current_user_id();
    $user_details = wpmlm_get_user_details($user_id);
    $user = get_user_by('id', $user_id);
    $parent_id = $user_details->user_parent_id;
    $package_id = $user_details->package_id;
    $user_status = $user_details->user_status;
    
    if (($user_id) && ($user_status == 1)) {
        echo '<div class="col-md-12 mlm-main-div" id="mlm-main-div"> ';

        if (isset($_GET['reg_status'])) {
            echo '<div class="panel-border"><div class="col-md-8 status-msg alert alert-success alert-dismissible text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' . base64_decode($_GET['reg_status']) . '</b></div></div>';
            ?>

            <h3 class="mlm-title"><?php _e('WP MLM User','wpmlm-unilevel'); ?></h3>
            <div class="ioss-mlm-menu">
                <input id="ioss-mlm-tab6" class="tab_class" type="radio" name="tabs" checked>
                <label class="tab_class" for="ioss-mlm-tab6"><?php _e('Dashboard','wpmlm-unilevel'); ?></label>

                <input id="ioss-mlm-tab1" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab1"><?php _e('My Profile','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab2" class="tab_class tree-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab2"><?php _e('Genealogy Tree','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab3" class="tab_class ewallet-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab3"><?php _e('E-wallet Management','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab4" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab4"><?php _e('Bonus Details','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab5" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab5"><?php _e('Referrall Details','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab7" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab7"><?php _e('Materials','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab8" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab8"><?php _e('Comments','wpmlm-unilevel'); ?></label>

                <section id="content1"><p><?php echo wpmlm_user_profile_admin($user_id); ?></p></section>    
                <section id="content2" ><p><?php echo wpmlm_unilevel_tree($user_id); ?></p></section> 
                <section id="content3"><p><?php echo wpmlm_user_ewallet_management(); ?></p></section>
                <section id="content4"><p><?php echo wpmlm_user_income_details($user_id); ?></p></section>
                <section id="content5"><p><?php echo wpmlm_user_referrals($user_id); ?></p></section>
                <section id="content6"><p><?php echo wpmlm_user_dashboard($user_id); ?></p></section>
                <section id="content7"><p><?php echo wpmlm_user_vault($user_id); ?></p></section>
                <section id="content8"><p><?php echo wpmlm_user_comments($user_id); ?></p></section> 

            </div>
            <?php
        } else if (isset($_GET['reg_failed'])) {
            ?>
            <h3 class="mlm-title"><?php _e('WP MLM User Registration','wpmlm-unilevel'); ?></h3>
            <?php
            echo '<div class="panel-border"><div class="col-md-8 status-msg alert alert-danger text-center"><b>' . base64_decode($_GET['reg_failed']) . '</b>
</div></div>';
        } else {
            ?>
            <h3><?php _e('WP MLM User','wpmlm-unilevel'); ?></h3>
            <div class="ioss-mlm-menu">
                <input id="ioss-mlm-tab6" class="tab_class" type="radio" name="tabs" checked>
                <label class="tab_class" for="ioss-mlm-tab6"><?php _e('Dashboard','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab1" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab1"><?php _e('My Profile','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab2" class="tab_class tree-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab2"><?php _e('Genealogy Tree','wpmlm-unilevel'); ?></label>    
                <input id="ioss-mlm-tab3" class="tab_class ewallet-tab-user" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab3"><?php _e('E-wallet Management','wpmlm-unilevel'); ?></label>      
                <input id="ioss-mlm-tab4" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab4"><?php _e('Bonus Details','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab5" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab5"><?php _e('Referral Details','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab7" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab7"><?php _e('Materials','wpmlm-unilevel'); ?></label>
                <input id="ioss-mlm-tab8" class="tab_class" type="radio" name="tabs">
                <label class="tab_class" for="ioss-mlm-tab8"><?php _e('Comments','wpmlm-unilevel'); ?></label>


                <section id="content1"><p><?php echo wpmlm_user_profile_admin($user_id); ?></p></section>  
                <section id="content2" ><p><?php echo wpmlm_unilevel_tree($user_id); ?></p></section> 
                <section id="content3"><p><?php echo wpmlm_user_ewallet_management(); ?></p></section>
                <section id="content4"><p><?php echo wpmlm_user_income_details($user_id); ?></p></section>
                <section id="content5"><p><?php echo wpmlm_user_referrals($user_id); ?></p></section>
                <section id="content6"><p><?php echo wpmlm_user_dashboard($user_id); ?></p></section> 
                <section id="content7"><p><?php echo wpmlm_user_vault($user_id); ?></p></section>
                 <section id="content8"><p><?php echo wpmlm_user_comments($user_id); ?></p></section> 
            </div>

            <?php
        }
        echo "</div>";
    } else {
        echo '<div class="alert alert-warning alert-dismissible show" role="alert">Please Register first!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></button></div>';
        echo do_shortcode('[wpmlm_registration_form]');
    }
    
}
?>