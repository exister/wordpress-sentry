<?php

require_once dirname(__FILE__) . '/raven/lib/Raven/Autoloader.php';
Raven_Autoloader::register();

class WP_Raven_Client extends Raven_Client {

	protected $settings;
	protected $sentry_context;
	
	protected $_sentry_context;
	protected $_user_context;
	protected $_tags_context;
	protected $_extra_context;
	
	/**
	 * @var array
	 */
	protected $errorLevelMap;

	public function __construct() {

		$this->errorLevelMap = array(
			'E_NONE' => 0,
			'E_ERROR' => 1,
			'E_WARNING' => 2,
			'E_PARSE' => 4,
			'E_NOTICE' => 8,
			'E_USER_ERROR' => 256,
			'E_USER_WARNING' => 512,
			'E_USER_NOTICE' => 1024,
			'E_RECOVERABLE_ERROR' => 4096,
			'E_ALL' => 8191);

		$this->settings = get_option('sentry-settings');
		$this->sentry_context = get_option('sentry-context');

		if (!isset($this->settings['dsn']) || $this->settings['dsn'] == '')
			return;
		if (!isset($this->sentry_context) ) $this->sentry_context = array();

		if (isset($this->sentry_context)){
			$this->_sentry_context = $this->filterTags($this->sentry_context);
			$this->_user_context = $this->_sentry_context['user_context'];
			$this->_tags_context = $this->_sentry_context['tags_context'];
			$this->_extra_context = $this->_sentry_context['extra_context'];			
			
		}
		
		parent::__construct($this->settings['dsn']);

		$this->setErrorReportingLevel($this->settings['reporting_level']);

		$this->setHandlers();
		$this->setContexts();
	}

	private function setHandlers() {
		$error_handler = new Raven_ErrorHandler($this);
		$error_handler->registerErrorHandler();
		$error_handler->registerExceptionHandler();
		$error_handler->registerShutdownFunction();
	}

	private function setErrorReportingLevel($level = 'E_ERROR')
	{
		error_reporting($this->errorLevelMap[$level]);
	}
	
	private function setContexts(){		
		$this->user_context($this->_user_context);
		$this->tags_context($this->_tags_context);
		$this->extra_context($this->_extra_context);
	}
	
	private function filterTags($content = ""){
		
		if (is_array($content) && !empty($content)) {
			foreach($content as $context => $tags_string) {
				$array = explode("\n", $tags_string);
				$new_array = array();
				if(!empty($array)){
					foreach($array as $string){
						$exp = explode("=", $string);
						if(count($exp) == 2){
							$value = ($this->filterShortcodes($exp[1]));
							$new_array[trim($exp[0])] = $value;
						}
					}
				}
				$context_array[$context] = $new_array;
			}
			
			
			return $context_array;
		}	
			
		return $content;
	}
	
	private function filterShortcodes($string = ""){
		$string = trim($string);
		if(strstr($string, "[")){
			// remove [ & ]
			$string = str_replace( ["[","]"], "", $string); 
			if(in_array($string, WP_Sentry_Shortcode_Functions::valid_shortcode_functions())){
				return call_user_func(array( 'WP_Sentry_Shortcode_Functions', $string));
			}
		}
	}

}
