<?php
	
class WP_Sentry_Shortcode_Functions {
	
	protected static $valid_shortcode_functions = array(
		"wp_current_user_id",
		"wp_current_user_name",
		"wp_current_user_role",
		"wp_current_user_email",
		"wp_bloginfo_name",
		"wp_bloginfo_url",
		"wp_bloginfo_admin_email",
		"wp_bloginfo_charset",
		"wp_bloginfo_html_type",
		"wp_bloginfo_text_direction",
		"wp_bloginfo_version",
		"wp_bloginfo_language",
		"wp_bloginfo_template_url",
		"phpversion"		
	);
	static public function valid_shortcode_functions(){
		return self::$valid_shortcode_functions;
	}
	
	static function wp_current_user_id() { return get_current_user_id(); }
	static function wp_current_user_name() { return wp_get_current_user()->user_login; }
	static function wp_current_user_role() { 
		global $current_user;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	} 
	static function wp_current_user_email() { return wp_get_current_user()->user_email; }
	static function wp_bloginfo_name() { return get_bloginfo('name'); }
	static function wp_bloginfo_url() { return get_bloginfo('url'); }
	static function wp_bloginfo_admin_email() { return get_bloginfo('admin_email'); }
	static function wp_bloginfo_charset() { return get_bloginfo('charset'); }
	static function wp_bloginfo_html_type() { return get_bloginfo('html_type'); }
	static function wp_bloginfo_text_direction() { return get_bloginfo('text_direction'); }
	static function wp_bloginfo_version() { return get_bloginfo('version'); }
	static function wp_bloginfo_language() { return get_bloginfo('language'); }
	static function wp_bloginfo_template_url() { return get_bloginfo('template_url'); }
	static function phpversion() { return phpversion(); }

	
}