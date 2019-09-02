<?php
namespace WpChartMaker\Classes\Builder;

use WpChartMaker\Classes\ArrayHelper;
use WpChartMaker\Classes\Models\Charts;

if (!defined('ABSPATH')) {
    exit;
}

class Render
{
    public function render($chartId)
    {
        $html = 'This is from chart maker with ID:';
        $html.= '<div id="renderchartmakerapp"></div>';
        $chart = Charts::getChart($chartId);
        $this->addAssets($chart);

        return apply_filters('eventninja/rendered_form_html', $html);
    }

    private function addAssets($form)
    {
        do_action('eventninja/eventninja_adding_assets', $form);

        wp_enqueue_script('eventninja_public_boot', CHARTMAKER_URL . 'dist/js/boot.js', array('jquery'), CHARTMAKER_VERSION, true);
        wp_enqueue_script('eventninja_public_js', CHARTMAKER_URL . 'dist/js/chart-maker-frontend.js', array('eventninja_public_boot'), CHARTMAKER_VERSION, true);


        wp_enqueue_style('eventninja_public_css', CHARTMAKER_URL . 'dist/admin/css/chartmaker-admin.css', array(), CHARTMAKER_VERSION);

    }

}
