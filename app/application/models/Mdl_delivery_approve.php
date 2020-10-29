<?php
class Mdl_delivery_approve extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_delivery_approve';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'delivery_rowid'=>'',
			'approve_by'=>'',
			'approve_date'=>'',
			'deliver_status_id'=>'',
			'deliver_by'=>'',
			'actual_deliver_datetime'=>'',
			'deliver_terminal'=>'',
			'terminal_recordno'=>'',
			'terminal_contactno'=>'',
			'total_packs'=>'',
			'total_items'=>'',
			'collected_cash'=>'',
			'delivering_fee'=>'',
			'remark'=>'',
			'is_rejected'=>'',
			'reject_by'=>'',
			'reject_date'=>''
			,'collected_method'=>''
		);
		$this->_ARR_DATATYPE_DATETIME = array('actual_deliver_datetime');
	}
	
	function search($arrObj = array()) {
		include ( APPPATH.'config/database.php');
		$_sql = <<<QUERY
SELECT ap.rowid, ap.delivery_rowid, ap.deliver_status_id, s.name AS status, t.customer_rowid
, t.customer_name AS customer, t.company, t.title, t.code AS delivery_code
, TO_CHAR(t.total, '9G999G990D00') AS disp_total, TO_CHAR(t.vat, '9G999G990D00') AS disp_vat
, TO_CHAR(t.grand_total, '9G999G990D00') AS disp_grand_total
, COALESCE(uc.name, ' - ') AS create_user, TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date
, jn.list_job_numbers, CONCAT_WS(' ', t.deliver_route, t.deliver_route_remark) AS deliver_route
, COALESCE(t.contact, t.customer_address) AS address, p.name_th AS province
, ap.deliver_by, t.left_amount, ap.collected_cash
, TO_CHAR(ap.actual_deliver_datetime, 'DD/MM/YYYY HH24:MI') AS actual_deliver_datetime
, ap.deliver_terminal, ap.terminal_recordno, ap.terminal_contactno, ap.total_packs
, COALESCE(ap.total_items, jn.sum_qty) AS total_items, ap.delivering_fee
, CONCAT_WS(' ', t.remark1, t.remark2) AS disp_remark, ap.collected_method, ap.remark
FROM pm_t_delivery_approve ap
	INNER JOIN pm_t_delivery t ON ap.delivery_rowid = t.rowid AND COALESCE(ap.is_rejected, 0) < 1
	LEFT OUTER JOIN m_deliver_status s ON COALESCE(ap.deliver_status_id, 0) = s.rowid 
	LEFT OUTER JOIN pm_t_customer_address ca ON ca.customer_rowid = t.customer_rowid
	LEFT OUTER JOIN pm_m_province p ON ca.province_rowid = p.rowid
	LEFT OUTER JOIN (
		SELECT o.delivery_rowid, SUM(o.sum_qty) AS sum_qty, STRING_AGG(DISTINCT(v.job_number), ',') AS list_job_numbers
		, MIN(v.deposit_date) AS min_deposit_date, MIN(v.close_date) AS min_close_date
		FROM (
			SELECT delivery_rowid, order_type_id, order_rowid, SUM(qty) AS sum_qty
			FROM pm_t_delivery_detail
			GROUP BY delivery_rowid, order_type_id, order_rowid
		) o
			INNER JOIN v_order_report_status v ON v.type_id = o.order_type_id AND v.order_rowid = o.order_rowid
		GROUP BY o.delivery_rowid
	) jn ON jn.delivery_rowid = ap.delivery_rowid
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON t.create_by = uc.id 
WHERE COALESCE(ap.is_rejected, 0) < 1

QUERY;
		$_arrSpecWhere = array(
			'category_id' => array('dbcol'=>'t.category_type_id','type'=>'int')
			, 'customer_rowid' => array('dbcol'=>'t.customer_rowid','type'=>'int')
			, 'company' => array('dbcol'=>'t.company','type'=>'txt')
			, 'deliver_status_id' => array('dbcol'=>'ap.deliver_status_id','type'=>'int')
			, 'create_user_id' => array('dbcol'=>'uc.id', 'type'=>'int')
			, 'update_user_id' => array('dbcol'=>'uu.id', 'type'=>'int')
		);

		if (array_key_exists('job_number', $arrObj)) {
			$_job_no = $arrObj['job_number'];
			if (strlen(trim($_job_no)) > 0) {
				$_sql .= <<<JBN
AND (
	t.code ILIKE '%{$_job_no}%' 
	OR jn.list_job_numbers ILIKE '%{$_job_no}%' 
	OR t.deliver_job_number ILIKE '%{$_job_no}%' 
)

JBN;
// 	-- GROUP_CONCAT(DISTINCT(v.job_number) ORDER BY jn.seq SEPARATOR ', ') LIKE '%{$_job_no}%' 
			}
			unset($arrObj['job_number']);
		}
		
		$_condition_date_field = 't.deliver_date';
		if (array_key_exists('date_type', $arrObj) && is_numeric($arrObj['date_type'])) {
			$_date_type = (int) $arrObj['date_type'];
			if ($_date_type == 2) {
				$_condition_date_field = 't.report_create_date';
			}
			unset($arrObj['date_type']);
		}
		if (array_key_exists('date_from', $arrObj)) {
			$datDateFrom = $this->_datFromPost($arrObj['date_from']);
			if ($datDateFrom !== '') {
				$_arrSpecWhere['date_from'] = array('dbcol'=>$_condition_date_field, 'operand'=>'>=', 'val'=>$datDateFrom->format('Y-m-d'));
			} else {
				unset($arrObj['date_from']);
			}
		}
		if (array_key_exists('date_to', $arrObj)) {
			$datDateTo = $this->_datFromPost($arrObj['date_to']);
			if ($datDateTo !== '') {
				$_arrSpecWhere['date_to'] = array('dbcol'=>$_condition_date_field, 'operand'=>'<=', 'val'=>$datDateTo->format('Y-m-d'));
			} else {
				unset($arrObj['date_to']);
			}
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, $_arrSpecWhere);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "delivery");
		$_sql .= "ORDER BY t.report_create_date, t.deliver_date DESC ";
