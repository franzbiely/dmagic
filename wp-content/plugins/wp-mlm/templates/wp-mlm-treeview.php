<?php
function wpmlm_tree_view($user_id='') {
    
echo '<div id="dynamic-div" class="mlm-tree-div">';
    $user_details_matrix = wpmlm_get_all_user_details_join();
    $tree_matrix = wpmlm_buildTree1($user_details_matrix, $user_id);
    // print_r($tree_matrix);die('cbcj');
    $general = wpmlm_get_general_information(); 
    $tree_count_matrix = count($user_details_matrix);

    foreach ($tree_matrix as $key => $data_matrix) {

        if (is_array($data_matrix)) {
            for($jj=0;$jj<$tree_count_matrix;$jj++) { 
                $tree_matrix = flattenArray($tree_matrix);
            }
        }

    }
// print_r($tree_matrix);die('cbcj');
    $folders_arr = array();
    foreach ($tree_matrix as $us) {
        $parentid = $us->user_parent_id;    //For Sponsor Tree
        // $parentid = $us->father_id;           //For Geneology Tree
        if($parentid == '0') $parentid = "#";

        $selected = false;$opened = false;
        if($us->ID == 2){
            $selected = true;$opened = true;
        }
        $folders_arr[] = array(
            "id"=>$us->ID,
            "parent"=>$parentid,
            "text"=>$us->user_login,
            "state" => array(
                "selected" => $selected,
                "opened"=>$opened
            ) 
        );
    }
    
    
    ?>

    <div class="panel panel-default">
        
   <div id="folder_jstree" style="padding-bottom: 3%;padding-left: 2%;padding-top:1%;"></div>

   <!-- Store folder list in JSON format -->
   <textarea id='txt_folderjsondata'><?= json_encode($folders_arr) ?></textarea>
    </div>


    <script type="text/javascript">

    $(document).ready(function(){

        $('#txt_folderjsondata').hide();
        var folder_jsondata = JSON.parse($('#txt_folderjsondata').val());

        $('#folder_jstree').jstree({ 'core' : {
            'data' : folder_jsondata,
            'multiple': false
        }, });
      

              
          
    });
    </script>

<?php 
    echo '</div>';
}