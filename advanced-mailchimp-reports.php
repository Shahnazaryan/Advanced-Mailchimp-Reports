<?php
/**
 * Plugin Name:       Advanced MailChimp Reports
 * Description:       MailChimp Reports Dashboard
 * Version:           1.0
 * Author:            Gevorg
 * Text Domain:       Advaned MailChimp Reports
 * Domain Path:       /languages
 */


defined('ABSPATH') or die('Hey,what are you doing here?');


// Define plugin constants.admcre_menu

defined( 'ADMCRE_DIR' ) or define( 'ADMCRE_DIR', plugin_dir_path( __FILE__ ) );
defined( 'ADMCRE_URL' ) or define( 'ADMCRE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load includes.
 * =============================================
 */
// Load CSS and JS
include( plugin_dir_path(__FILE__) . 'includes/admcre_Style.php');
include (plugin_dir_path(__FILE__).'includes/admcre_Script.php');


// Load Class
ob_start();
include_once (ADMCRE_DIR.'class/admcre_random_data_info.php');
include_once (ADMCRE_DIR.'class/admcre_receive_database.php');
include_once (ADMCRE_DIR.'class/admcre_menu.php');
include_once (ADMCRE_DIR.'class/admcre_display_dashboard.php');
include_once (ADMCRE_DIR.'class/admcre_DB_analysis.php');
include_once (ADMCRE_DIR.'class/PMC_WP_List_Table.php');
include_once (ADMCRE_DIR.'class/admcre_mailchimpAPI.php');



ob_clean();


// Load includes
//include (ADMCRE_DIR.'includes/admcre_menu_setting.php');


global $admcre_db_version;
$admcre_db_version = '1.0';

function admcre_patrol_init_class(){
    global $admcre_create_menu,$admcre_create_display_dashboard;
    
    $admcre_create_menu = new admcre_menu();
    $admcre_create_menu->admcre_register();

    $admcre_create_display_dashboard = new admcre_display_dashboard();
    $admcre_create_display_dashboard ->admcre_dashboard_html();

}
add_action('init','admcre_patrol_init_class');

// Create Database Tables or Updating the Table

function admcre_createDatabase_install() {
    global $wpdb;
    global $admcre_db_version;

    $table_name_update = $wpdb->prefix . 'admcre_reports_update';
    $charset_collate = $wpdb->get_charset_collate();

    $sql_admcre_reports_update = "CREATE TABLE ". $table_name_update." (
        id int(11) NOT NULL AUTO_INCREMENT,
        web_id int NOT NULL,
        emails_sent tinyint NULL,
        mailchimp_id tinytext NOT NULL,
        subject_line tinytext NOT NULL,
        title tinytext NOT NULL,        
        open_rate float(24,3) NULL,
        click_rate float(24,3) NULL,      
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        )". $charset_collate;

    /*************************************************************************************/
    // sql_second to create your table

    $table_name_analysis = $wpdb->prefix . 'admcre_reports_analysis';

    $charset_collate = $wpdb->get_charset_collate();

    $sql_admcre_reports_analysis = "CREATE TABLE " .$table_name_analysis." (
        id mediumint(9) NOT NULL AUTO_INCREMENT,    
        campaign tinytext NOT NULL,  
        audience tinytext NOT NULL,
        subject tinytext NOT NULL,
        subscribed int(11) NULL,
        open_rate int(11) NULL,
        click_rate int(11) NULL,      
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        )". $charset_collate;
    /************************************************************************************/

    /*************************************************************************************/
    // sql_second to create your table

    $admcre_mailchimp_body = $wpdb->prefix . 'admcre_mailchimp_body';

    $charset_collate = $wpdb->get_charset_collate();

    $sql_admcre_mailchimp_body = "CREATE TABLE " .$admcre_mailchimp_body." (
        id int NOT NULL AUTO_INCREMENT,
        mailchimp_id tinytext NOT NULL,
        web_id int NOT NULL,
        type_id tinytext NOT NULL,
        create_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        archive_url tinytext NULL,
        long_archive_url text NULL,
        status tinytext NULL,
        emails_sent tinyint NULL,
        send_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        content_type tinytext NULL,
        needs_block_refresh boolean,
        has_logo_merge_tag boolean NULL,
        resendable boolean NULL,
        recipients_list_id tinytext NULL,
        recipients_list_is_active boolean NULL,
        recipients_list_name tinytext NULL,
        recipients_segment_text tinytext NULL,
        recipients_recipient_count tinyint NULL,
        settings_subject_line text NULL,
        settings_preview_text text NULL,
        settings_title text NULL,
        settings_from_name tinytext NULL,
        settings_reply_to tinytext NULL,
        settings_use_conversation boolean NULL,
        settings_to_name tinytext NULL,
        settings_folder_id tinytext NULL,
        settings_authenticate boolean NULL,
        settings_auto_footer boolean NULL,
        settings_inline_css boolean NULL,
        settings_auto_tweet boolean NULL,
        settings_fb_comments boolean NULL,
        settings_timewarp boolean NULL,
        settings_template_id int NULL,
        settings_drag_and_drop boolean NULL,
        tracking_opens tinyint NULL,
        tracking_html_clicks boolean NULL,
        tracking_text_clicks boolean NULL,
        tracking_goal_tracking boolean NULL,
        tracking_ecomm360 boolean NULL,
        tracking_google_analytics text NULL,
        tracking_clicktale text NULL,
        report_summary_opens float(24,3) NULL,
        report_summary_unique_opens float(24,3) NULL,
        report_summary_open_rate float(24,3) NULL,
        report_summary_clicks float(24,3) NULL,
        report_summary_subscriber_clicks float(24,3) NULL,
        report_summary_click_rate float(24,3) NULL,
        report_summary_ecommerce_total_orders int NULL,
        report_summary_ecommerce_total_spent int NULL,
        report_summary_ecommerce_total_revenue int NULL,
        delivery_status_enabled boolean,
        PRIMARY KEY  (id)
        )". $charset_collate;
    /************************************************************************************/

    /*************************************************************************************/
    $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
    $charset_collate = $wpdb->get_charset_collate();
    $sql_admcre_mailchimpAPIKEY = "CREATE TABLE " .$table_mailchimpAPIKEY." (
            id int(11) NOT NULL AUTO_INCREMENT,    
            getAPI_key tinytext NOT NULL, 
            getAPI_id  tinytext NULL,
            PRIMARY KEY  (id)
            )". $charset_collate;

    /*************************************************************************************/


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_admcre_reports_update );
    dbDelta( $sql_admcre_reports_analysis );
    dbDelta( $sql_admcre_mailchimp_body);
    dbDelta( $sql_admcre_mailchimpAPIKEY);

    add_option( 'admcre_db_version', $admcre_db_version );


