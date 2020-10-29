<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_order_detail_cap extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 't_order_detail_cap';

		$this->_FIELDS = array(
			'order_rowid' => NULL
			//,'cap_type_rowid' => NULL
			//,'cap_type_detail' => NULL
			,'standard_pattern_rowid' => NULL
			,'standard_pattern_detail' => NULL
			,'fabric_rowid' => NULL
			,'front_color_rowid' => NULL
			,'back_color_rowid' => NULL
			,'brim_color_rowid' => NULL
			,'button_color_rowid' => NULL			
			,'is_sandwich_rim' => NULL
			,'swr_color_rowid' => NULL
			,'air_flow_holes_number' => NULL
			,'afh_color_rowid' => NULL
			,'cap_belt_type_rowid' => NULL
			,'cap_belt_detail' => NULL
			,'remark1' => NULL
			,'remark2' => NULL
		);
	}
}
