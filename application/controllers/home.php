<?php

class Home extends Master_Controller
{
	function __construct()
	{
		$themes = $this->config->item('themes');
		$this->template_section = $themes['justified nav'];
		$this->include_standard = true;
		parent::__construct();
	}
	
	public function index()
	{
		$this->v('home');
	}
}