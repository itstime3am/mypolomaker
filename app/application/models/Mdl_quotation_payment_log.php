<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_quotation_payment_log extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) $this->db = $this->load->database('default', TRUE);

		$this->_TABLE_NAME = 'pm_t_quotation_payment_log';
		$this->_AUTO_FIELDS = array('rowid' => '');
		$this->_FIELDS = array(
			'quotation_rowid' => NULL
			,'payment_datetime' => NULL
			,'payment_route_rowid' => NULL
			,'amount' => NULL
			,'description' => NULL
			,'remark' => NULL
			,'is_approve' => NULL
			,'approve_by' => NULL
			,'approve_date' => NULL
			,'create_by' => NULL
			,'create_date' => NULL
			,'update_by' => NULL
			,'update_date' => NULL
			,'is_cancel' => NULL
			,'cancel_by' => NULL
			,'cancel_date' => NULL
			,'payment_type' => NULL //default 0, 0 = deposit, others = else
		);
		$this->_DATETIME_FIELDS = array('payment_datetime', 'approve_date', 'cancel_date');
		//$this->_JSON_FIELDS = array('json_details', 'json_others', 'json_images');
	}
	
	function search($arrObj = array()) {
		$_sql = <<<QUERY
SELECT t.*
FROM pm_t_quotation_payment_log t 
	INNER JOIN pm_t_quotation m ON t.quotation_rowid = m.rowid 
WHERE True 

QUERY;
		$_sql .= $this->_getSearchConditionSQL($arrObj);
		$_sql .= $this->_getCheckAccessRight("m.create_by", "quotation");
		$_sql .= "ORDER BY t.rowid";

		return $this->arr_execute($_sql);
	}
	
	function cancel($RowID) {
		$this->db->set('is_cancel', 1);
		$this->db->set('cancel_by', $this->_AC->_user_id);
		$this->db->set('cancel_date', 'CURRENT_TIMESTAMP', FALSE);
		$this->db->where('rowid', $RowID);
		$this->db->update($this->_TABLE_NAME);
		$this->error_message = $this->db->error()['message'];
		$this->error_number = $this->db->error()['code'];
		return $this->db->affected_rows();
	}

	function updateImageReceipt($filename, $rowid) {
		$this->db->set('image_receipt', $filename);
		$this->db->where('rowid', $rowid);
		$this->db->update($this->_TABLE_NAME);
		$this->error_message = $this->db->error()['message'];
		$this->error_number = $this->db->error()['code'];
		return $this->db->affected_rows();
	}

}