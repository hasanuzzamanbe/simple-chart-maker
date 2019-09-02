<?php
namespace WpChartMaker\Classes\Models;;

if (!defined('ABSPATH')) {
    exit;
}

class Charts
{
    public static function create($data)
    {

        $chartData = array(
            'post_title'   => $data[post_title],
            'post_status'  => 'publish',
            'post_type'    => 'chart_maker'
        );

        $id = wp_insert_post($chartData);
        $chartData[id] = $id;
        return $chartData;
    }

    public static function createDemoChart($chartId)
    {
        $data['chart_id'] = $chartId;
        $data['chart_values'] = 'not_updated';
        return chartMakerDB()->table('chart_maker')->insert($data);
    }

    public static function getCharts($args = array(), $with = array())
    {
        $whereArgs = array(
            'post_type'   => 'chart_maker'
        );

        $whereArgs = apply_filters('chartmaker/all_charts_where_args', $whereArgs);

        $chartsQuery = chartMakerDB()->table('posts')
            ->orderBy('ID', 'DESC')
            ->offset($args['offset'])
            ->whereNotIn('post_status', ['auto-draft'])
            ->limit($args['posts_per_page']);

        foreach ($whereArgs as $key => $where) {
            $chartsQuery->where($key, $where);
        }

        if (!empty($args['s'])) {
            $chartsQuery->where(function ($q) use ($args) {
                $q->where('post_title', 'LIKE', "%{$args['s']}%");
                $q->orWhere('ID', 'LIKE', "%{$args['s']}%");
                $q->orWhere('post_content', 'LIKE', "%{$args['s']}%");
            });
        }

        $chartsQuery->whereNotIn('post_status', ['auto-draft']);

        $total = $chartsQuery->count();

        $charts = $chartsQuery->get();

        $charts = apply_filters('chartmaker/get_all_charts', $charts);

        $lastPage = ceil($total / $args['posts_per_page']);

        return array(
            'charts'     => $charts,
            'total'     => $total,
            'last_page' => $lastPage
        );
    }

    public static function deleteChart($chartID)
    {
        wp_delete_post($chartID, true);
        return true;
    }

    public static function getChart($chartId)
    {
        $chart = get_post($chartId, 'OBJECT');
        if (!$chart || $chart->post_type != 'chart_maker') {
            return false;
        }
        return $chart;
    }

    public static function updateChart($chart_id, $chart_values)
    {
        return  wp_update_post( array( 'ID' => $chart_id, 'post_content' => $chart_values ));

    }

    public static function getChartOptions($chart_id)
    {
        $chart = get_post($chart_id);
//        $content = $chart->post_content;
        return $chart;
    }
}
