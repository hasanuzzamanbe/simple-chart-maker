<?php

/*
Plugin Name: Chart Maker- Show chart easily
Plugin URI: https://github.com/hasanuzzamanbe/Chart-Maker
Description: Create your data chart within a minute.
Version: 1.0.0
Author: Xaaman
Author URI: https://github.com/hasanuzzamanbe
License: A "Slug" license name e.g. GPL2
Text Domain: chartmaker
*/


/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright 2019 WPManageNinja LLC. All rights reserved.
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('CHARTMAKER_VERSION')) {
    define('CHARTMAKER_VERSION_LITE', true);
    define('CHARTMAKER_VERSION', '1.1.0');
    define('CHARTMAKER_MAIN_FILE', __FILE__);
    define('CHARTMAKER_URL', plugin_dir_url(__FILE__));
    define('CHARTMAKER_DIR', plugin_dir_path(__FILE__));
    define('CHARTMAKER_UPLOAD_DIR', '/chartmaker');

    class WpChartMaker
    {
        public function boot()
        {
            if (is_admin()) {
                $this->adminHooks();
            }
            $this->registerShortcodes();
        }

        public function registerShortcodes()
        {
             require CHARTMAKER_DIR.'includes/renderautoloader.php';

            // Register the shortcode
            add_shortcode('chartmaker', function ($args) {
                $args = shortcode_atts(array(
                    'id'               => ''
                ), $args);

                if (!$args['id']) {
                    return;
                }

                $builder = new \WpChartMaker\Classes\Builder\Render();
                return $builder->render($args['id']);
            });
        }

        public function adminHooks()
        {
            require CHARTMAKER_DIR.'includes/autoload.php';

            //Register Admin menu
            $menu = new \WpChartMaker\Classes\Menu();
            $menu->register();

            // Top Level Ajax Handlers
            $ajaxHandler = new \WpChartMaker\Classes\AdminAjaxHandler();
            $ajaxHandler->registerEndpoints();

            add_action('wpchartmaker/render_admin_app', function () {
                $adminApp = new \WpChartMaker\Classes\AdminApp();
                $adminApp->bootView();
            });

        }

        public function textDomain()
        {
            load_plugin_textdomain('wpchartmaker', false, basename(dirname(__FILE__)) . '/languages');
        }

    }

    add_action('plugins_loaded', function () {
        (new WpChartMaker())->boot();
    });

    register_activation_hook(__FILE__, function ($newWorkWide) {
        require_once(CHARTMAKER_DIR . 'includes/Classes/Activator.php');
        $activator = new \WpChartMaker\Classes\Activator();
        $activator->migrateDatabases($newWorkWide);
    });

} else {
    add_action('admin_init', function () {
        deactivate_plugins(plugin_basename(__FILE__));
    });
}
