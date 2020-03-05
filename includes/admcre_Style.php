<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 11.10.2019
 * Time: 0:07
 */

//Load CSS on all admin pages

function admcre_admin_styles(){
    wp_enqueue_style(
        'custom-admin',
        ADMCRE_URL.'admin/css/admcre-admin-style.css',
        [],
        time()
    );
    wp_enqueue_style(
        'bootstrap-admin',
        ADMCRE_URL.'admin/css/bootstrap.min.css',
        [],
        time()
    );
    wp_enqueue_style(
        'dashboard-admin',
        ADMCRE_URL.'admin/css/custom.css',
        [],
        time()
    );
}
add_action('admin_enqueue_scripts','admcre_admin_styles');


// Load CSS on the frontend

function admcre_frontend_stylrs(){
    wp_enqueue_style(
        'wpplugin-frontend',
        ADMCRE_URL.'frontend/css/admcre-frontend-style.css',
        [],
        time()
    );
}
add_action('wp_enqueue_scripts','admcre_frontend_stylrs',100);