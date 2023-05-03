<?php

function wpmlm_user_comments($user_id) {
	// $result1 = wpmlm_get_user_details_by_id_join($user_id);
 //    $username = $result1[0]->user_login;
    $results = get_user_comments($user_id);

    $count=0;
    $source1 = WP_MLM_PLUGIN_DIR . '/uploads/';
   // print_r($results);die;
    ?>

<div class="panel-border-heading">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-file"></i> <span> <?php _e('Comments','wpmlm-unilevel'); ?></span></h4>

            </div>
            <div class="panel-border">
               <!-- <h6><?php //_e('Documents','wpmlm-unilevel'); ?></h6>  -->
                         <div  id="comment_area" style="overflow: auto;" class="comment_area" >
                             
                                                          

                                                           <?php  
                                                if (count($results) >0) { ?>

                                                               <table id="comment_view" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                   <thead>
                                                                       <tr>
                                                                           <th>#</th>
                                                                           <th><?php _e("Date","wpmlm-unilevel"); ?></th>
                                                                           <th><?php _e("Comment","wpmlm-unilevel"); ?></th>                                    
                                                                           <th><?php _e("Admin User","wpmlm-unilevel"); ?></th>
                                                                           <th><?php _e("Action","wpmlm-unilevel"); ?></th>
                                                                       </tr>
                                                                   </thead>
                                             
                                                                   <tbody id="comment_row">
                                                                       <?php
                                                                       foreach ($results as $res) {
                                                                           $count++;
                                                                           $fullstring = $res->comment;
                                                                           $string = $res->comment;
                                                                           // strip tags to avoid breaking any html
                                                                           $string = strip_tags($string);
                                                                           if (strlen($string) > 100) {

                                                                               // truncate string
                                                                               $stringCut = substr($string, 0, 60);
                                                                               $endPoint = strrpos($stringCut, ' ');

                                                                               //if the string doesn't contain any space then it will cut without word basis.
                                                                               $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                                              // $string .= '... <a style="cursor: pointer;" href="">Read More</a>';
                                                                           }

                                                                           
 
                                            ?>
<tr>
  <td> <?php echo $count; ?></td>
  <td> <?php echo $res->date; ?></td>
  <td>
    <span class="teaser"><?php echo $string; ?> </span>
    <span class="complete"><?php echo $fullstring; ?></span>
    <span class="more spn-teaser">more..</span>
  </td>
  <td> <?php echo $res->admin_name; ?></td>
  <td><a class="btn btn-sm delete_class" data-value="<?php echo $res->id; ?>"> Delete </a></td>

</tr>
                                            <?php  

                                                                       }
                                                                       ?>
                                                                   </tbody> 
                                                               </table>

                                                               <?php
                                                           } else {
                                                               echo '<div class="no-data">' .__("No Data","wpmlm-unilevel") .'</div>';
                                                           }
                                                           ?>
                                                               
                                                       
                         </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">

      $(".more").click(function(){

        if($(this).hasClass('spn-teaser')) {
          $(this).text("less..").siblings(".teaser").hide(); 
          $(this).text("less..").siblings(".complete").show();  
          $(this).removeClass('spn-teaser').addClass('spn-complete');
        } else {
          $(this).text("more..").siblings(".complete").hide(); 
          $(this).text("more..").siblings(".teaser").show();
          $(this).removeClass('spn-complete').addClass('spn-teaser');
        }
        
      });


    jQuery(document).ready(function ($) {

      
        $(document).on('click', '.delete_class', function(){ 
                 var dl_id = $(this).attr('data-value');
                 ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';

                 $.ajax({ 
                      data: {
                         action: 'wpmlm_ajax_comment_user_delete', 
                         d_id: dl_id,
                      },
                      type: 'post',
                      url: ajaxurl,
                      success: function(data) {
                         $(".comment_dlt_message").show();
                          $(".comment_dlt_message").html('<div class="alert alert-info">' + data + '</div>');
                          setTimeout(function () {
                                 $(".comment_dlt_message").hide();
                                 }, 5000);
                          $("#comment_area").load(" #comment_view");

                     }
                 });
                     return false;

             });
        });
    </script>
    <?php } ?>