//echo $_sql;exit;
		return $this->arr_execute($_sql);
	}
	
	function commit($arrObj, $blnCommitNullValue = true) {
//var_dump($arrObj);exit;
		$this->success_rows = 0;
		$_BASE_FIELDS = array();
		foreach ($this->_FIELDS as $key=>$value) {
			array_push($_BASE_FIELDS, $key);
		}
		$this->db->trans_begin();
		foreach ($arrObj as $index=>$obj) {
			unset($this->_FIELDS);
			$this->_FIELDS = array();
			foreach ($obj as $_k=>$_v) {
				if (in_array($_k, $_BASE_FIELDS)) {
					if ((substr($_k, -5) == '_date')) {
						$_datValue = $this->_datFromPost($_v);
						if ($_datValue instanceof DateTime) $this->_FIELDS[$_k] = $_datValue;
					} elseif ((substr($_k, -9) == '_datetime')) {
						$_datValue = $this->_datFromPost($_v);
						if ($_datValue instanceof DateTime) $this->_FIELDS[$_k] = $_datValue;
					} elseif (isset($_v)) {
						$this->_FIELDS[$_k] = $_v;
					}
				}
			}
			$_blnIsInsert = TRUE;
			if (array_key_exists('rowid', $obj) && (trim($obj['rowid']) > '0')) $_blnIsInsert = FALSE;

			if ($_blnIsInsert) {
/*++ Inferno 20150729 fix duplicate report error */
				//$this->insert();
				$__cols = '';
				$__vals = '';
				$__dupStm = '';
				foreach ($this->_FIELDS as $_k=>$_v) {
					$__cols .= $_k . ',';
					$__dupStm .= $_k . '=VALUES(' . $_k . '),';
					if ($_v instanceof DateTime) {
						$__vals .= 'STR_TO_DATE(' . $this->db->escape($_v->format('Y/m/d H:i:s')) . ", '%Y/%m/%d %H:%i:%s'),";
					} else {
						$__vals .= $this->db->escape($_v) . ',';
					}
				}
				if (strlen($__cols) > 0) {
					$__cols = substr($__cols, 0, -1);
					$__vals = substr($__vals, 0, -1);
					$__dupStm = substr($__dupStm, 0, -1);
				}
				$_insSQL = <<<INSSQL
INSERT INTO {$this->_TABLE_NAME} ($__cols)
VALUES ($__vals)
ON DUPLICATE KEY UPDATE 
$__dupStm
;

INSSQL;
				$this->db->query($_insSQL);
				$this->error_message .= $this->db->_error_message();
/*-- Inferno 20150729 fix duplicate report error */				
			} else {
				$this->update(array('rowid' => $obj['rowid']));
			}
			if ($this->error_message != '') break;
			$this->success_rows += 1;
		}
//echo $this->success_rows . "\n<br>";exit;
		if (($this->db->trans_status() === FALSE) || ($this->error_message != '')) {
			$this->error_message = 'DB Transaction error::' . $this->error_message;
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_complete();
			return TRUE;
		}		
	}
}