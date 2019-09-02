<?php
namespace WpChartMaker\Classes\Tools;

use WpChartMaker\Classes\AccessControl;
use WpChartMaker\Classes\ArrayHelper;
use WpChartMaker\Classes\Models\Charts;

class GlobalTools
{
    public function getChart($chartId)
    {
        $chart = get_post($chartId, 'ARRAY_A');
        if (!$chart || $chart['post_type'] != 'chart_maker') {
            return false;
        }

        $metas = chartMakerDB()->table('postmeta')
            ->select(['meta_key', 'meta_value'])
            ->where('post_id', $chartId)
            ->get();

        $chartattedMeta = array();
        foreach ($metas as $meta) {
            $chartattedMeta[$meta->meta_key] = maybe_unserialize($meta->meta_value);
        }

        $chart['chart_meta'] = $chartattedMeta;

        return $chart;
    }

    public function createChartFromData($chart)
    {
        // Create the chart post type
        $postId = wp_insert_post(array(
            'post_title'   => ArrayHelper::get($chart, 'post_title', 'imported chart '.gmdate('Y-m-d')),
            'post_content' => ArrayHelper::get($chart, 'post_content', ''),
            'post_type'    => 'chart_maker',
            'post_status'  => 'publish',
            'post_excerpt' => ArrayHelper::get($chart, 'post_excerpt', ''),
            'post_author'  => get_current_user_id()
        ));

        if (is_wp_error($postId) && wp_doing_ajax()) {
            wp_send_json_error(array(
                'message' => 'Something is wrong when processing the file. Please try again'
            ), 423);
        } else if(is_wp_error($postId)) {
            return false;
        }

        $metas = ArrayHelper::get($chart, 'chart_meta', array());
        if (is_array($metas)) {
            foreach ($metas as $metaKey => $metaValue) {
                update_post_meta($postId, $metaKey, $metaValue);
            }
        }

        $newChart = Charts::getChart($postId);

        return $newChart;

    }
}
