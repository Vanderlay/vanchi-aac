<?php

class User extends Master_Controller
{
	function __construct()
	{
		/** API calls do not load defaults **/
		$this->loadDefaults = FALSE;
		parent::__construct();
	}
	
	public function login()
	{
		echo "hi";
	}
}