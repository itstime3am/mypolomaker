<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_detail extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_delivery_detail';
	}
}