<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 15.10.2019
 * Time: 2:52
 */

class admcre_menu
{
    function admcre_menus()
    {
        add_menu_page(
            __('Advanced Mailchimp Reports', 'admcre_table_analysis'),
            __('Advanced Mailchimp Reports', 'admcre_table_analysis'),
            'activate_plugins',
            'advanced-mailchimp-reports',
            'admcre_dashboard',
            'dashicons-dashboard'
        );
        add_submenu_page(
            'advanced-mailchimp-reports',
            __('MailChimp Analysis', 'admcre_table_analysis'),
            __('MailChimp Analysis', 'admcre_table_analysis'),
            'activate_plugins',
            'mailchimp-analysis',
            'admcre_table_analysis_dashboard'

        );
        // add new will be described in next part
        add_submenu_page(
            'advanced-mailchimp-reports',
            __('Settings', 'admcre_table_analysis'),
            __('Settings', 'admcre_table_analysis'),
            'activate_plugins',
            'reports_tools_form',
            'admcre_table_analysis_dashboard_setting'
        );
    }

    function admcre_register(){
        add_action('admin_menu',array($this,'admcre_menus'));
    }


}
