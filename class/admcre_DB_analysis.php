<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class admcre_DB_analysis extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'report',
            'plural' => 'reports',
        ));
    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * [OPTIONAL] How to render specific column
     *
     * method name must be like this: "column_[column_name]"
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_age($item)
    {
        return '<em>' . $item['age'] . '</em>';
    }

    /**
     * [OPTIONAL] How to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
 
        $actions = array(
            'edit' => sprintf('<a href="?page=persons_form&id=%s">%s</a>', $item['id'], __('Edit', 'admcre_table_analysis')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'admcre_table_analysis')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] How checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
            'campaign'      => __('Campaign', 'admcre_table_analysis'),
            'audience'      => __('Audience', 'admcre_table_analysis'),
            'subject'       => __('Subject', 'admcre_table_analysis'),
            'subscribed'    => __('Subscribed', 'admcre_table_analysis'),
            'open_rate'     => __('Open rate', 'admcre_table_analysis'),
            'click_rate'    => __('Click rate', 'admcre_table_analysis'),
            'time'          => __('Date Time', 'admcre_table_analysis'),
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'campaign'      => array('campaign', true),
            'audience'      => array('audience', false),
            'subject'       => array('subject', false),
            'subscribed'    => array('subscribed', false),
            'open_rate'     => array('open_rate', false),
            'click_rate'    => array('click_rate', false),
            'time'          => array('time', false),

        );
        return $sortable_columns;
    }


    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'admcre_reports_analysis';

        $per_page = 5; // constant, how much records will be shown per page
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

 

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'campaign';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
    /**
     * @param $num_varebel should be column name with integer
     * @return array which column is maximum in the data
     */
    function get_maximum_numbervalue_array($num_varebel){
        global $wpdb;
        $table_name = $wpdb->prefix . 'admcre_mailchimp_body';
        $results = $wpdb->get_results( "SELECT * FROM $table_name");
        $start=0;
        foreach ($results as $rows){
            if($rows->$num_varebel > $start){
                $start = $rows->$num_varebel;
                $openRateMaxId = $rows->id;
            }
        }
        return $results[$openRateMaxId-1];
    }

    function prepare_items1() {
        global $wpdb;
        $get_table =$wpdb->prefix . 'admcre_reports_analysis';

        $per_page = 10;
        $current_page = $this->get_pagenum();

        if ( 1 < $current_page ) {
            $offset = $per_page * ( $current_page - 1 );
        } else {
            $offset = 0;
        }
        $search = '';
        //Retrieve $customvar for use in query to get items.
        if ( ! empty( $_REQUEST['s'] ) ) {
            $search = "AND campaign LIKE '%" . esc_sql( $wpdb->esc_like( $_REQUEST['s'] ) ) . "%'";
        }
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'campaign';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $items = $wpdb->get_results( "SELECT * FROM ".$get_table." WHERE 1=1 {$search}" . $wpdb->prepare( "ORDER BY $orderby $order LIMIT %d OFFSET %d;", $per_page, $offset ),ARRAY_A);
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $count = $wpdb->get_var( "SELECT COUNT(id) FROM ".$get_table." WHERE 1 = 1 {$search} " );

        $this->items = $items;

        // Set the pagination
        $this->set_pagination_args( array(
            'total_items' => $count,
            'per_page'    => $per_page,
            'total_pages' => ceil( $count / $per_page )
        ) );
    }

    function get_top_five_by_column($columnName,$topNumber){
        global $wpdb;
        $get_table =$wpdb->prefix . 'admcre_mailchimp_body';
        //$get_table =$wpdb->prefix . 'admcre_reports_analysis';

        // notice that last argument is ARRAY_A, so we will retrieve array
        $results_plot = $wpdb->get_results("SELECT * FROM $get_table ORDER BY $columnName DESC LIMIT $topNumber");

        $openRate_all = '';
        $clickRate_all = '';
        $dates = '';
        foreach ($results_plot as $row){
            $opa1 = $row->report_summary_open_rate;
            $opa2 = $row->report_summary_click_rate;
            //$date = $row->campaign;
            $date = $row->settings_title;

            $dates = $dates.'"'.$date.'",';
            $openRate_all = $openRate_all.$opa1.',';
            $clickRate_all = $clickRate_all.$opa2.',';
        }

        $dates = trim($dates,",");
        $openRate_all = trim($openRate_all,",");
        $clickRate_all = trim($clickRate_all,",");

        $getresults = [$dates,$openRate_all,$clickRate_all];

        return $getresults;
        //return $results_plot;

    }
    protected function get_views() {
        $views = array();
        $current = ( !empty($_REQUEST['customvar']) ? $_REQUEST['customvar'] : 'all');

        //All link
        $class = ($current == 'all' ? ' class="current"' :'');
        $all_url = remove_query_arg('customvar');
        $views['all'] = "<a href='{$all_url }' {$class} >All</a>";
        /*
                //Recovered link
                $foo_url = add_query_arg('customvar','recovered');
                $class = ($current == 'recovered' ? ' class="current"' :'');
                $views['recovered'] = "<a href='{$foo_url}' {$class} >Recovered</a>";

                //Abandon
                $bar_url = add_query_arg('customvar','abandon');
                $class = ($current == 'abandon' ? ' class="current"' :'');
                $views['abandon'] = "<a href='{$bar_url}' {$class} >Abandon</a>";
        */
        return $views;
    }


    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */

    function get_bulk_actions()
    {
        $actions = array(
            'Show' => 'Show'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function get_rows_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'admcre_reports_analysis';
        $results = $wpdb->get_results( "SELECT * FROM $table_name");
        if ('Show' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            for ($j = 0; $j < count($ids); $j++){
                for ($i= 0; $i< count($results); $i++){
                    if ( $results[$i]->id === $ids[$j]){
                        $rowid[$j] = $results[$i];
                    }
                }
            }
            return $rowid;
        }
    }

    function filter_plot(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'admcre_reports_analysis';
        $results = $wpdb->get_results( "SELECT * FROM $table_name");

        $selected  = $this->get_rows_action();
        $dates = '';
        $openRate_all = '';
        $clickRate_all = '';

        if ('Show' === $this->current_action()) {
            for($y = 0; $y <count($selected); $y++) {
                $j=0;
                for ($x = 0; $x < count($results); $x++) {
                    if ($results[$x]->campaign == $selected[$y]->campaign) {
                        $selectedAll[$j] = $results[$x];
                        $j++;
                    }
                }
                foreach ($selectedAll as $row) {
                    $opa1 = $row->open_rate;
                    $opa2 = $row->click_rate;
                    $date = $row->time;

                    $dates = $dates . '"' . $date . '",';
                    $openRate_all = $openRate_all . $opa1 . ',';
                    $clickRate_all = $clickRate_all . $opa2 . ',';
                }
                $campaignName[$y] = $selected[$y]->campaign;
                $openRate[$y] = $openRate_all;
                $clickRate[$y] = $clickRate_all;
            }
            $dates = trim($dates, ",");
            for ($x = 0; $x<count($openRate);$x++){
                $openRate[$x] = trim($openRate[$x], ",");
                $clickRate[$x] = trim($clickRate[$x], ",");
            }
            $getresults = [$dates, $openRate, $clickRate];

          //  return $getresults;
            ?>
            <h1 class="profile-title">Ads of Selected Campaign by Time</h1>
            <canvas id="admcreChart_time"></canvas>
            <script>
                var ctx = document.getElementById('admcreChart_time').getContext('2d');
                var admcrechartPlotTime = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',
                    // The data for our dataset
                    data: {
                        labels: [<?php echo $getresults[0];?>],
                        datasets: [
                            <?php for ($x = 0; $x< count($getresults[1]);$x++){?>

                            {
                                label: '<?php echo $campaignName[$x] ?> Campaign Open Rate',
                                backgroundColor: 'rgba(<?php echo rand(0,255)?>, <?php echo rand(0,255)?>, <?php echo rand(0,255)?>,0.5)',
                                borderColor: 'rgb(<?php echo rand(0,255)?>,<?php echo rand(0,255)?>,<?php echo rand(0,255)?>)',
                                data: [<?php echo $getresults[1][$x]; ?>]
                            },
                            {
                                label: '<?php echo $campaignName[$x] ?> Campaign Click Rate',
                                backgroundColor: 'rgba(<?php echo rand(0,255)?>, <?php echo rand(0,255)?>, <?php echo rand(0,255)?>,0.5)',
                                borderColor: 'rgb(<?php echo rand(0,255)?>,<?php echo rand(0,255)?>,<?php echo rand(0,255)?>)',
                                data: [<?php echo $getresults[2][$x]; ?>]
                            },
                            <?php } ?>
                        ]
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

            <?php
        }
    }
////////////////////////////////////////////////////////////////





///

}