////
/// Adding an Upgrade Function
    $installed_ver = get_option( "admcre_db_version" );

    if ( $installed_ver != $admcre_db_version ) {

        $table_name_update = $wpdb->prefix . 'admcre_reports_update';
        $charset_collate = $wpdb->get_charset_collate();
        $sql_admcre_reports_update = "CREATE TABLE ". $table_name_update." (
        id int(11) NOT NULL AUTO_INCREMENT,
        web_id int NOT NULL,
        emails_sent tinyint NULL,
        mailchimp_id tinytext NOT NULL,
        subject_line tinytext NOT NULL,
        title tinytext NOT NULL,        
        open_rate float(24,3) NULL,
        click_rate float(24,3) NULL,      
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
            )". $charset_collate;
        /*************************************************************************************/
        $table_name_analysis = $wpdb->prefix . 'admcre_reports_analysis';
        $charset_collate = $wpdb->get_charset_collate();
        $sql_admcre_reports_analysis = "CREATE TABLE " .$table_name_analysis." (
            id mediumint(9) NOT NULL AUTO_INCREMENT,    
            campaign tinytext NOT NULL,  
            audience tinytext NOT NULL,
            subject tinytext NOT NULL,
            subscribed int(11) NULL,
            open_rate int(11) NULL,
            click_rate int(11) NULL,      
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
            )". $charset_collate;

        /*************************************************************************************/
        /*************************************************************************************/
        // sql_second to create your table

        $admcre_mailchimp_body = $wpdb->prefix . 'admcre_mailchimp_body';

        $charset_collate = $wpdb->get_charset_collate();

        $sql_admcre_mailchimp_body = "CREATE TABLE " .$admcre_mailchimp_body." (
        id int NOT NULL AUTO_INCREMENT,
        mailchimp_id tinytext NOT NULL,
        web_id int NOT NULL,
        type_id tinytext NOT NULL,
        create_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        archive_url tinytext NULL,
        long_archive_url text NULL,
        status tinytext NULL,
        emails_sent tinyint NULL,
        send_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        content_type tinytext NULL,
        needs_block_refresh boolean,
        has_logo_merge_tag boolean NULL,
        resendable boolean NULL,
        recipients_list_id tinytext NULL,
        recipients_list_is_active boolean NULL,
        recipients_list_name tinytext NULL,
        recipients_segment_text tinytext NULL,
        recipients_recipient_count tinyint NULL,
        settings_subject_line text NULL,
        settings_preview_text text NULL,
        settings_title text NULL,
        settings_from_name tinytext NULL,
        settings_reply_to tinytext NULL,
        settings_use_conversation boolean NULL,
        settings_to_name tinytext NULL,
        settings_folder_id tinytext NULL,
        settings_authenticate boolean NULL,
        settings_auto_footer boolean NULL,
        settings_inline_css boolean NULL,
        settings_auto_tweet boolean NULL,
        settings_fb_comments boolean NULL,
        settings_timewarp boolean NULL,
        settings_template_id int NULL,
        settings_drag_and_drop boolean NULL,
        tracking_opens tinyint NULL,
        tracking_html_clicks boolean NULL,
        tracking_text_clicks boolean NULL,
        tracking_goal_tracking boolean NULL,
        tracking_ecomm360 boolean NULL,
        tracking_google_analytics text NULL,
        tracking_clicktale text NULL,
        report_summary_opens float(24,3) NULL,
        report_summary_unique_opens float(24,3) NULL,
        report_summary_open_rate float(24,3) NULL,
        report_summary_clicks float(24,3) NULL,
        report_summary_subscriber_clicks float(24,3) NULL,
        report_summary_click_rate float(24,3) NULL,
        report_summary_ecommerce_total_orders float(24,3) NULL,
        report_summary_ecommerce_total_spent float(24,3) NULL,
        report_summary_ecommerce_total_revenue float(24,3) NULL,
        delivery_status_enabled boolean,
        PRIMARY KEY  (id)
        )". $charset_collate;
        /************************************************************************************/

        /*************************************************************************************/
        $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
        $charset_collate = $wpdb->get_charset_collate();
        $sql_admcre_mailchimpAPIKEY = "CREATE TABLE " .$table_mailchimpAPIKEY." (
            id int(11) NOT NULL AUTO_INCREMENT,    
            getAPI_key tinytext NOT NULL,
            getAPI_id  tinytext NULL,
            PRIMARY KEY  (id)
            )". $charset_collate;

        /*************************************************************************************/

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_admcre_reports_update );
        dbDelta( $sql_admcre_reports_analysis );
        dbDelta( $sql_admcre_mailchimp_body );
        dbDelta( $sql_admcre_mailchimpAPIKEY );

        update_option( "admcre_db_version", $admcre_db_version );
    }
}
register_activation_hook( __FILE__, 'admcre_createDatabase_install' );
// Adding Initial Data
function admcre_createDatabase_install_data() {

    global $wpdb;
/*
    $table_name_1 = $wpdb->prefix . 'admcre_reports_1';
    $total_items_table_1 = $wpdb->get_var("SELECT COUNT(id) FROM $table_name_1");
*/
    $table_name_analysis = $wpdb->prefix . 'admcre_reports_analysis';
    $total_items_table_analysis = $wpdb->get_var("SELECT COUNT(id) FROM $table_name_analysis");


    $table_mailchimpAPIKEY = $wpdb->prefix . 'admcre_mailchimpAPIKEY';
    $total_items_table_mailchimpAPIKEY = $wpdb->get_var("SELECT COUNT(id) FROM $table_mailchimpAPIKEY");

    $admcre_random_data_info = new admcre_random_data_info();

    if($total_items_table_mailchimpAPIKEY == 0) {
        $wpdb->insert($table_mailchimpAPIKEY, array(
            'getAPI_key'  => 'getAPIKey',
            'getAPI_id'  => 0
        ));
    }
    if($total_items_table_analysis == 0){
        $fromDate = '2018-04-02';
        $toDate = '2019-07-02';

        for ($x = 0; $x<=50; $x++){

            $wpdb->insert(
                $table_name_analysis,
                array(
                    'campaign'      => $admcre_random_data_info->getNameCampaign(3),
                    'audience'      => $admcre_random_data_info->getName(5),
                    'subject'       => $admcre_random_data_info->getName(5),
                    'subscribed'    => rand(1,100),
                    'open_rate'     => rand(1,100),
                    'click_rate'    => rand(1,100),
                    'time'          => $admcre_random_data_info->randomDate($fromDate,$toDate,1)
                )
            );
        }
    }
}

