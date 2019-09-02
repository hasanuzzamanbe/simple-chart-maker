window.ChartMakerBus = new window.ChartMaker.Vue();

window.ChartMaker.Vue.mixin({
    methods: {
        applyFilters: window.ChartMaker.applyFilters,
        addFilter: window.ChartMaker.addFilter,
        addAction: window.ChartMaker.addFilter,
        doAction: window.ChartMaker.doAction,
        $get: window.ChartMaker.$get,
        $adminGet: window.ChartMaker.$adminGet,
        $adminPost: window.ChartMaker.$adminPost,
        $post: window.ChartMaker.$post
    }
});

import {routes} from './routes'

const router = new window.ChartMaker.Router({
    routes: window.ChartMaker.applyFilters('chart_maker_global_vue_routes', routes),
    linkActiveClass: 'active'
});

import App from './AdminApp'

new window.ChartMaker.Vue({
    el: '#wpchartmakerapp',
    render: h => h(App),
    router: router,
    mounted() {
        window.ChartMakerBus.$on("site_title", title => {
            jQuery(document).attr("title", title + " :: ChartMaker");
        });
    }
});

