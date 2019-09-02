<?php

namespace WpChartMaker\Classes;


class AccessControl
{
    public static function hasTopLevelMenuPermission()
    {
        $menuPermissions = array(
            'manage_options',
            'wpf_full_access',
            'wpf_can_view_menus'
        );
        foreach ($menuPermissions as $menuPermission) {
            if (current_user_can($menuPermission)) {
                return $menuPermission;
            }
        }
        return false;
    }
    public static function hasGrandAccess()
    {
        $grandPermissions = array(
            'manage_options',
            'chart_maker_full_access'
        );

        foreach ($grandPermissions as $grandPermission) {
            if (current_user_can($grandPermission)) {
                return $grandPermission;
            }
        }
        return false;
    }
    public static function checkAndPresponseError($endpoint = false, $group = false, $message = '')
    {
        if(self::hasEndPointPermission($endpoint, $group)) {
            return true;
        }
        wp_send_json_error(array(
            'message' => ($message) ? $message : __('Sorry, You do not have permission to do this action: ', 'chartmaker').$endpoint,
            'action' => $endpoint
        ), 423);
    }

    public static function hasEndPointPermission($endpoint = false, $group = false)
    {
        if($grandAccess = self::hasGrandAccess()) {
            return apply_filters('chartmaker/has_endpoint_access', $grandAccess, $endpoint, $group);
        }
        return apply_filters('chartmaker/has_endpoint_access', false, $endpoint, $group);
    }
}