register_activation_hook( __FILE__, 'admcre_createDatabase_install_data' );

//  Check the plugin db version
function admcre_plugin_update_db_check() {
    global $admcre_db_version;
    if ( get_site_option( 'admcre_db_version' ) != $admcre_db_version ) {
        admcre_createDatabase_install();
    }
}
add_action( 'plugins_loaded', 'admcre_plugin_update_db_check' );



/**
 *
 */


// The activation hook

function admcre_wp_cron_activation(){
    if( !wp_next_scheduled( 'admcre_wp_cron_add_every_one_minutes_event' ) ){
        wp_schedule_event( time(), 'every_one_minutes', 'admcre_wp_cron_add_every_one_minutes_event' );
    }
}

register_activation_hook(   __FILE__, 'admcre_wp_cron_activation' );

// The deactivation hook
function admcre_wp_cron_deactivation(){
    if( wp_next_scheduled( 'admcre_wp_cron_add_every_one_minutes_event' ) ){
        wp_clear_scheduled_hook( 'admcre_wp_cron_add_every_one_minutes_event' );
    }
}

register_deactivation_hook( __FILE__, 'admcre_wp_cron_deactivation' );


// The schedule filter hook
function admcre_wp_cron_add_every_one_minutes( $schedules ) {
    $schedules['every_one_minutes'] = array(
        'interval'  => 60*1,
        'display'   => __( 'Every 1 Minutes', 'textdomain' )
    );
    return $schedules;
}

