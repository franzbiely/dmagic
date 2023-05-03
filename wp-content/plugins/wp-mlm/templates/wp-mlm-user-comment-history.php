<?php
function wpmlm_user_comment_history($user_id = '') {
  $results = wpmlm_get_comment_history($user_id);
    
    ?>
    <div id="user-profile">
    <div class="panel panel-default">
        <div class="panel-heading">
         <h4><i class="fa fa-info-circle"></i> <span class="report-caption"> <?php _e('Comment and User History','wpmlm-unilevel'); ?></span></h4>         
      </div>
                <div class="panel-border">
                  <div class="comment_message"></div>
               <!-- <h4><?php //_e('Account Information','wpmlm-unilevel'); ?></h4> -->
                    <form id="comment-form" class="form-horizontal " >
                      <input type="hidden" name="userid" id="userid" value="<?php echo $user_id; ?>">
                        <div class="form-group">
                     <label class="control-label col-md-3" for="admin_name"><?php _e('Admin Name','wpmlm-unilevel'); ?>:</label>
                     <div class="col-md-7">
                                <input type="text" class="tax-dt" name="admin_name" id="admin_name" style="border: none;">
                            </div>
                        </div>
                        <div class="form-group">
                     <label class="control-label col-md-3" for="comment"><?php _e('Comment','wpmlm-unilevel'); ?>:</label>
                     <div class="col-md-7">
                                <textarea class="tax-dt" name="comment" id="comment" style="width: 100%;margin-top: 3%;" > </textarea>
                            </div>
                        </div>

                           <?php wp_nonce_field('comment_form', 'comment_form_nonce'); ?>

                           <button class="btn btn-danger comment_save" type="submit" name="comment_submit" id="comment_save" style="margin-left: 26%; margin-top: 1%;">
                                                          <?php _e('Comment','wpmlm-unilevel'); ?> <i class="fa fa-arrow-circle-right"></i>
                                                          </button>

                    </form>
              </div> 
      </div> 

      <div class="panel panel-default">
          <div class="panel-heading">
           <h4><i class="fa fa-info-circle"></i> <span class="report-caption"> <?php _e('Comment History','wpmlm-unilevel'); ?></span></h4>         
        </div>
                  <div class="panel-border">
                    <div class="comment_dlt_message"></div>
                   
                <!--  <h4><?php// _e('Account Information','wpmlm-unilevel'); ?></h4> -->
                          <div  id="comment_printarea" style="overflow: auto;" class="comment_print_area" >
                             

                              <?php  
                   if (count($results) >0) { ?>

                                  <table id="comment_tableview" class="table table-striped table-bordered" cellspacing="0" width="100%">
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

                                              echo '<tr>
                      <td>' . $count . '</td>
                      <td>' . $res->date . '</td>
                      <td><span class="teaser">' . $string . '</span> <span class="complete">' . $fullstring . '</span> <span class="more spn-teaser" style="cursor:pointer;color:navy;">more...</span></td>
                      <td>' . $res->admin_name . '</td>
                      <td><a class="btn btn-sm edit_class" data-toggle="modal" data-value="'.$res->id.'" data-target="#exampleModal"> Edit </a>
                    <a class="btn btn-sm delete_class" data-value="'.$res->id.'"> Delete </a></td>                             
                      </tr>';
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

     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <h4 class="modal-title" id="exampleModalLabel"><span class="report-caption"> <?php _e('Edit Comment','wpmlm-unilevel'); ?></span></h4>
             
           </div>
           <div class="modal-body"style="padding-bottom: 30%;">

           </div>
          <!--  <div class="modal-footer" style="margin-top: 20%;display:none">
             
           </div> -->
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

          // $(".more").toggle(function(){
          //     //$(this).text("less..").siblings(".teaser").hide(); 
          //     $(this).text("less..").siblings(".complete").show();    
          // }, function(){
          //     //$(this).text("more..").siblings(".complete").hide();    
          // });


          $('#comment_tableview').DataTable({
                "pageLength": 10,
                "bFilter": false
            });

                $("#comment-form").submit(function () {
                var formData = new FormData(this);
                
                formData.append('action','wpmlm_ajax_comment_history');
           isValid = true;
                
                
                if (isValid) {
                    
               $.ajax({
                   type: "POST",
                   url: ajaxurl,
                   data: formData,
                   cache: false,
                   processData: false,
                   contentType: false,
                   success: function (data) {
                    //alert(data);
                    $('#comment_tableview').DataTable();
                         $("#comment_printarea").load(" #comment_tableview");
                        
                        $(".comment_message").show();
                        $(".comment_message").html('<div class="alert alert-info">' + data + '</div>');
                           setTimeout(function () {
                               $(".comment_message").hide();

                               }, 5000);
                       
                        

                       
                   }
               });
   
           }
           return false;
   
       });

      $(document).on('click', '.delete_class', function(){ 
               var dl_id = $(this).attr('data-value');
               ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';

               $.ajax({ 
                    data: {
                       action: 'wpmlm_ajax_comment_delete', 
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
                        $("#comment_printarea").load(" #comment_tableview");

                   }
               });
                   return false;

           });
      $(document).on('click', '.edit_class', function(){ 
      //$("#comment_tableview").delegate(".edit_class", "click", function(){

         var user1 = $(this).attr('data-value');
         $('#exampleModal').modal('show');
         ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';

         $.ajax({ 
                      data: {
                       action: 'wpmlm_ajax_comment_edit', 
                       uid: user1, 
                      },
                      type: 'post',
                      url: ajaxurl,
                      success: function(data) {
                       
                         $(".modal-body").html(data);
                     }
                 });
                     return false;
       });



        });

    </script>
    <?php
}