<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_order_payment_log extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) $this->db = $this->load->database('default', TRUE);

		$this->_TABLE_NAME = 't_order_payment_log';
		$this->_AUTO_FIELDS = array('rowid' => '');
		$this->_FIELDS = array(
			'type_id' => NULL
			,'order_rowid' => NULL
			,'order_detail_rowid' => NULL
			,'payment_datetime' => NULL
			,'payment_route_rowid' => NULL
			,'amount' => NULL
			,'description' => NULL
			,'remark' => NULL
			,'is_approve' => NULL
			,'approve_by' => 0
			,'approve_date' => NULL
			,'create_by' => 0
			,'create_date' => NULL
			,'update_by' => 0
			,'update_date' => NULL
			,'is_cancel' => NULL
			,'cancel_by' => 0
			,'cancel_date' => NULL
		);
		$this->_DATETIME_FIELDS = array('payment_datetime', 'approve_date', 'cancel_date');
	}
	
	function search($arrObj = array()) {
		$_sql = <<<QUERY
SELECT t.*
FROM t_order_payment_log t 
WHERE True 

QUERY;
		$_sql .= $this->_getSearchConditionSQL($arrObj);
		$_sql .= $this->_getCheckAccessRight("m.create_by", "quotation");
		$_sql .= "ORDER BY t.rowid";

		return $this->arr_execute($_sql);
	}
}