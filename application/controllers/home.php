<?php

class Home extends Master_Controller
{
	function __construct()
	{
		$this->template_section = 'Justified Nav';
		$this->include_standard = true;
		parent::__construct();
	}
	
	public function index()
	{
		$this->v('home');
	}
}