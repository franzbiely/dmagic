<?php

function wpmlm_the_vault() {
    $count=0;
    $results = get_all_vault();
session_start();

    
    ?>
    <div class="panel-border-heading">
        <h4><i class="fa fa-file" aria-hidden="true"></i> <?php _e('Materials','wpmlm-unilevel'); ?></h4>
    </div>
    <div id="all-reports">
        <div class="panel-border col-md-12">
            <div id="exTab4">
                <div class="col-md-3">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        <!-- <li class="active"><a href="#doc-management" data-toggle="tab" class="doc_management"><?php// _e('Document Management','wpmlm-unilevel'); ?></a></li> -->
                        <li class="active"><a href="#upload-doc" data-toggle="tab" class="upload-doc"><?php _e('Upload Materials','wpmlm-unilevel'); ?></a></li>
                        <li><a href="#list-of-doc" data-toggle="tab" class="list-of-doc"><?php _e('List of Materials','wpmlm-unilevel'); ?></a></li>
                    </ul>
                </div>

                <div class="col-md-9">
                    <!-- Tab panes -->      <!-- Document management -->
                    <div class="tab-content">
                        <div class="tab-pane" id="doc-management" style="display: none;">
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Document Management','wpmlm-unilevel'); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                    
                                    
                                    Coming Soon...
                                </div>
                            </div>
                        </div>
                        <!-- Tab 2 Content-->
                        <div class="tab-pane active" id="upload-doc">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e("Upload Materials","wpmlm-unilevel"); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                    <div class="vault_message"></div>
                                    <form id="upload-doc-form" class="form-horizontal " method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="user_name"><?php _e('Username','wpmlm-unilevel'); ?>:</label>
                                            <div class="col-md-7">
                                                <input type="text" class="vault-dt" name="user_name" id="user_name" style="border: none;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="caption"><?php _e('Caption','wpmlm-unilevel'); ?>:</label>
                                            <div class="col-md-7">
                                                <input type="text" class="vault-dt" name="caption" id="caption" style="border: none;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php _e('Upload File','wpmlm-unilevel'); ?>:</label>
                                            <!-- <div class="col-md-2 col-sm-3 col-xs-3" > <img class="thumb-image-general" src="<?php //echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result->upload; ?>">       
                                            </div> -->
                                            <div class="col-md-7"> 
                                                <input type="file" class="form-control" name="upload_file" id="upload">
                                            </div>
                                        </div>
                                        
                                        <?php wp_nonce_field('document_add_vault', 'document_add_vault_nonce'); ?>   
                                        <button class="btn btn btn-danger" tabindex="5" type="submit" value="Submit" style="margin-left: 26%;"> <?php _e('Upload','wpmlm-unilevel'); ?></button>
                                        
                                    </form> 
                                </div>
                            </div>
                        </div>
                        <!-- list of doc -->
                        <div class="tab-pane" id="list-of-doc">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><i class="fa fa-external-link-square"></i> <span> <?php _e('Uploaded Documents','wpmlm-unilevel'); ?></span></h4>

                                </div>
                                <div class="panel-border">
                                   <!--  <h4><?php// _e('Account Information','wpmlm-unilevel'); ?></h4> -->
                                             <div  id="doc_print_area" style="overflow: auto;" class="document-data" >
                                                 <?php if (count($results) > 0) { ?>

                                                     <table id="document_view" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                         <thead>
                                                             <tr>
                                                                 <th>#</th>
                                                                 <th><?php _e("Username","wpmlm-unilevel"); ?></th>
                                                                 <th><?php _e("File","wpmlm-unilevel"); ?></th>            
                                                             </tr>
                                                         </thead>
                                   
                                                         <tbody>
                                                             <?php
                                                             foreach ($results as $res) {
                                                                 $count++;
                                                                 
                                                                 $source1=plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/'.$res->upload;


                                                                 echo '<tr>
                                         <td>' . $count . '</td>
                                         <td>' . $res->username . '</td>
                                         <td><a href="' . $source1.'" download/><i class="fa fa-download" aria-hidden="true"></i> '.$res->caption.'</a></td>
                                                                      
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

<div style="display:none">
<?php
if(isset($_SESSION['selected_user_id'])){
    $count=0;
    $results = wpmlm_get_comment_history($_SESSION['selected_user_id']); ?>
    <div  id="comment_printarea" style="overflow: auto;" class="comment_print_area" >
                <?php   if (count($results) >0) { ?>

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
                                              



                                              echo '<tr>
                      <td>' . $count . '</td>
                      <td>' . $res->date . '</td>
                      <td>' . $res->comment . '</td>
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
                              } ?>
                          </div>
                          <?php
                             
}
   ?>  
                            


</div>




                                </div>
                            </div>
                        </div>
                        
               
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {

          $('#comment_tableview').DataTable({
                "pageLength": 10,
                "bFilter": false
            });
          $('#document_view').DataTable({
                "pageLength": 10,
                "bFilter": false
            });
            
                 $("#upload-doc-form").submit(function () {
                 var formData = new FormData(this);
                 
                 formData.append('action','wpmlm_ajax_document_vault');
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
                       
                         $(".vault_message").show();
                          $(".vault_message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $(".vault_message").hide();

                                }, 2000);
                            isValid= false;
                         $("#doc_print_area").load(" #document_view");
                         

                    }
                });
        
            }
            return false;
        
        });
        });

    </script>    
    <?php
}
// <td><a class="btn btn-sm edit_class" data-toggle="modal" data-value="'.$res->id.'" data-target="#exampleModal"> Edit </a>
                                       // <a class="btn btn-sm delete_class" data-value="'.$res->id.'"> Delete </a></td>