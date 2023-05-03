<?php

function wpmlm_user_details_admin() {
    ?>
    <div class="panel-border-heading">
        <h4><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('User Details','wpmlm-unilevel'); ?></h4>
    </div>


    <div class="panel-border" id="user-div">

        <table id="user-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php _e('Username','wpmlm-unilevel'); ?></th>
                    <th><?php _e('Full Name','wpmlm-unilevel'); ?></th>
                    <th><?php _e('Joining Date','wpmlm-unilevel'); ?></th>
                    <th><?php _e('Action','wpmlm-unilevel'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = wpmlm_get_all_user_details_join();
                $p_count = 0;
                foreach ($results as $res) {

                    $p_count++;
                   $tab_content = '<tr>
                               <th scope="row">' . $p_count . '</th>
                                   <td>' . $res->user_login . '</td>
                              <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                               <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                               
                               <td>
                               
                                   
                                   <button type="button" class="btn btn-default btn-sm user_view" style="float:left;" edit-id="' . $res->ID . '">'. __("View details","wpmlm-unilevel"). '</button>';
                               
                                   if ($res->active_inactive == 'active'){
                                       $ckd = 'checked';
                                   }else{
                                       $ckd = '';
                                   }
                    $tab_content .= '<div class="switch_div" style="float:left; padding:3%;">
                                       <input type="checkbox" class="switch" id="s' . $res->ID . '" data-value="' . $res->ID . '" ' .$ckd. ' style="float:right;"/><label for="s' . $res->ID . '">Toggle</label> 
                                   </div>
                                   

                               </td>
                           </tr>';
                    echo $tab_content;
                }
                ?>

            </tbody>
        </table>
    </div>
    <div class="col-md-12 please-wait" style="text-align: center; display: none"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/please-wait.gif'; ?>"></div>
    <div class="user-details">

    </div>


    <script>

        jQuery(document).ready(function ($) {
            $('#user-table').DataTable({
                "pageLength": 10
            });

            $(document).on("change", ".switch", function () {
                if(this.checked) {
                    var user_sts = 'active';
                }else{
                    var user_sts = 'inactive'
                }
                var tgl_id = $(this).attr('data-value');
                ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';

                $.ajax({ 
                     data: {
                        action: 'wpmlm_ajax_activate_user', 
                        tgl_id: tgl_id,
                        user_sts: user_sts
                     },
                     type: 'post',
                     url: ajaxurl,
                     success: function(data) {

                    }
                });
                    return false;
            });
            
            $(document).on("click", ".user_view", function () {
                $(".please-wait").show();
                $(".user-details").show();

                var user_id = $(this).attr('edit-id');
                $.get(ajaxurl + '?user_id=' + user_id+'&action=wpmlm_ajax_user_details', function (data) {
                    $('.user-details').html(data);
                    $(".please-wait").hide("slow");

                });
                $("#user-div").hide();
                return false;

            });
        });

    </script>
    <?php
}
