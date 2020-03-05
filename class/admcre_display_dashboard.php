<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 15.10.2019
 * Time: 3:03
 * 
 */

class admcre_display_dashboard extends admcre_menu
{

    function __construct()
    {

    }
    function admcre_dashboard_html()
    {
        function admcre_dashboard()
        {

            $table_DB = new admcre_DB_analysis();
            $table_DB->prepare_items();
            $getMaxRow_openRate = $table_DB->get_maximum_numbervalue_array('report_summary_open_rate');

           // $getMaxRow_clickRate = $table_DB->get_maximum_numbervalue_array('click_rate');
            echo '<h1>Barev</h1>';
            echo '<pre>';print_r($getMaxRow_openRate);echo '</pre>';
            ?>
            <div class="wrap">
                <h1> Dashboard</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="well profile">
                            <div class="col-sm-12">
                                <div class="col-xs-12 col-sm-12">
                                    <h1 class="profile-title"> Top Campaign by Open Rate</h1>
                                    <h2 class="profile-capaign"> <?php echo $getMaxRow_openRate->settings_title ?></h2>
                                    <p><strong>Subject: </strong> <?php echo $getMaxRow_openRate->settings_subject_line ?></p>
                                    <p><strong>Open Rate: </strong> <?php echo ($getMaxRow_openRate->report_summary_open_rate*100).'%' ?></p>
                                    <p><strong>Send Time: </strong>
                                        <span class="tags"><?php echo $getMaxRow_openRate->send_time ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="well profile">
                            <?php $topClickRate = $table_DB->get_top_five_by_column('report_summary_open_rate', '4'); ?>
                            <h1 class="profile-title">Top 5 Campaign by Open Rate </h1>
                            <canvas id="admcreChart_ClickRate"></canvas>
                            <script>
                                var ctx = document.getElementById('admcreChart_ClickRate').getContext('2d');
                                var admcrechart = new Chart(ctx, {
                                    // The type of chart we want to create
                                    type: 'bar',
                                    // The data for our dataset
                                    data: {
                                        labels: [ <?php echo $topClickRate[0]?>],
                                        datasets: [{
                                            label: 'Open Rate',
                                            backgroundColor: 'rgba(69, 92, 105,0.5)',
                                            //     borderColor: '#45ff73',
                                            data: [<?php echo $topClickRate[1] ?>] // Open rate
                                        }, {
                                            label: 'Click Rate',
                                            backgroundColor: 'blue',
                                            //   borderColor: 'blue',
                                            data: [<?php echo $topClickRate[2] ?>] // Click rate
                                        }]
                                    },
                                    // Configuration options go here
                                    options: {
                                        legend: {
                                            display: true,
                                            position: 'bottom',
                                            labels: {
                                                fontColor: 'red',
                                            }
                                        },
                                        tooltips: {
                                            mode: 'x',
                                        },
                                        scales: {
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: false,
                                                }
                                            }],
                                            xAxes: [{
                                                ticks: {
                                                    autoSkip: true,
                                                    maxTicks: 2,
                                                }
                                            }]
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="well profile">
                            <?php $topClickRate = $table_DB->get_top_five_by_column('click_rate', '4'); ?>
                            <h1 class="profile-title">Top 5 Campaign by Click Rate </h1>
                            <canvas id="admcreChart_OpenRate"></canvas>
                            <script>
                                var ctx = document.getElementById('admcreChart_OpenRate').getContext('2d');
                                var admcrechart = new Chart(ctx, {
                                    // The type of chart we want to create
                                    type: 'horizontalBar',
                                    // The data for our dataset
                                    data: {
                                        labels: [ <?php echo $topClickRate[0]?>],
                                        datasets: [{
                                            label: 'Open Rate',
                                            backgroundColor: 'rgba(69, 92, 105,0.5)',
                                            //    borderColor: '#45ff73',
                                            data: [<?php echo $topClickRate[1] ?>] // Open rate
                                        }, {
                                            label: 'Click Rate',
                                            backgroundColor: 'blue',
                                            //    borderColor: 'blue',
                                            data: [<?php echo $topClickRate[2] ?>] // Click rate
                                        }]
                                    },
                                    // Configuration options go here
                                    options: {
                                        legend: {
                                            display: true,
                                            position: 'bottom',
                                            labels: {
                                                fontColor: 'red',
                                            }
                                        },
                                        tooltips: {
                                            mode: 'y',
                                        },
                                        scales: {
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: false,
                                                }
                                            }],
                                            xAxes: [{
                                                ticks: {
                                                    autoSkip: true,
                                                    maxTicks: 2,
                                                }
                                            }]
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>

                </div>
            </div>
            <div class="wrap">
                <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                <h2 class="custem-header-title"><?php _e('Table List', 'admcre_table_analysis') ?>
                    <a class="add-new-h2"
                       href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=mailchimp-analysis'); ?>">
                        <?php _e('Mailchimp Analysis', 'admcre_table_analysis') ?>
                    </a>
                </h2>
                <?php $table_DB->prepare_items1(); ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                <form method="post">
                    <input type="hidden" name="page" value="pmc_fs_search">
                    <?php $table_DB->views(); ?>
                    <?php $table_DB->search_box('search', 'search_id'); ?>
                    <?php $table_DB->display(); ?>
                </form>
            </div>

            <div class="wrap">
                <?php $table_DB->filter_plot(); ?>
            </div>

            <?php

            global $wpdb;

            $table_name_plot = $wpdb->prefix . 'admcre_reports_analysis';

            $results_plot = $wpdb->get_results("SELECT * FROM $table_name_plot");
            $opa1_all = '';
            $opa2_all = '';
            $dates = '';
            foreach ($results_plot as $row) {
                $opa1 = $row->open_rate;
                $opa2 = $row->click_rate;
                $date = $row->time;

                $dates = $dates . '"' . $date . '",';
                $opa1_all = $opa1_all . $opa1 . ',';
                $opa2_all = $opa2_all . $opa2 . ',';
            }
            $dates = trim($dates, ",");
            $opa1_all = trim($opa1_all, ",");
            $opa2_all = trim($opa2_all, ",");


            ?>

            <div class="wrap">
                <!---
                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                -->
                <h1>Hello Everyone-1</h1>
                <canvas id="admcreChart_1"></canvas>
                <script>
                    var ctx = document.getElementById('admcreChart_1').getContext('2d');
                    var admcrechart1 = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        // The data for our dataset
                        data: {
                            labels: [<?php echo $dates?>],
                            datasets: [{
                                label: 'My First dataset',
                                backgroundColor: 'rgba(69, 92, 105,0.5)',
                                borderColor: '#45ff73',
                                data: [<?php echo $opa1_all ?>]
                            }, {
                                label: 'My First dataset second',
                                backgroundColor: 'blue',
                                borderColor: 'blue',
                                data: [<?php echo $opa2_all ?>]
                            }]
                        },
                        // Configuration options go here
                        options: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    fontColor: 'red',
                                }
                            },
                            tooltips: {
                                mode: 'x',
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: false,
                                    }
                                }],
                                xAxes: [{
                                    ticks: {
                                        autoSkip: true,
                                        maxTicks: 2,
                                    }
                                }]
                            }
                        }
                    });
                </script>
            </div>


            <?php

        }

        function admcre_table_analysis_dashboard()
        {
            $table = new admcre_receive_database();
            $table->prepare_items();
            $message = '';
            ?>

            <div class="wrap">

                <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                <h2 class="custem-header-title"><?php _e('MailChimp Dashboard', 'admcre_table_analysis') ?>
                    <a class="add-new-h2"
                       href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=mailchimp-analysis'); ?>">
                        <?php _e('Mailchimp Analysis', 'admcre_table_analysis') ?>
                    </a>
                </h2>
                <?php echo $message; ?>

                <form id="persons-table" method="GET">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                    <?php $table->display() ?>
                </form>

            </div>

            <div class="wrap">
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . 'admcre_reports_analysis';
                ?>
                <h2><?php _e('Second Date Table List', 'admcre_table_analysis') ?></h2>
                <p><?php _e('Type something in the input field to search the table for names', 'admcre_table_analysis') ?></p>
                <input id="admcre-table-search" type="text" placeholder="Search..">
                <br><br>

                <?php $results = $wpdb->get_results("SELECT * FROM $table_name"); ?>
                <?php if (!empty($results)) { ?>

                    <table class="admcre-table">
                        <thead class="admcre-table-head">
                        <tr class="admcre-tr">
                            <th class="admcre-th">Campaign</th>
                            <th class="admcre-th">Audience</th>
                            <th class="admcre-th">Subject</th>
                            <th class="admcre-th">Subscribed</th>
                            <th class="admcre-th">Open Rate</th>
                            <th class="admcre-th">Click Rate</th>
                            <th class="admcre-th">Date Time</th>
                        </tr>
                        </thead>
                        <tbody id="admcre-table-search">
                        <?php foreach ($results as $row) { ?>
                            <?php /*$userip = $row->user_ip; */ ?>
                            <tr class="admcre-tr">
                                <td class="admcre-td"><?php echo $row->campaign ?></td>
                                <td class="admcre-td"><?php echo $row->audience ?></td>
                                <td class="admcre-td"><?php echo $row->subject ?></td>
                                <td class="admcre-td"><?php echo $row->subscribed ?></td>
                                <td class="admcre-td"><?php echo $row->open_rate ?></td>
                                <td class="admcre-td"><?php echo $row->click_rate ?></td>
                                <td class="admcre-td"><?php echo $row->time ?></td>

                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <div class="wrap">
                <h1> Hello - 2</h1>
                <?php $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name"); ?>
                <h2><?php echo $total_items ?> echo</h2>
            </div>


            <?php
        }

        function admcre_table_analysis_dashboard_setting()
        {
            $readMailchimpAPI = new admcre_mailchimpAPI();
            ?>

            <h1> Mailchimp for WordPress: API Settings </h1>

            <!------------------------------------------------------------------------------------->


            <?php if ($readMailchimpAPI->admcre_get_API_Key() === 'getAPIKey') { ?>
            <form method="post">
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row"> <?php _e('Status', 'admcre_table_analysis') ?></th>
                        <td>
                            <span class="status"
                                  style="background: red;color:white;font-size: 20px;"><?php _e('DISCONNECT', 'admcre_table_analysis') ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="mailchimp_API_key"><?php _e('API Key', 'admcre_table_analysis') ?></label>
                        </th>
                        <td>
                            <input type="text" class="widefat"
                                   placeholder="<?php echo esc_attr('Your Mailchimp API key') ?>" id="mailchimp_API_key"
                                   name="api_key" value="" style="width: 50%;">
                            <p class="help"><?php _e('The API key for connecting with your Mailchimp account.', 'admcre_table_analysis') ?>
                                <a href="https://admin.mailchimp.com/account/api"><?php _e('Get your API key here.', 'admcre_table_analysis') ?></a>
                            </p>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <button type="submit" name="save_changes" class="btn btn-primary"><?php _e('Save Changes', 'admcre_table_analysis') ?></button>

            </form>

            <?php } ?>


            <!-------------------------------------------------------------------------------------------->
            <?php if ($readMailchimpAPI->admcre_get_API_Key() === "") { ?>
            <form method="post">
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row"> <?php _e('Status', 'admcre_table_analysis') ?></th>
                        <td>
                            <span class="status"
                                  style="background: grey;color:white;font-size: 20px;"><?php _e('NOT CONNECTED', 'admcre_table_analysis') ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="mailchimp_API_key"><?php _e('API Key', 'admcre_table_analysis') ?></label>
                        </th>
                        <td>
                            <input type="text" class="widefat"
                                   placeholder="<?php echo esc_attr('Your Mailchimp API key') ?>" id="mailchimp_API_key"
                                   name="api_key" value="" style="width: 50%;">
                            <p class="help" style="color:red;"><?php _e('The given value does not look like a valid Mailchimp API key.','admcre_table_analysis')?></p>

                            <p class="help"><?php _e('The API key for connecting with your Mailchimp account.', 'admcre_table_analysis') ?>
                                <a href="https://admin.mailchimp.com/account/api"><?php _e('Get your API key here.', 'admcre_table_analysis') ?></a>
                            </p>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <button type="submit" name="save_changes" class="btn btn-primary"><?php _e('Save Changes', 'admcre_table_analysis') ?></button>

            </form>

        <?php } ?>
            <!----------------------------------------------->
            <?php if ($readMailchimpAPI->admcre_get_API_Key() !== 'getAPIKey' && $readMailchimpAPI->admcre_get_API_Key() !== "") { ?>
                <?php $validation = $readMailchimpAPI->admcre_mailchimp_api_validation('campaigns', 'GET'); ?>
                <?php $campaigns = $readMailchimpAPI->admcre_mailchimp_api_request("campaigns", 'GET'); ?>
                <?php if ($validation['response']['code'] == 401) { ?>

                    <form method="post">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row"> <?php _e('Status', 'admcre_table_analysis') ?></th>
                            <td>
                            <span class="status"
                                  style="background: grey;color:white;font-size: 20px;"><?php _e('NOT CONNECTED', 'admcre_table_analysis') ?></span>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mailchimp_API_key"><?php _e('API Key', 'admcre_table_analysis') ?></label>
                            </th>
                            <td>
                                <input type="text" class="widefat"
                                       placeholder="<?php echo esc_attr('Your Mailchimp API key') ?>" id="mailchimp_API_key"
                                       name="api_key" value="" style="width: 50%";>
                                <p class="help" style="color: red"><?php _e('The given value does not look like a valid Mailchimp API key.','admcre_table_analysis')?></p>

                                <p class="help"><?php _e('The API key for connecting with your Mailchimp account.', 'admcre_table_analysis') ?>
                                    <a href="https://admin.mailchimp.com/account/api"><?php _e('Get your API key here.', 'admcre_table_analysis') ?></a>
                                </p>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <button type="submit" name="save_changes" class="btn btn-primary"><?php _e('Save Changes', 'admcre_table_analysis') ?></button>

                </form>
                <?php } ?>


                <?php if ($validation['response']['code'] == 200) { ?>
                <?php
                global $wpdb;
                $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
                $getAPI_id = $wpdb->get_results("SELECT getAPI_id FROM $table_mailchimpAPIKEY");
                if ($getAPI_id[0]->getAPI_id == 0)
                    $readMailchimpAPI->admcre_inputSQL_DB_mailchimp_campaigns();
                ?>
                <h3>Congratulations! It connected successfully</h3>
                <form method="post">
                    <table class="form-table ">
                        <tbody>
                        <tr valign="top">
                            <th scope="row"> <?php _e('Status', 'admcre_table_analysis') ?></th>
                            <td>
                                <span class="status"
                                      style="background: green;color:white;font-size: 20px;">
                                    <?php _e('CONNECTED', 'admcre_table_analysis') ?>
                                </span>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mailchimp_API_key"><?php _e('API Key', 'admcre_table_analysis') ?></label>
                            </th>
                            <td>
                                <input type="text" class="widefat"
                                       placeholder="<?php echo $readMailchimpAPI->admcre_get_API_Key() ?>"
                                       id="mailchimp_API_key" name="api_key" value="" style="width: 50%;">
                                <p class="help"><?php _e('The API key for connecting with your Mailchimp account.', 'admcre_table_analysis') ?>
                                    <a href="https://admin.mailchimp.com/account/api"><?php _e('Get your API key here.', 'admcre_table_analysis') ?></a>
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="submit" name="save_changes"
                            class="btn btn-primary"><?php _e('Save Changes', 'admcre_table_analysis') ?></button>
                </form>
                <!-------------------------------------------------------------------->

                <!---------------------------------------------------------------------------------->
                <div class="wrap">
                    <h1>Your Mailchimp Account</h1>
                    <p>The table below shows your Mailchimp lists and their details. If you just applied changes to
                        your Mailchimp lists, please use the following button to renew the cached lists
                        configuration.</p>
                    <?php
                    if (isset($_POST['insert'])) {
                        $message = "Done! Mailchimp lists renewed. \n";
                        echo $readMailchimpAPI->admcre_mailchimp_DB_update();
                    }
                    ?>
                    <form method="post">
                        <input class="btn btn-primary" type="submit" name="insert" value="Renew Mailchimp lists">
                        <?php if (isset($message)) {
                            echo '<span style="color: limegreen;">'.$message.'</span>';
                        } ?>
                    </form>


                    <p>A total of 1 lists were found in your Mailchimp account.</p>
                    <table class="table table-striped custab">
                        <thead>
                        <tr>
                            <th>List Name</th>
                            <th>ID</th>
                            <th>Subscribers</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="color: deepskyblue;"><?php echo $campaigns->campaigns[0]->recipients->list_name ?></td>
                            <td style="color: red"><?php echo $campaigns->campaigns[0]->recipients->list_id ?></td>
                            <td style="font-weight: bolder"><?php echo $campaigns->campaigns[0]->recipients->recipient_count ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
        <?php
        } ?>
            <?php
            /*
            echo '<p>Report Web_id</p>';
            echo $campaigns->campaigns[1]->web_id;
            echo '<p>Report emails_sent</p>';
            echo $campaigns->campaigns[1]->emails_sent;
            echo '<p>Report list_id</p>';
            echo $campaigns->campaigns[1]->recipients->list_id;
            echo '<p>Report Subject_line</p>';
            echo $campaigns->campaigns[1]->settings->subject_line;
            echo '<p>Report Subject Title</p>';
            echo $campaigns->campaigns[1]->settings->title;
            echo '<p>Report Open Rate</p>';
            echo $campaigns->campaigns[1]->report_summary->open_rate;
            echo '<p>Report Click Rate</p>';
            echo $campaigns->campaigns[1]->report_summary->click_rate;
            echo '<pre>';print_r($campaigns->campaigns); echo '</pre>';
*/



        }
    }
}
