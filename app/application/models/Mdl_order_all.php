<?php

class Mdl_order_all extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
	}
	function search($arrObj = array()) {
		$_sql = <<<EOT
SELECT (v.order_type_id * 1000) + v.order_rowid AS rowid
, CONCAT_WS(' ', v.category, v.type) AS disp_order_type
, v.type_id, v.order_rowid, v.job_number, v.ref_number, v.customer_rowid, v.customer_name AS customer
, v.order_date, v.deliver_date, v.due_date, v.disp_order_date, v.disp_deliver_date, v.disp_due_date
, v.is_vat, v.has_sample, v.pattern_rowid, v.pattern, v.process_code, v.process_status
,fnc__dispVATType(v.is_vat) AS disp_vat_type, COALESCE(v.is_tax_inv_req, 0) AS is_tax_inv_req
, v.total_price_sum, v.total_deposit_payment, v.arr_deposit_log, v.total_close_payment, v.arr_payment_log
, v.total_price_sum - v.total_deposit_payment - v.total_close_payment AS total_left_amount
, fnc_order_avai_status(v.ps_rowid) AS avail_process_status, v.supplier_rowid, v.supplier_name
FROM v_order_report_status v
WHERE COALESCE(v.is_cancel, 0) < 1 

EOT;

		if (isset($arrObj['is_active_status']) && ($arrObj['is_active_status'])) {
			$_sql .= "\nAND (COALESCE(v.ps_rowid, 1) >= 10 AND (v.ps_rowid != 60))\n";
			unset($arrObj['is_active_status']);
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, 
			array(
				'job_number' => array("type"=>"txt", 'dbcol'=>'v.job_number'),
				'date_from' => array('type'=>'dat', 'dbcol'=>'v.order_date', 'operand'=>'>='),
				'date_to' => array('type'=>'dat', 'dbcol'=>'v.order_date', 'operand'=>'<=')
			)
		);
		$_sql .= $this->_getCheckAccessRight("v.create_by", "order");
		$_sql .= ' LIMIT 3000';

		return $this->arr_execute($_sql);
	}

	function change_status_by_id($rowid, $ps_rowid, $status_remark = FALSE) {
		$_rowid = $this->db->escape((int) $rowid);
		$_ps_rowid = $this->db->escape((int) $ps_rowid);

		if ($status_remark) $this->db->set('status_remark', $status_remark);
		$this->db->set('ps_rowid', $_ps_rowid);
		$this->db->set('update_by', $this->db->escape((int)$this->session->userdata('user_id')));
		$this->db->where('rowid', $_rowid);
		$this->db->update($this->_TABLE_NAME);

		$this->error_message = $this->db->error()['message'];
		return true;
	}
}