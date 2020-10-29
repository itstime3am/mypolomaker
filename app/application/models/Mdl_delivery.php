<?php
class Mdl_delivery extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_delivery';
		$this->_AUTO_FIELDS = array(
			'rowid' => NULL
		);
		$this->_FIELDS = array(
			'report_create_date'=>NULL,
			'deliver_date'=>NULL,
			'deliver_job_number'=>NULL,
			'title' => NULL,
			'customer_rowid' => NULL,
			'customer_name' => NULL,
			'company' => NULL,
			'tel' => NULL,
			'customer_address' => NULL,
			'contact' => NULL,
			'deliver_route' => NULL,
			'deliver_route_remark' => NULL,
			'product_deliver' => NULL,
			'product_deliver_other' => NULL,
			'attachment' => NULL,
			'attachment_other' => NULL,
			'is_vat' => NULL,
			'total'=>NULL,
			'vat'=>NULL,
			'grand_total'=>NULL,
			'deposit_route' => NULL,
			'deposit_amount' => NULL,
			'payment_route' => NULL,
			'payment_amount' => NULL,
			'deliver_amount' => NULL,
			'left_amount' => NULL,
			'remark1' => NULL,
			'remark2' => NULL,
			'create_by' => NULL,
			'create_date' => NULL,
			'update_by' => NULL,
			'update_date' => NULL
			,'percent_discount' => NULL
		);
	}
	
	function search($arrObj = array()) {
		include ( APPPATH.'config/database.php' );
		$_sql = <<<QUERY
SELECT t.rowid, t.report_create_date, t.deliver_date, t.deliver_job_number, t.code, t.title, t.customer_rowid, t.customer_name
, t.company, t.tel, t.customer_address, t.contact, t.deliver_route, t.deliver_route_remark, t.product_deliver, t.product_deliver_other
, t.attachment, t.attachment_other, t.is_vat, t.total, t.vat, t.grand_total, t.deliver_amount, t.left_amount, t.remark1, t.remark2
, jn.last_deposit_route AS deposit_route, jn.total_deposit_payment AS deposit_amount
, jn.last_close_route AS payment_route, jn.total_close_payment AS payment_amount
, t.customer_name AS customer_display, TO_CHAR(t.grand_total, '9G999G990D00') AS disp_grand_total
, CONCAT(COALESCE(t.remark1, ''), COALESCE(t.remark2, '')) AS disp_remark
, jn.list_job_numbers
, TO_CHAR(t.report_create_date, 'DD/MM/YYYY') AS disp_report_create_date
, TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date
, COALESCE(uc.name, ' - ') AS create_user, COALESCE(uu.name, ' - ') AS update_user
, fnc__dispVATType(t.is_vat) AS disp_vat_type
, TO_CHAR(jn.last_deposit_date, 'YYYY/MM/DD') AS deposit_date
, TO_CHAR(jn.last_close_date, 'YYYY/MM/DD') AS payment_date
, CASE 
	WHEN COALESCE(ap.rowid, 0) < 1 THEN 0 
	WHEN ap.deliver_status_id IN (60, 80) THEN 2
	WHEN (COALESCE(ap.deliver_status_id, 0) + COALESCE(ap.total_packs, 0) + COALESCE(ap.total_items, 0)) > 0 THEN 5
	ELSE 1
END AS is_approved
, COALESCE(t.tel, t.contact) AS contact_no, ca.arr_full_address AS customer_full_addresses
FROM pm_t_delivery t 
	LEFT OUTER JOIN pm_t_delivery_approve ap ON ap.delivery_rowid = t.rowid -- AND COALESCE(ap.is_rejected, 0) < 1 
	LEFT OUTER JOIN v_delivery_payment jn ON jn.delivery_rowid = t.rowid
	LEFT OUTER JOIN v_customer_address ca ON ca.customer_rowid = t.customer_rowid 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON t.create_by = uc.id 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uu ON t.update_by = uu.id 
WHERE COALESCE(t.is_deleted, 0) < 1

QUERY;

		$_arrSpecWhere = array(
			'date_from' => array('type'=>'dat', 'dbcol'=>'t.report_create_date', 'operand'=>'>=')
			, 'date_to' => array('type'=>'dat', 'dbcol'=>'t.report_create_date', 'operand'=>'<=')
			, 'create_user_id' => array('dbcol'=>'uc.id', 'type'=>'int')
			, 'update_user_id' => array('dbcol'=>'uu.id', 'type'=>'int')
		);
		if (array_key_exists('deliver_job_number', $arrObj)) {
			$_job_no = $arrObj['deliver_job_number'];
			if (strlen(trim($_job_no)) > 0) {
				$_sql .= <<<JBN
AND (
	t.code LIKE '%{$_job_no}%' 
	OR t.deliver_job_number LIKE '%{$_job_no}%' 
)

JBN;
// 	-- GROUP_CONCAT(DISTINCT(v.job_number) ORDER BY jn.seq SEPARATOR ', ') LIKE '%{$_job_no}%' 
			}
			unset($arrObj['deliver_job_number']);
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, $_arrSpecWhere);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "delivery");
		$_sql .= "ORDER BY t.report_create_date, t.deliver_date DESC LIMIT 3000 ";
		
		return $this->arr_execute($_sql);
	}
	
	function commit($arrObj, $blnCommitNullValue = true) {
//var_dump($arrObj);exit;
		$this->db->trans_begin();
		foreach ($this->_FIELDS as $key=>$value) {
			$this->_FIELDS[$key] = null;
		}
		foreach ($arrObj as $key=>$value) {
			if (array_key_exists($key, $this->_FIELDS)) {
				if (substr($key, -5) == '_date') {
					$_datValue = $this->_datFromPost($value);
					if ($_datValue instanceof DateTime) $this->_FIELDS[$key] = $_datValue;
				} else {
					$this->_FIELDS[$key] = $value;
				}
			}
		}
//var_dump($this->_FIELDS);exit;
		$_blnIsInsert = TRUE;
		if (array_key_exists('rowid', $arrObj) && (trim($arrObj['rowid']) > 0)) $_blnIsInsert = FALSE;
		if ($_blnIsInsert) {
			$this->insert();
			$_rowid = $this->last_insert_id;
		} else {
			$_rowid = trim($arrObj['rowid']);
			$this->update(array('rowid' => $_rowid), FALSE);
		}
		//$this->db->delete('pm_t_delivery_detail', array('delivery_rowid'=>$_rowid));
		if (array_key_exists('details', $arrObj)) {
			$_arrDetails = json_decode($arrObj['details']);
			if ((is_array($_arrDetails)) && (count($_arrDetails) > 0)) {
				$_bulk = array();
				foreach ($_arrDetails as $_obj) {
					$_new = array(
						'delivery_rowid' => $_rowid
						, 'seq' => $_obj->seq
						, 'title' => $_obj->title
						, 'qty' => $_obj->qty
						, 'price' => $_obj->price
						, 'total_amount' => $_obj->total_amount
						, 'create_by' => $this->session->userdata('user_id')
					);
					if (property_exists($_obj, 'order_type_id') && ($_obj->order_type_id > 0)) $_new['order_type_id'] = $_obj->order_type_id;
					if (property_exists($_obj, 'order_rowid') && ($_obj->order_rowid > 0)) $_new['order_rowid'] = $_obj->order_rowid;
					if (property_exists($_obj, 'order_detail_rowid') && ($_obj->order_detail_rowid > 0)) $_new['order_detail_rowid'] = $_obj->order_detail_rowid;
					array_push($_bulk, $_new);
				}
				if (count($_bulk) > 0) {
					$this->db->insert_batch('pm_t_delivery_detail', $_bulk);
				}
				if ($this->db->error()['message'] !== "") $_strError = $this->db->error()['message'];
			}
		}

		if (($this->db->trans_status() === FALSE) || ($this->error_message != '')) {
			$this->error_message = 'DB Transaction error::' . $this->error_message;
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_complete();
			return TRUE;
		}	
	}
	
	function get_detail_by_id($rowid) {
		$_sql = <<<DET
SELECT d.*, d.order_type_id AS type_id, d.qty AS left_qty
, COALESCE(TO_CHAR(d.price, '9G999G990D00'), '--') AS disp_price
, COALESCE(TO_CHAR(d.total_amount, '9G999G999G990D00'), '--') AS disp_amount
, CASE WHEN v.sum_qty > 0 THEN v.deposit_payment / v.sum_qty ELSE v.deposit_payment END AS avg_deposit_payment
, CASE WHEN v.sum_qty > 0 THEN v.close_payment / v.sum_qty ELSE v.close_payment END AS avg_close_payment 
, COALESCE(q.percent_discount, 0) AS percent_discount, TO_CHAR(COALESCE(q.percent_discount, 0), 'FM9G999G990D00') AS disp_discount
, ((COALESCE(q.percent_discount, 0) / 100) * COALESCE(d.price, 0)) * COALESCE(d.qty, 0) AS total_discount
, d.total_amount - (((COALESCE(q.percent_discount, 0) / 100) * COALESCE(d.price, 0)) * COALESCE(d.qty, 0)) AS actual_amount
FROM pm_t_delivery_detail d 
	LEFT OUTER JOIN v_order_report_status v 
		ON v.type_id = d.order_type_id 
		AND v.order_rowid = d.order_rowid 
		AND COALESCE(v.order_detail_rowid, -1) = COALESCE(d.order_detail_rowid, -1)
	LEFT OUTER JOIN pm_t_quotation_detail qd ON qd.rowid = v.quotation_detail_rowid 
	LEFT OUTER JOIN pm_t_quotation q ON q.rowid = qd.quotation_rowid 
WHERE d.delivery_rowid = $rowid 
ORDER BY d.seq

DET;
		return $this->arr_execute($_sql);
	}
	
	function get_linked_job_number($rowid) {
		$_sql = <<<JNBR
SELECT o.order_type_id, o.order_rowid, v.category, v.type, v.job_number
, v.customer, v.company, TO_CHAR(v.raw_order_date, 'DD/MM/YYYY') AS disp_order_date
FROM (
		SELECT delivery_rowid, order_type_id, order_rowid, MAX(seq) AS seq
		FROM pm_t_delivery_detail
		GROUP BY delivery_rowid, order_type_id, order_rowid
) o
	INNER JOIN v_order_report_status v ON v.type_id = o.order_type_id AND v.order_rowid = o.order_rowid
WHERE o.delivery_rowid = $rowid
GROUP BY o.order_type_id, o.order_rowid, v.category, v.type, v.job_number
, v.customer, v.company, TO_CHAR(v.raw_order_date, 'DD/MM/YYYY')
ORDER BY CASE WHEN o.order_type_id IN (1,5,6) THEN 1 ELSE 0 END
JNBR;
/* sorting to set loading library to gen pdf as last items ( to prevent unknown bugs until now, cannot generate pdf file ) */
		return $this->arr_execute($_sql);
	}

	function arr_get_report_data($rowid) {
		include ( APPPATH.'config/database.php' );
		$_arrReturn = FALSE;
		
		$_sql = <<<MAS
SELECT m.*, COALESCE(NULLIF(m.customer_address, 'null'), '') AS disp_customer_address
, TO_CHAR(m.report_create_date, 'DD/MM/YYYY') AS disp_report_create_date, TO_CHAR(m.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date
, COALESCE(uc.name, ' - ') AS user_name
, fnc__dispVATType(m.is_vat) AS disp_vat_type
FROM pm_t_delivery m 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON m.create_by = uc.id 
WHERE m.rowid = $rowid 
MAS;

		$_arr = $this->arr_execute($_sql);
		if (($_arr !== FALSE) && (count($_arr) > 0)) {
			$_arrReturn = $_arr[0];
			$_user_id = (array_key_exists('create_by', $_arrReturn) && is_numeric($_arrReturn['create_by']))?$_arrReturn['create_by']:-1;
			//$_arrReturn['user_tel'] = json_decode($_arrReturn['profile_value']);
			$_arrReturn['user_branch'] = '';
			if ($_user_id > 0) {
				$_sql = <<<PRF
SELECT ug.title 
FROM {$db['joomla']['database']}.{$db['joomla']['dbprefix']}user_usergroup_map ugm 
	INNER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}usergroups ug ON ug.id = ugm.group_id 
WHERE ugm.user_id = $_user_id 
AND ug.title LIKE 'สาขา%'  
ORDER BY ugm.group_id 
PRF;
				$_arrGroups = $this->arr_execute($_sql);
				if (($_arrGroups !== FALSE) && (count($_arrGroups) > 0)) {
					//foreach ($_arrGroups as $_row) $_arrReturn['user_branch'] .= $_row['title'] . ', ';
					$_arrReturn['user_branch'] = $_arrGroups[0]['title'];
				}
			}
			
			$_arrDetails = $this->get_detail_by_id($rowid);
			if ($_arrDetails !== FALSE) $_arrReturn['details'] = $_arrDetails;
		}
	
		return $_arrReturn;
	}
	
	function unlink_job_number($rowid, $delivery_rowid) {
		return $this->db->delete('pm_t_delivery_jobno', array('rowid'=>$rowid, 'delivery_rowid'=>$delivery_rowid));
	}
	
	function approve($rowid) {
		return $this->db->insert('pm_t_delivery_approve', array(
			'delivery_rowid' => $rowid
			, 'approve_by' => $this->session->userdata('user_id')
		));
	}
	
	function revoke($rowid) {
		return $this->db->delete('pm_t_delivery_approve', array('delivery_rowid'=>$rowid));
	}
}