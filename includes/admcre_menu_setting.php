<?php
/**
 * Admin page
 * ============================================================================
 * admin_menu hook implementation, will add pages to list persons and to add new one
 */
function admcre_menu()
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
add_action('admin_menu','admcre_menu');

function admcre_dashboard(){

    $table = new admcre_receive_database();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'admcre_table_analysis'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
    <div class="wrap">

        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php _e('Mailchimp Dashboard', 'admcre_table_analysis')?>
            <a class="add-new-h2"
               href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=mailchimp-analysis');?>">
                <?php _e('Mailchimp Analysis', 'admcre_table_analysis')?>
            </a>
        </h2>
        <?php echo $message; ?>

        <form id="persons-table" method="GET">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php $table->display() ?>
        </form>

    </div>
    <?php
}
