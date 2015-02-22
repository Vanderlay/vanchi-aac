<?php

final class Example extends Master_Model
{
	public static $class_name = __CLASS__;
	public $id;
	public $created;
	public $updated;
	
	function __construct()
	{
		parent::__construct();
	}
}

?>