<?php


namespace WpChartMaker\Classes;
use WpChartMaker\Classes\Models\Charts;
use WpChartMaker\Classes\Tools\GlobalTools;

if (!defined('ABSPATH')) {
    exit;
}

class AdminAjaxHandler
{
    public function registerEndpoints()
    {
        add_action('wp_ajax_chart_maker_admin_ajax', array($this, 'handeEndPoint'));
    }

    public function handeEndPoint()
    {
        $route = sanitize_text_field($_REQUEST['route']);

        $validRoutes = array(
            'get_charts'                  => 'getCharts',
            'create_chart'                => 'createChart',
            'update_chart'                => 'updateChart',
            'get_chart_option'            => 'getChartOption',
            'delete_chart'                => 'deleteChart',
            'duplicate_chart'             => 'duplicateChart',
            'create_demo_chart'           => 'createDemoChart'
        );

        if (isset($validRoutes[$route])) {
            AccessControl::checkAndPresponseError($route, 'charts');
            do_action('chartmaker/doing_ajax_forms_' . $route);
            return $this->{$validRoutes[$route]}();
        }
        do_action('chartmaker/admin_ajax_handler_catch', $route);
    }

    protected function createChart()
    {

        $postTitle = sanitize_text_field($_REQUEST['post_title']);

        if (!$postTitle) {
            $postTitle = 'Blank chart(Edit your chart title)';
        }
        $template = sanitize_text_field($_REQUEST['chart_type']);

        $data = array(
            'post_title'  => $postTitle,
            'post_status' => 'publish',
        );


        do_action('chartmaker/before_create_form', $data, $template);

        $chartData = Charts::create($data);
        $chartId = $chartData[id];
        wp_update_post([
            'ID' => $chartId,
            'post_title' => $data['post_title'],
            'post_excerpt' => $template
        ]);

        if (is_wp_error($chartId)) {
            wp_send_json_error(array(
                'message' => __('Something is wrong when creating the chart. Please try again', 'chartmaker')
            ), 423);
            return;
        }

        do_action('chartmaker/after_create_form', $chartId, $data, $template);

        wp_send_json_success(array(
            'message' => __('Form successfully created.', 'chartmaker'),
            'chart' => $chartData,
            'chart_type' => $template
        ), 200);
    }

    protected function createDemoChart()
    {
        $chart_id =  $_REQUEST[chart_id];
        Charts::createDemoChart($chart_id);
        wp_send_json_success(array(
            'message' => __('Chart successfully duplicated', 'chartmaker'),
        ), 200);
    }

    protected function getCharts()
    {
        $perPage = absint($_REQUEST['per_page']);
        $pageNumber = absint($_REQUEST['page_number']);
        $searchString = sanitize_text_field($_REQUEST['search_string']);
        $args = array(
            'posts_per_page' => $perPage,
            'offset'         => $perPage * ($pageNumber - 1)
        );
        $args = apply_filters('chartmaker/get_all_charts_args', $args);

        if ($searchString) {
            $args['s'] = $searchString;
        }

        $charts = Charts::getCharts($args, $with = array('entries_count'));

        wp_send_json_success($charts);
    }

    protected function deleteChart()
    {
        $chartId = intval($_REQUEST['chart_id']);
        do_action('chartmaker/before_chart_delete', $chartId);
        Charts::deleteChart($chartId);
        do_action('chartmaker/after_chart_delete', $chartId);
        wp_send_json_success(array(
            'message' => __('Selected chart successfully deleted', 'chartmaker')
        ), 200);
    }

    protected function duplicateChart()
    {

        $chartId = absint($_POST['chart_id']);
        $globalTools = new GlobalTools();
        $oldChart = $globalTools->getChart($chartId);
        $oldChart['post_title'] = '(Duplicate) '.$oldChart['post_title'];
        $oldChart = apply_filters('chartmaker/chart_duplicate', $oldChart);

        if(!$oldChart) {
            wp_send_json_error(array(
                'message' => __('No chart found when duplicating the chart', 'chartmaker')
            ), 423);
        }
        $newChart = $globalTools->createChartFromData($oldChart);
        wp_send_json_success(array(
            'message' => __('Chart successfully duplicated', 'chartmaker'),
            'chart' => $newChart
        ), 200);
    }

    protected function updateChart()
    {

        $chart_id =  $_REQUEST[chart_id];
        $chart_values =maybe_serialize( $_REQUEST[chart_values] ); ;
        Charts::updateChart($chart_id, $chart_values);
        wp_send_json_success(array(
            'message' => __('Chart successfully Updated', 'chartmaker'),
        ), 200);
    }

    protected function getChartOption()
    {
        $chart_id = $_REQUEST[chart_id];
        $chart_val  = Charts::getChartOptions($chart_id);
        $post_title = $chart_val->post_title;

        $field = maybe_unserialize( $chart_val->post_content );
        wp_send_json_success(array(
            'chart_id' => $chart_id,
            'chart_name' => $post_title,
            'chart_values' => $field
        ), 200);
    }

    //end bracket
}
