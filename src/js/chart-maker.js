import Vue from './elements';
import Router from 'vue-router';
Vue.use(Router);

import { applyFilters, addFilter, addAction, doAction } from '@wordpress/hooks';

export default class ChartMaker {
    constructor() {
        this.applyFilters = applyFilters;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.doAction = doAction;
        this.Vue = Vue;
        this.Router = Router;
    }

    $get(options) {
        return window.jQuery.get(window.chartMakerAdmin.ajaxurl, options);
    }

    $adminGet(options) {
        options.action = 'chart_maker_admin_ajax';
        return window.jQuery.get(window.chartMakerAdmin.ajaxurl, options);
    }

    $post(options) {
        return window.jQuery.post(window.chartMakerAdmin.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'chart_maker_admin_ajax';
        return window.jQuery.post(window.chartMakerAdmin.ajaxurl, options);
    }

    $getJSON(options) {
        return window.jQuery.getJSON(window.chartMakerAdmin.ajaxurl, options);
    }

    $post(options) {
        return window.jQuery.post(window.chartMakerAdmin.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'chart_maker_admin_ajax';
        return window.jQuery.post(window.chartMakerAdmin.ajaxurl, options);
    }
    $getJSON(options) {
        return window.jQuery.getJSON(window.chartMakerAdmin.ajaxurl, options);
    }
}
