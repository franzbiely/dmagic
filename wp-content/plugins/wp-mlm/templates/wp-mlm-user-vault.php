<?php

function wpmlm_user_vault($user_id) {
	$result1 = wpmlm_get_user_details_by_id_join($user_id);
    $username = $result1[0]->user_login;
    $results = get_user_vault($username);

    $count=0;
    $source1 = WP_MLM_PLUGIN_DIR . '/uploads/';
   // print_r($results);die;
    ?>

<div class="panel-border-heading">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-file"></i> <span> <?php _e('Materials','wpmlm-unilevel'); ?></span></h4>

            </div>
            <div class="panel-border">
               <h6><?php _e('Documents','wpmlm-unilevel'); ?></h6> 
                         <div  id="doc_print_area" style="overflow: auto;" class="document-data" >
                             <?php if (count($results) > 0) { ?>

                                         <?php
                                         foreach ($results as $res) {
                                             
                                             
                                             $source1=plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/'.$res->upload;


                                             echo '<div style="background-color: #e7f7f7; width:30%;margin:1%;padding:2%;float:left;">
                     <a href="' . $source1.'" download/><i class="fa fa-download" aria-hidden="true"></i> '.$res->caption.'</a></div>';
                                         }
                                         ?>
                                   
                                 <?php
                             } else {
                                 echo '<div class="no-data">' .__("No Data","wpmlm-unilevel") .'</div>';
                             }
                             ?>
                         </div>
            </div>
        </div>

    </div>
    <?php } ?>