<?php

function wpmlm_user_income_details($user_id = '') {
    $results = wpmlm_get_leg_amount_details_by_user_id($user_id);
    $results1 = wpmlm_get_total_leg_amount_by_user_id($user_id);
    $result_count = count($results);
    $result2 = wpmlm_get_general_information();
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span><?php _e('Bonus Details','wpmlm-unilevel'); ?></span></h4>
                    
                </div>
                <div  id="profile_print_area" style="overflow: auto; padding: 10px;" class="report-data" >
                    <?php if ($result_count > 0) { ?>                        
                        <table id="user-income-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php _e('Username','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Amount Type','wpmlm-unilevel'); ?></th>                                    
                                    <th><?php _e('Date','wpmlm-unilevel'); ?></th>
                                    <th><?php _e('Amount','wpmlm-unilevel'); ?></th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>

                                    <th  colspan="4" style="text-align: right;"><?php _e('Total Amount','wpmlm-unilevel'); ?></th>
                                    <th><?php echo $result2->company_currency . ' ' . $results1->total_amount; ?></th>
                                </tr>
                            </tfoot>

                            <tbody>
                                <?php
                                $count = 0;
                                foreach ($results as $res) {

                                    $count++;
                                    echo '<tr>
            <th scope="row">' . $count . '</th>
            <td>' . $res->user_login . '</td>
            <td>' . ucwords(str_replace("_", " ", $res->amount_type)) . '</td>
            <td>' . date("Y/m/d", strtotime($res->date_of_submission)) . '</td>
                <td>' . $result2->company_currency . ' ' . $res->total_amount . '</td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                        <?php
                    } else {
                        echo '<div class="no-data">' . __("No Data","wpmlm-unilevel") . ' </div>';
                    }
                    ?>
                </div>

            </div>
        </div>

    </div> 
    <script>
        jQuery(document).ready(function ($) {
            $('#user-income-table').DataTable({
                "pageLength": 10,
                "bFilter": false
            });
        });

    </script>
    <?php
}