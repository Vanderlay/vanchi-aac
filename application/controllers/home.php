<?php

class Home extends Master_Controller
{
	function __construct()
	{
		/** We wish to not load defaults yet because we need to get the template name first from the config **/
		$this->loadDefaults = FALSE;
		parent::__construct();

		$themes = $this->config->item('themes');
		$this->template_section = $themes[7];
		$this->loadDefaults();
	}
	
	public function index()
	{
		$this->v('home');
	}
}