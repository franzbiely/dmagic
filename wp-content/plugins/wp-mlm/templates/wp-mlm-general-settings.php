<?php

function wpmlm_general_settings() {
    $result = wpmlm_get_general_information();
    ?>
    <div id="general-settings">
         <div class="panel panel-default">

            <div class="panel-heading">
                <h4><i class="fa fa-external-link-square" aria-hidden="true"></i> <span> <?php _e('General Settings','wpmlm-unilevel'); ?></span></h4>
            </div>
            <div class="panel-border">
                <div id="submit_message"></div>
                <form id="general-form" class="form-horizontal " method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="company_name"><?php _e('Company Name','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control company_input" name="company_name" id="company_name" placeholder="<?php _e('Enter Company Name','wpmlm-unilevel'); ?>" value="<?php echo $result->company_name; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="company_address"><?php _e('Company Address','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">

                            <textarea class="form-control company_input" name="company_address" id="company_address" rows="4"><?php echo $result->company_address; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-12 col-xs-12 user-dt" ><?php _e('Company Logo','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-2 col-sm-3 col-xs-3" > <img class="thumb-image-general" src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result->company_logo; ?>">       
                        </div>


                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-6 company_logo col-md-offset-2"> 
                            <label for="company_logo" class="custom-file-upload-logo">
                                <?php
                                if ($result->company_logo == 'default_logo.png') {
                                    echo '<i class="fa fa-cloud-upload"></i>';
                                    ?><?php _e("Upload Logo","wpmlm-unilevel");
                                } else {
                                    echo '<i class="fa fa-cloud-upload"></i>';
                                    ?><?php _e("Change Logo","wpmlm-unilevel");
                                }
                                ?>

                            </label>

                            <input type="file" onchange="previewFile()" class="form-control" name="company_logo" id="company_logo" style="margin-top: 10%">
                            <label for="image-remove" class="image-remove" style="<?php if ($result->company_logo == 'default_logo.png') {
                                    echo 'display:none';
                                } ?>" >
                                <i class="fa fa-trash"></i> <?php _e('Remove','wpmlm-unilevel'); ?>

                            </label>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2"></div>
                        <div class="col-md-6">
                            
                            <input type="checkbox" <?php echo ($result->site_logo=='active'?'checked':'')?> class="form-control" name="site_logo" id="site_logo" value="active"  ><label class="control-label site_logo_label" for="site_logo"><?php _e('Use same image in the Login/Register Page','wpmlm-unilevel'); ?></label>
                    </div></div>
                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="company_email"><?php _e('Company Email','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control company_input" name="company_email" id="company_email" placeholder="<?php _e('Enter Email','wpmlm-unilevel'); ?>" value="<?php echo $result->company_email; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="company_phone"><?php _e('Company Phone','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control company_input" name="company_phone" id="company_phone" placeholder="<?php _e('Enter Company Phone','wpmlm-unilevel'); ?>" value="<?php echo $result->company_phone; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="company_currency"><?php _e('Currency','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-6">
                            <select class="form-control" name="company_currency" id="company_currency">
                                <option value="" tabindex="1"><?php _e('Select Currency','wpmlm-unilevel'); ?></option>
                                <option value="A$" <?php echo ($result->company_currency == 'A$') ? 'selected' : '' ;?>><?php _e('Australian dollar','wpmlm-unilevel'); ?></option>
                                <option value="R$" <?php echo ($result->company_currency == 'R$') ? 'selected' : '' ;?>><?php _e('Brazilian real','wpmlm-unilevel'); ?></option>
                                <option value="C$" <?php echo ($result->company_currency == 'C$') ? 'selected' : '' ;?>><?php _e('Canadian dollar','wpmlm-unilevel'); ?></option>
                                <option value="¥" <?php echo ($result->company_currency == '¥') ? 'selected' : '' ;?>><?php _e('Chinese Renmenbi','wpmlm-unilevel'); ?></option>
                                <option value="Kč" <?php echo ($result->company_currency == 'Kč') ? 'selected' : '' ;?>><?php _e('Czech koruna','wpmlm-unilevel'); ?></option>
                                <option value="Kr." <?php echo ($result->company_currency == 'Kr.') ? 'selected' : '' ;?>><?php _e('Danish krone','wpmlm-unilevel'); ?></option>
                                <option value="€" <?php echo ($result->company_currency == '€') ? 'selected' : '' ;?>><?php _e('Euro','wpmlm-unilevel'); ?></option>
                                <option value="HK$" <?php echo ($result->company_currency == 'HK$') ? 'selected' : '' ;?>><?php _e('Hong Kong dollar','wpmlm-unilevel'); ?></option>
                                <option value="Ft" <?php echo ($result->company_currency == 'Ft') ? 'selected' : '' ;?>><?php _e('Hungarian forint','wpmlm-unilevel'); ?></option>
                                <option value="₹" <?php echo ($result->company_currency == '₹') ? 'selected' : '' ;?>><?php _e('Indian rupee','wpmlm-unilevel'); ?></option>
                                <option value="₪" <?php echo ($result->company_currency == '₪') ? 'selected' : '' ;?>><?php _e('Israeli new shekel','wpmlm-unilevel'); ?></option>
                                <option value="JP¥" <?php echo ($result->company_currency == 'JP¥') ? 'selected' : '' ;?>><?php _e('Japanese yen','wpmlm-unilevel'); ?></option>
                                <option value="RM" <?php echo ($result->company_currency == 'RM') ? 'selected' : '' ;?>><?php _e('Malaysian ringgit','wpmlm-unilevel'); ?></option>
                                <option value="Mex$" <?php echo ($result->company_currency == '') ? 'selected' : '' ;?>><?php _e('Mexican peso','wpmlm-unilevel'); ?></option>
                                <option value="NT$" <?php echo ($result->company_currency == 'NT$') ? 'selected' : '' ;?>><?php _e('New Taiwan dollar','wpmlm-unilevel'); ?></option>
                                <option value="NZ$" <?php echo ($result->company_currency == 'NZ$') ? 'selected' : '' ;?>><?php _e('New Zealand dollar','wpmlm-unilevel'); ?></option>
                                <option value="Kr" <?php echo ($result->company_currency == 'Kr') ? 'selected' : '' ;?>><?php _e('Norwegian krone','wpmlm-unilevel'); ?></option>
                                <option value="₱" <?php echo ($result->company_currency == '₱') ? 'selected' : '' ;?>><?php _e('Philippine peso','wpmlm-unilevel'); ?></option>
                                <option value="zł" <?php echo ($result->company_currency == 'zł') ? 'selected' : '' ;?>><?php _e('Polish złoty','wpmlm-unilevel'); ?></option>
                                <option value="£" <?php echo ($result->company_currency == '£') ? 'selected' : '' ;?>><?php _e('Pound sterling','wpmlm-unilevel'); ?></option>
                                <option value="₽" <?php echo ($result->company_currency == '₽') ? 'selected' : '' ;?>><?php _e('Russian ruble','wpmlm-unilevel'); ?></option>
                                <option value="S$" <?php echo ($result->company_currency == 'S$') ? 'selected' : '' ;?>><?php _e('Singapore dollar','wpmlm-unilevel'); ?></option>
                                <option value="SEK" <?php echo ($result->company_currency == 'SEK') ? 'selected' : '' ;?>><?php _e('Swedish krona','wpmlm-unilevel'); ?></option>
                                <option value="Fr" <?php echo ($result->company_currency == 'Fr') ? 'selected' : '' ;?>><?php _e('Swiss franc','wpmlm-unilevel'); ?></option>
                                <option value="฿" <?php echo ($result->company_currency == '฿') ? 'selected' : '' ;?>><?php _e('Thai baht','wpmlm-unilevel'); ?></option>
                                <option value="$" <?php echo ($result->company_currency == '$') ? 'selected' : '' ;?>><?php _e('US dollar','wpmlm-unilevel'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="user_dash"><?php _e('User Dashboard Page','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-5">
                            <!-- new -->
                            <?php $args = array(
                                'sort_order' => 'asc',
                                'sort_column' => 'post_title',
                                'hierarchical' => 1,
                                'exclude' => '',
                                'include' => '',
                                'meta_key' => '',
                                'meta_value' => '',
                                'authors' => '',
                                'child_of' => 0,
                                'parent' => -1,
                                'exclude_tree' => '',
                                'number' => '',
                                'offset' => 0,
                                'post_type' => 'page',
                                'post_status' => 'publish'
                            ); 
                            $pages_array = array( 'Choose A Page' );
                            $get_pages = get_pages( 'hide_empty=0' ); ?>

                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="user_dash" id="user_dash">
                                  
                           <?php foreach ( $get_pages as $page ) {  
                                $pages_array[$page->ID] = esc_attr( $page->post_title );  ?> 
                               <option value="<?php echo $page->ID; ?>" <?php echo ($result->user_dash == $page->ID) ? 'selected' : ''; ?> ><?php echo $pages_array[$page->ID]; ?></option>
                            <?php }  ?>
                            </select>
                            
                            <!-- end new -->
                        </div>
                        <div class="col-md-4">
                            <i class="fa fa-info-circle" aria-hidden="true" style="color:green;"></i>
                            <small style="color:green;">Shortcode for User Dashboard: <strong>  [wp_affiliate_user_dashboard]</strong></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 user-dt" for="user_registration"><?php _e('User Registration Page','wpmlm-unilevel'); ?>:</label>
                        <div class="col-md-5">
                            <!-- new -->
                            <?php $args = array(
                                'sort_order' => 'asc',
                                'sort_column' => 'post_title',
                                'hierarchical' => 1,
                                'exclude' => '',
                                'include' => '',
                                'meta_key' => '',
                                'meta_value' => '',
                                'authors' => '',
                                'child_of' => 0,
                                'parent' => -1,
                                'exclude_tree' => '',
                                'number' => '',
                                'offset' => 0,
                                'post_type' => 'page',
                                'post_status' => 'publish'
                            ); 
                            $pages_array = array( 'Choose A Page' );
                            $get_pages = get_pages( 'hide_empty=0' );
?>

                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="user_registration" id="user_registration">
                            <?php foreach ( $get_pages as $page ) { ?>
                                <option value="<?php echo $page->ID; ?>" <?php echo ($result->user_registration == $page->ID) ? 'selected' : ''; ?> ><?php echo $page->post_title; ?></option>
                            <?php } ?>
                            </select>
                            <!-- end new -->
                        </div>
                        <div class="col-md-4">
                            <i class="fa fa-info-circle" aria-hidden="true" style="color:green;"></i>
                            <small style="color:green;">Shortcode for User Registration: <strong>  [wp_affiliate_registration_form]</strong></small>
                        </div>
                    </div>
                    
                    <div class="form-group"> 
                        <div class="col-sm-offset-2 col-sm-6">
                            <input type="hidden" name="action" value="" id="action">
                            <input type="hidden" name="image" value="default_logo.png" id="image">
                            <?php wp_nonce_field('general_add', 'general_add_nonce'); ?>
                            <button id="general-save" type="submit" class="btn btn-danger"> <?php _e('Save','wpmlm-unilevel'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div> 
    </div> 


    <script>
        jQuery(document).ready(function ($) {
            var plugin_url = path.pluginsUrl;
            $("#general-form").submit(function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_general_settings');
                
                isValid = true;
                $(".company_input").each(function () {
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
                            $("#submit_message").show();
                            $("#submit_message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                    $("#submit_message").hide();

                                }, 2000);
                        }
                    });
                }
                return false;
            })
            $(".company_input").focus(function () {
                $(this).removeClass("invalid");
            });
            
            $(document).on('click', '.image-remove', function () {
            $('.thumb-image-general').attr('src', plugin_url + '/uploads/default_logo.png');
            $("#image").val('');
            $("#company_logo").val('');
            $(".image-remove").hide();
            $(".custom-file-upload-logo").html('<i class="fa fa-cloud-upload"></i> Upload Logo');
        });
        
        $("#company_logo").change(function () {
            readURL1(this);
            $(".image-remove").show();
        }); 
        });
    </script>
    <?php
}