<?php

namespace WpChartMaker\Classes;


class Menu
{
    public function register()
    {
        add_action( 'admin_menu', array($this, 'addMenus') );
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
    }
    public function addMenus()
    {
        $menuPermission = AccessControl::hasTopLevelMenuPermission();
        if (!$menuPermission) {
            return;
        }

        $title = __('Chart Maker', 'wpchartmaker');
        global $submenu;
        add_menu_page(
            $title,
            $title,
            $menuPermission,
            'wpchartmaker.php',
            array($this, 'render'),
            'dashicons-chart-line',
            25
        );

        $submenu['wpchartmaker.php']['all_charts'] = array(
            __('All Charts', 'wpchartmaker'),
            $menuPermission,
            'admin.php?page=wpchartmaker.php#/',
        );
        $submenu['wpchartmaker.php']['settings'] = array(
            __('Settings', 'wpchartmaker'),
            $menuPermission,
            'admin.php?page=wpchartmaker.php#/settings',
        );
        $submenu['wpchartmaker.php']['supports'] = array(
            __('Supports', 'wpchartmaker'),
            $menuPermission,
            'admin.php?page=wpchartmaker.php#/supports',
        );
    }
    public function enqueueAssets()
    {
        if ((isset($_GET['page']) && $_GET['page'] == 'wpchartmaker.php')) {
            wp_enqueue_script(
                'chart_maker_settings_boot',
                CHARTMAKER_URL . 'dist/js/boot.js',
                array('jquery'),
                CHARTMAKER_VERSION,
                true
            );
            wp_enqueue_script(
                'chart_maker_settings_main',
                CHARTMAKER_URL . 'dist/js/chart-maker.js',
                array('jquery'),
                CHARTMAKER_VERSION,
                true
            );

            do_action('wpchartmaker/booting_admin_app');

            wp_enqueue_style('chartmaker_admin_app', CHARTMAKER_URL . 'dist/admin/css/chartmaker-admin.css', array(), CHARTMAKER_VERSION);

            $chartmakerAdminVars = apply_filters('chartmaker/admin_app_vars', array(
                'i18n' => array(
                    'All Events' => __('All Events', 'chartmaker')
                ),
                'assets_url' => CHARTMAKER_URL . 'assets/',
                'ajaxurl' => admin_url('admin-ajax.php'),
                'printStyles' => apply_filters('chartmaker/print_styles', [
                    CHARTMAKER_URL . 'dist/admin/css/chartmaker-admin.css'
                ])
            ));
            wp_localize_script('chart_maker_settings_boot', 'chartMakerAdmin', $chartmakerAdminVars);

        }
    }
    public function render() {
        do_action('wpchartmaker/render_admin_app');

    }

}
