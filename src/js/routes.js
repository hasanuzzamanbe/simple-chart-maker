import AllCharts from './Components/Charts/Charts';
import Settings from './Components/Settings';
import GlobalView from './Components/Global/index';

import Supports from './Components/Supports';
import PieAndDonutChart from './Components/ChartTypes/PieAndDonutChart'




export const routes = [
    {
        path: '/',
        component: GlobalView,
        props: true,
        children: [
            {
                path: '/',
                name: 'charts',
                component: AllCharts,
                props: true,
            },
            {
                path: '/settings',
                name: 'settings',
                component: Settings
            },
            {
                path: '/supports',
                name: 'supports',
                component: Supports
            },
            {
                path: 'edit/:chart_id/:chart_type',
                name: 'PieAndDonutChart',
                component: PieAndDonutChart
            },
        ]
    }
];
