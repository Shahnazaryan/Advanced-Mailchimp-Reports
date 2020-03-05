<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 20.10.2019
 * Time: 1:13
 */
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class PMC_WP_List_Table extends WP_List_Table
{

    function __construct(){
        parent::__construct( array(
            'ajax'      => false        //does this table support ajax?
        ) );

    }


    /**
     * Add columns to grid view
     */
    function get_columns(){
        $columns = array(
            'campaign' => 'Name',
            'audience'    => 'Last Name',
            'subject'      => 'Email',
            'subscribed'	=> 'Action'
        );
        return $columns;
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id':
            case 'campaign':
            case 'audience':
            case 'subject':
            case 'subscribed':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    protected function get_views() {
        $views = array();
        $current = ( !empty($_REQUEST['customvar']) ? $_REQUEST['customvar'] : 'all');

        //All link
        $class = ($current == 'all' ? ' class="current"' :'');
        $all_url = remove_query_arg('customvar');
        $views['all'] = "<a href='{$all_url }' {$class} >All</a>";

        return $views;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'action'  => array('action',false),
        );
        return $sortable_columns;
    }

    /**
     * Prepare admin view
     */
    function prepare_items1() {
        global $wpdb;
        $asas =$wpdb->prefix . 'admcre_reports_analysis';

        $per_page = 50;
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

        $items = $wpdb->get_results( "SELECT * FROM ".$asas." WHERE 1=1 {$search}" . $wpdb->prepare( "ORDER BY id DESC LIMIT %d OFFSET %d;", $per_page, $offset ),ARRAY_A);

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $count = $wpdb->get_var( "SELECT COUNT(id) FROM ".$asas." WHERE 1 = 1 {$search} " );

        $this->items = $items;

        // Set the pagination
        $this->set_pagination_args( array(
            'total_items' => $count,
            'per_page'    => $per_page,
            'total_pages' => ceil( $count / $per_page )
        ) );
    }

}