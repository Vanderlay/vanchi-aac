<?php

/**
 * TODO: Docs
 */
class Master_Controller extends CI_Controller
{
	/**
	 * Used to see if an user is logged in or not
	 * @var  bool
	 */
	public $logged_in = FALSE;
	
	/**
	 * Used to check user access
	 * @var  string
	 */
	public $userAccess = '';
	
	/**
	 * The user ID
	 * @var  int
	 */
	public $user_id;
	
	/**
	 * Array of data to be passed to the view
	 * @var array
	 */
	protected $view_data;
	
	/**
	 * Views to load before main view
	 * @var array
	 */
	protected $header_views = array();
	
	/**
	 * Views to load after the main view
	 * @var array
	 */
	protected $footer_views = array();

	/**
	 * Views section variable
	 * Used for different types of templates
	 * @var string
	 */
	protected $template_section = '';

	/**
	 * If we wish to only view a single template
	 * and exclude all the function views
	 * @var boolean
	 */
	protected $include_standard = TRUE;
	
	
	/**
	 * Class constructor
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth');
		$this->load->helper('url');
		/**
		 * We require login access before displaying anything on the page
		 */
		/*if(!$this->tank_auth->is_logged_in()) {
			if(uri_string() != 'users/login') {
				redirect('/users/login');
			}
		}*/
		$this->loadDefaults();
	}

	public function loadDefaults()
	{
		/**
		 * These are required so the template system works as intended
		 */
		if(!$this->template_section) {
			show_error('A template section must be defined in ' . __CLASS__, 500);
		}
		$this->template_section = strToLower($this->template_section);
		if(preg_match('#/$#', $this->template_section)) {
			$this->template_section = substr($this->template_section, 0, strlen($this->template_section)-1);
		}
		$this->view_data['template_section'] = $this->template_section;
		$this->autoJS();
		$this->autoCSS();

		/**
		 * Custom default variables
		 */
		if(!isset($this->view_data['user'])) {
			$this->view_data['user'] = $this->tank_auth->get_user();
		}
	}

	public function autoJS($ignoredFiles = array())
	{
		array_push($ignoredFiles, '.', '..', '.svn');
		$jquery = FALSE;
		foreach(scandir(FCPATH . 'js/' . ucFirst($this->template_section)) as $filename) {
			if(!in_array($filename, $ignoredFiles)) {
				if($filename == 'jquery.js') {
					$jquery = TRUE;
				} else {
					$this->view_data['autoJS'][] = '/js/' . $this->template_section . '/' . $filename;
				}
			}
		}
		if($jquery) {
			array_unshift($this->view_data['autoJS'], '/js/' . $this->template_section . '/' . 'jquery.js');
		}
		$this->view_data['autoJS'] = array_unique($this->view_data['autoJS']);
	}


	public function autoCSS($ignoredFiles = array())
	{
		array_push($ignoredFiles, '.', '..', '.svn');
		foreach(scandir(FCPATH . 'css/' . ucFirst($this->template_section)) as $filename) {
			if(!in_array($filename, $ignoredFiles)) {
				$this->view_data['autoCSS'][] = '/css/' . $this->template_section . '/' . $filename;
			}
		}
		$this->view_data['autoCSS'] = array_unique($this->view_data['autoCSS']);
	}
	
	/**
	 * Wrapper around $this->load->view()
	 * 
	 * @param	string	$view
	 * @return 	void
	 */
	public function v($view, $loadJS = FALSE, $loadCSS = FALSE)
	{
		if($loadCSS) {
			$this->autoCSS();
		}
		if($loadJS) {
			$this->autoJS();
		}
		if(!$this->header_views) {
			$this->header_views[] = 'Standard/header';
			$this->header_views[] = ucFirst($this->template_section) . '/shared/header';
		}

		if(!$this->footer_views) {
			$this->footer_views[] = ucFirst($this->template_section) . '/shared/footer';
			$this->footer_views[] = 'Standard/footer';
		}
		foreach ($this->header_views as $append)
		{
			$this->load->view($append, $this->view_data);
		}

		$this->load->view(ucFirst($this->template_section) . '/' . $view, $this->view_data);

		foreach ($this->footer_views as $append)
		{
			$this->load->view($append, $this->view_data);
		}
	}

	public function setDefaultTemplateSection()
	{
		$this->template_section = 'functions';
	}
}
