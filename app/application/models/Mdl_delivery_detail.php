<?php
class Mdl_delivery_detail extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_delivery_detail';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'delivery_rowid'=>'',
			'seq'=>'',
			'title'=>'',
			'qty' => '',
			'price' => '',
			'total_amount' => '',
			'order_type_id' => '',
			'order_rowid' => '',
			'order_detail_rowid' => '',
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL
		);
	}
}