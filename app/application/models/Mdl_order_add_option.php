<?php
class Mdl_order_add_option extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 't_order_add_option';
		$this->_AUTO_FIELDS = array('rowid' => NULL);
		$this->_FIELDS = array(
			'order_rowid' => NULL
			,'order_type_id' => NULL
			,'promotion' => NULL
			,'hem_rowid' => NULL
			,'hem_color_rowid' => NULL
			,'male_fix_length' => NULL
			,'female_fix_length' => NULL
			,'is_no_neck_tag' => NULL
			,'is_customer_size_tag' => NULL
			,'is_no_plmk_size_tag' => NULL
			,'is_no_back_clasper' => NULL
			,'pakaging_type_rowid' => NULL
			,'pakaging_method_rowid' => NULL
			,'create_by' => NULL
			,'create_date' => NULL
			,'update_by' => NULL
			,'update_date' => NULL
		);
	}
}