add_filter( 'cron_schedules', 'admcre_wp_cron_add_every_one_minutes' );


// The WP Cron event callback function
function admcre_wp_cron_every_one_minutes_event_func() {
    $readMailchimpAPI = new admcre_mailchimpAPI();
    $campaigns = $readMailchimpAPI->admcre_mailchimp_api_request("campaigns", 'GET');
    $validation = $readMailchimpAPI->admcre_mailchimp_api_validation('campaigns', 'GET');
    if ($readMailchimpAPI->admcre_get_API_Key() !== 'getAPIKey' && $readMailchimpAPI->admcre_get_API_Key() !== "") {
        if ($validation['response']['code'] == 200) {
            global $wpdb;
            $table_name_update = $wpdb->prefix . 'admcre_reports_update';
            for ($x = 0; $x < count($campaigns->campaigns); $x++) {
                if ($campaigns->campaigns[$x]->status === 'sent') {
                    $wpdb->insert($table_name_update, array(
                        'web_id'        => $campaigns->campaigns[$x]->web_id,                       // get web id
                        'emails_sent'   => $campaigns->campaigns[$x]->emails_sent,                  // get sent email count
                        'mailchimp_id'  => $campaigns->campaigns[$x]->recipients->list_id,          // mail chimp list_id
                        'subject_line'  => $campaigns->campaigns[$x]->settings->subject_line,       // get subject title
                        'title'         => $campaigns->campaigns[$x]->settings->title,              // campaign name
                        'open_rate'     => $campaigns->campaigns[$x]->report_summary->open_rate,    // Open rate
                        'click_rate'    => $campaigns->campaigns[$x]->report_summary->click_rate,   // Click rate
                        'time'          => current_time('mysql'),
                    ));
                }
            }
        }
    }
}

add_action( 'admcre_wp_cron_add_every_one_minutes_event', 'admcre_wp_cron_every_one_minutes_event_func' );
