<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 11.10.2019
 * Time: 0:45
 */
// Load JS on all admin pages

function admcre_admin_script(){
    wp_enqueue_script(
        'custom-admin',
        ADMCRE_URL.'admin/js/admcre-admin-script.js',
        ['jquery'],
        time()
    );
    wp_enqueue_script(
        'cart-admin',
        ADMCRE_URL.'admin/js/bootstrap.min.js',
        ['jquery'],
        time()
    );
    wp_enqueue_script(
        'dashboard-admin',
        ADMCRE_URL.'admin/js/fontawesome.js',
        ['jquery'],
        time()
    );
    wp_enqueue_script(
        'feather-admin',
        ADMCRE_URL.'admin/js/jquery-3.1.1.slim.min.js',
        ['jquery'],
        time()
    );
    wp_enqueue_script(
        'Chart-admin',
        ADMCRE_URL.'admin/js/Chart.bundle.min.js',
        ['jquery'],
        time()
    );
}
add_action('admin_enqueue_scripts','admcre_admin_script');

// Load JS on the frontend

function admcre_frontend_scripts(){
    wp_enqueue_script(
        'wpplugin-frontend',
        ADMCRE_URL.'frontend/js/admcre-frontend-script.js',
        [],
        time()
    );
}
add_action('wp_enqueue_scripts','admcre_frontend_scripts');