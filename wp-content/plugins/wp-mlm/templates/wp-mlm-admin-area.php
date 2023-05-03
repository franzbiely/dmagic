<?php
  
function wpmlm_admin_area() {  
    //install_events_pg();  
    $user_id = get_current_user_id();
    $user_details = wpmlm_get_user_details($user_id);
    $parent_id = $user_details->user_parent_id;
    $packages = wpmlm_select_all_packages();
    $depth = wpmlm_get_level_depth();    
    ?>
<div class="panel-border-heading">
<h3 class="mlm-title"><?php _e('WP MLM Admin','wpmlm-unilevel'); ?></h3>
</div>
  <div class="ioss-mlm-menu panel-border">
    <input id="ioss-mlm-tab1" class="tab_class" type="radio" name="tabs" checked>
    <label class="tab_class" for="ioss-mlm-tab1"><?php _e('Dashboard','wpmlm-unilevel'); ?></label>      
    <input id="ioss-mlm-tab2" class="tab_class user-details-tab" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab2"><?php _e('Users','wpmlm-unilevel'); ?></label>      
    <input id="ioss-mlm-tab3" class="tab_class tree-tab" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab3"><?php _e('Genealogy Tree','wpmlm-unilevel'); ?></label>
    <input id="ioss-mlm-tab9" class="tab_class tree-tab" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab9"><?php _e('Tree View','wpmlm-unilevel'); ?></label>       
    <input id="ioss-mlm-tab4" class="tab_class ewallet-tab" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab4"><?php _e('E-wallet Management','wpmlm-unilevel'); ?></label>
    <input id="ioss-mlm-tab5" class="tab_class report-tab" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab5"><?php _e('Reports','wpmlm-unilevel'); ?></label>
    <input id="ioss-mlm-tab6" class="tab_class" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab6"><?php _e('Change Password','wpmlm-unilevel'); ?></label>
    <input id="ioss-mlm-tab7" class="tab_class" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab7"><?php _e('Settings','wpmlm-unilevel'); ?></label>
    <input id="ioss-mlm-tab8" class="tab_class" type="radio" name="tabs">
    <label class="tab_class" for="ioss-mlm-tab8"><?php _e('Materials','wpmlm-unilevel'); ?></label>    
      
    <section id="content1"><p><?php echo wpmlm_admin_dashboard($user_id); ?></p></section>      
    <section id="content2"><p><?php echo wpmlm_user_details_admin(); ?></p></section>      
    <section id="content3" ><p><?php echo wpmlm_unilevel_tree($user_id); ?></p></section>      
    <section id="content4"><p><?php echo wpmlm_ewallet_management(); ?></p></section>    
    <section id="content5"><p><?php echo wpmlm_all_reports(); ?></p></section>
    <section id="content6"><p><?php echo wpmlm_password_settings(); ?></p></section>
    <section id="content7"><p><?php echo wpmlm_settings(); ?></p></section>
    <section id="content8"><p><?php echo wpmlm_the_vault(); ?></p></section>
    <section id="content9" ><p><?php echo wpmlm_tree_view($user_id); ?></p></section> 
       
  </div>
<?php }