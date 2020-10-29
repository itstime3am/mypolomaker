<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_order_cap extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 't_order_cap';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'job_number' => NULL
			,'ref_number' => NULL
			,'customer_rowid' => NULL
			,'order_date' => NULL
			,'due_date' => NULL
			,'deliver_date' => NULL
			,'is_vat' => NULL
			,'has_sample' => NULL
			,'cap_pattern_rowid' => NULL
			,'order_qty' => NULL
			,'order_price_each' => NULL
			,'file_image1' => NULL
			,'file_image2' => NULL
			,'remark1' => NULL
			,'remark2' => NULL
			,'create_by' => NULL
			,'create_date' => NULL
			,'update_by' => NULL
			,'update_date' => NULL
			,'is_tax_inv_req' => NULL
			,'file_image3' => NULL
			,'file_image4' => NULL
			,'file_image5' => NULL
			,'file_image6' => NULL
			,'file_image7' => NULL
			,'file_image8' => NULL
			,'file_image9' => NULL
			,'ps_rowid' => NULL
			,'status_remark' => NULL
			,'supplier_rowid' => NULL
		);
	}
	function search($arrObj = array()) {
		$_sql = <<<EOT
SELECT t.rowid, t.job_number, t.ref_number, t.customer_rowid, v.customer, t.order_date, t.deliver_date, t.due_date
,TO_CHAR(t.order_date, 'DD/MM/YYYY') AS disp_order_date, TO_CHAR(t.due_date, 'DD/MM/YYYY') AS disp_due_date
,TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date, t.is_vat, t.has_sample, t.cap_pattern_rowid, t.order_qty, t.order_price_each,
t.file_image1, t.file_image2, t.remark1, t.remark2, d.cap_type_rowid, d.color, d.color_add1, d.color_add2, d.brim_color, 
COALESCE(d.is_sandwich_rim, 0) AS is_sandwich_rim, d.sandwich_rim_color, COALESCE(d.is_air_flow, 0) AS is_air_flow, d.air_flow_color,
d.cap_button_color, d.cap_belt_type_rowid, d.remark1 AS detail_remark1, d.remark2 AS detail_remark2 
, fnc__dispVATType(t.is_vat) AS disp_vat_type, COALESCE(t.is_tax_inv_req, 0) AS is_tax_inv_req
, v.total_price_sum, v.deposit_payment, v.arr_deposit_log, v.close_payment, v.arr_payment_log
, v.total_price_sum - v.deposit_payment - v.close_payment AS left_amount, t.supplier_rowid
, fnc_order_avai_status(t.ps_rowid) AS avail_process_status, ps.code AS ps_code, ps.name AS process_status 
FROM {$this->_TABLE_NAME} t
	INNER JOIN v_order_report_status v ON v.type_id = 5 AND v.order_rowid = t.rowid 
	INNER JOIN t_order_detail_cap d ON t.rowid = d.order_rowid 
	LEFT OUTER JOIN pm_t_quotation_detail qd ON qd.rowid = v.quotation_detail_rowid
	LEFT OUTER JOIN pm_t_quotation q ON qd.quotation_rowid = q.rowid
	LEFT OUTER JOIN m_process_status ps ON t.ps_rowid = ps.rowid
WHERE COALESCE(t.is_cancel, 0) < 1 
EOT;
		if (isset($arrObj['is_active_status']) && ($arrObj['is_active_status'])) {
			$_sql .= "\nAND (COALESCE(t.ps_rowid, 1) >= 10 AND (t.ps_rowid != 60) AND (q.status_rowid BETWEEN 80 AND 89))\n";
			unset($arrObj['is_active_status']);
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, 
			array(
				'job_number' => array("type"=>"txt"),
				'date_from' => array('type'=>'dat', 'dbcol'=>'t.order_date', 'operand'=>'>='),
				'date_to' => array('type'=>'dat', 'dbcol'=>'t.order_date', 'operand'=>'<=')
			)
		);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_sql .= 'LIMIT 3000';

		return $this->arr_execute($_sql);
	}
	
	function list_job_number() {
		$_sql = <<<EOT
SELECT t.rowid, t.job_number 
FROM {$this->_TABLE_NAME} t 
WHERE True 
EOT;
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_sql .= " ORDER BY t.rowid, t.job_number ";
		return $this->arr_execute($_sql);	
	}

	function get_detail_by_id($RowID) {
		$_sql = <<<EOT
SELECT t.* 
FROM v_order_cap t 
WHERE t.rowid = ?
EOT;
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_arr = $this->arr_execute($_sql, array($RowID));
		if (is_array($_arr) && count($_arr) > 0) {
			return $_arr[0];
		} else {
			return FALSE;
		}
	}
	
	function delete($RowID) {
		$_rowid = $this->db->escape((int) $RowID);
		if ($_rowid > 0) {
			while ($this->error_message == '') {
				$this->db->delete('t_order_detail_cap', array('order_rowid'=>$_rowid));
				if ($this->db->_error_message() !== "") {
					$this->error_message = $this->db->_error_message();
					break;
				}
				$this->db->delete('t_order_price_cap', array('order_rowid'=>$_rowid));
				if ($this->db->_error_message() !== "") {
					$this->error_message = $this->db->_error_message();
					break;
				}
				$this->db->delete('t_order_screen_cap', array('order_rowid'=>$_rowid));
				if ($this->db->_error_message() !== "") {
					$this->error_message = $this->db->_error_message();
					break;
				}
				return parent::delete($RowID);
				break;
			}
		} else {
			$this->error_message = 'ERROR:: Invalid parameter "order_rowid" ( ' . $RowID . ' )';
		}
	}

	function get_detail_report($RowID) {
		$_sql = <<<EOT
SELECT m.*
FROM v_order_cap m
WHERE m.rowid = ?
EOT;
		$_sql .= $this->_getCheckAccessRight("m.create_by", "order");
		$_arr = $this->arr_execute($_sql, array($RowID));
		$_item = array();
		if (is_array($_arr) && count($_arr) > 0) {
			$_item = $_arr[0];
			$_item['user_name'] = '';
			$_user = JFactory::getUser($_item['create_by']);
			if ($_user) $_item['user_name'] = $_user->username;

			return $_item;
		}
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