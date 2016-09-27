<?php

/*
  Plugin Name: WordPress Sentry Client
  Plugin URI: http://www.hzdg.com
  Description: Sends PHP errors to Django Sentry
  Author: Ryan Bagwell
  Version: 1
  Author URI: http://www.ryanbagwell.com
 */

require_once( dirname(__FILE__) . '/class.wp-raven-client.php' );
require_once( dirname(__FILE__) . '/class.wp-shortcode-functions.php');

class WPSentry extends WP_Raven_Client {

    private static $instance = null;

	public function __construct() {
		add_action('admin_menu', array($this, 'addOptionsPage'));

		if( defined( 'WP_DEBUG' ) && ( WP_DEBUG ) && is_admin() ){
			add_action( 'admin_notices', array( $this, 'admin_notice_wp_debug') );
		}

		if (is_admin() && $_POST)
			$this->saveOptions();

		parent::__construct();

        static::$instance = $this;
	}

	public function addOptionsPage() {
		add_options_page('Sentry Error Reporting Settings', 'Sentry', 'edit_pages', 'sentrysettings', array($this, 'printOptionsHTML'));
	}

	public function printOptionsHTML() {
		$error_levels = $this->errorLevelMap;
		$settings = $this->settings;
		$context = $this->sentry_context;
		$shortcodes = WP_Sentry_Shortcode_Functions::valid_shortcode_functions();
		require_once( dirname(__FILE__) . '/optionspage.html.php' );
	}

	public function saveOptions() {

		if (!isset($_POST['sentry_dsn']) || !isset($_POST['sentry_reporting_level']))
			return;

		update_option('sentry-settings', array(
			'dsn' => $_POST['sentry_dsn'],
			'reporting_level' => $_POST['sentry_reporting_level'],
			'name' => $_POST['sentry_name'],
			'environment' => $_POST['sentry_environment']
		));
		
		update_option('sentry-context', array(
			'user_context' => $_POST['sentry_user_context'],
			'tags_context' => $_POST['sentry_tags_context'],
			'extra_context' => $_POST['sentry_extra_context']
		));
	}
	
	public function getSettings() {
		return $this->settings;
	}

	public function getContext() {
		return $this->sentry_context;
	}

	public static function load() {
		try {
			$wps = new WPSentry();
		} catch (Exception $e) {
			
		}
	}

    public static function getInstance() {
        return self::$instance;
    }
    
    public function admin_notice_wp_debug(){
	   	printf( '<div class="%1$s"><p>%2$s</p></div>', 
	   		"notice notice-warning is-dismissible", 
	   		__( '<code>WP_DEBUG</code> is currently enabled It is recommended to disable this for a production environment.', 'wordpress-sentry' ) 
	   	);    
	}
}

add_action('plugins_loaded', array('WPSentry', 'load'), 1);