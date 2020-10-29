<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_address extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_customer_address';
	}
}