<?php 
namespace ChartMaker\Classes;

class HelperClass {
	
	public static function makeView($file, $data = array()) {
		$file = sanitize_file_name($file);
		$file = str_replace('.', DIRECTORY_SEPARATOR, $file);
		extract($data);
		$fiCHARTMAKER_DIR . 'includes/templates/' . $file . '.php';
		if(!file_exists($filePath))  {
			return '';
		}


        wp_enqueue_script(
            'event_ninja_vue_main_loaded',
            CHARTMAKER_URL . 'dist/js/chart-maker.js',
            array( 'jquery' ),
            CHARTMAKER_VERSION,
            true
        );

        // wp_enqueue_style('eventninja_admin_app', CHARTMAKER_URL.'assets/admin/css/eventninja-admin.css', array(), EVENTNINJA_VERSION);
		ob_start();
		include $filePath;
		return ob_get_clean();
	}
	
	public static function renderView($file, $data) {
		echo self::makeView($file, $data);
    }
}
