<?php
/**
 * Created by PhpStorm.
 * User: ah1
 * Date: 2019-06-16
 * Time: 11:11
 */

namespace WpChartMaker\Classes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin App Renderer and Handler
 * @since 1.0.0
 */
class AdminApp
{
    public function bootView()
    {
        echo "<div id='wpchartmakerapp'></div>";
    }
}
