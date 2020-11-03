<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_order_premade_other extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 't_order_premade_other';
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
			,'supplier_rowid' => NULL
			,'is_vat' => NULL
			,'has_sample' => NULL
			,'is_tax_inv_req' => NULL
			,'total_price' => NULL
			,'file_image1' => NULL
			,'file_image2' => NULL
			,'file_image3' => NULL
			,'file_image4' => NULL
			,'file_image5' => NULL
			,'file_image6' => NULL
			,'file_image7' => NULL
			,'file_image8' => NULL
			,'file_image9' => NULL
			,'product_type_rowid' => NULL
			,'remark1' => NULL
			,'remark2' => NULL
			,'create_by' => NULL
			,'create_date' => NULL
			,'update_by' => NULL
			,'update_date' => NULL
			/*,'is_cancel' => 0
			,'cancel_by' => NULL
			,'cancel_date' => NULL*/
			,'status_remark' => NULL
			,'ps_rowid' => NULL
			//,'quotation_detail_rowid' => NULL
		);
	}
	function search($arrObj = array()) {
		$_sql = <<<EOT
SELECT t.rowid, t.job_number, t.ref_number, t.customer_rowid, v.customer, t.order_date, t.deliver_date, t.due_date
, TO_CHAR(t.order_date, 'DD/MM/YYYY') AS disp_order_date, TO_CHAR(t.due_date, 'DD/MM/YYYY') AS disp_due_date
, TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date, t.is_vat, t.has_sample, t.file_image1, t.file_image2
, t.remark1, t.remark2, t.product_type_rowid, pt.name AS product_type
, fnc__dispVATType(t.is_vat) AS disp_vat_type, COALESCE(t.is_tax_inv_req, 0) AS is_tax_inv_req
, v.total_price_sum, v.deposit_payment, v.arr_deposit_log, v.close_payment, v.arr_payment_log
, v.total_price_sum - v.deposit_payment - v.close_payment AS left_amount, t.supplier_rowid, v.order_detail_rowid
, fnc_order_avai_status(v.ps_rowid) AS avail_process_status, ps.code AS ps_code, ps.name AS process_status 
FROM {$this->_TABLE_NAME} t 
	INNER JOIN v_order_report_status v ON v.type_id IN (12, 14) AND v.order_rowid = t.rowid 
	INNER JOIN m_other_product_type pt ON pt.rowid = t.product_type_rowid 
	LEFT OUTER JOIN pm_t_quotation_detail qd ON qd.rowid = v.quotation_detail_rowid
	LEFT OUTER JOIN pm_t_quotation q ON qd.quotation_rowid = q.rowid
	LEFT OUTER JOIN m_process_status ps ON v.ps_rowid = ps.rowid
WHERE COALESCE(t.is_cancel, 0) < 1 

EOT;
		if (isset($arrObj['is_active_status']) && ($arrObj['is_active_status'])) {
			$_sql .= "\nAND (COALESCE(t.ps_rowid, 1) >= 10 AND (t.ps_rowid != 60))\n"; // AND (q.status_rowid BETWEEN 80 AND 89)
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
		$_sql .= 'ORDER BY t.rowid DESC LIMIT 3000';

		return $this->arr_execute($_sql);
	}

	function list_size_quan() {
		$_sql = <<<EOT
SELECT s.rowid, c.rowid AS cat_rowid, c.name AS category, sc.rowid as sub_rowid, sc.name AS sub_category, s.size_text, s.size_chest 
FROM pm_m_premade_order_size s
	INNER JOIN pm_m_order_size_cat c ON s.main_cat_rowid = c.rowid 
	INNER JOIN pm_m_order_size_sub_cat sc ON s.sub_cat_rowid = sc.rowid 
WHERE s.is_cap = 1 
AND s.is_use = 1 
ORDER BY c.rowid, sc.rowid, s.rowid
EOT;
		$_arr = $this->arr_execute($_sql);
		$_arrData = array();
		$_arrIndex = array("cat" => array(), "sub" => array());
		if ($_arr !== FALSE) {
			foreach ($_arr as $_row) {
				if (! array_key_exists($_row['cat_rowid'], $_arrIndex["cat"])) {
					$_arrIndex["cat"][$_row['cat_rowid']] = $_row['category'];
				}
				if (! array_key_exists($_row['sub_rowid'], $_arrIndex["sub"])) {
					$_arrIndex["sub"][$_row['sub_rowid']] = $_row['sub_category'];
				}
				if (! array_key_exists($_row['cat_rowid'], $_arrData)) {
					$_arrData[$_row['cat_rowid']] = array();
				}
				if (! array_key_exists($_row['sub_rowid'], $_arrData[$_row['cat_rowid']])) {
					$_arrData[$_row['cat_rowid']][$_row['sub_rowid']] = array();
				}
				$_arrData[$_row['cat_rowid']][$_row['sub_rowid']][$_row['size_text']] = array('rowid'=>$_row['rowid'], 'chest' => $_row['size_chest']);
			}
		}
		return array("appendix" => $_arrIndex, "data" => $_arrData);
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
		$_arrReturn = FALSE;
		$_whileChecker = TRUE;
		while ($_whileChecker) {
		
			$_sql = <<<EOT
SELECT t.rowid, t.job_number, t.ref_number, t.customer_rowid, t.order_date, t.deliver_date, t.due_date
, TO_CHAR(t.order_date, 'DD/MM/YYYY') AS disp_order_date, TO_CHAR(t.due_date, 'DD/MM/YYYY') AS disp_due_date
, TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date, t.is_vat, t.has_sample, t.file_image1, t.file_image2
, TO_CHAR(COALESCE(t.total_price, 0), '9G999G990D00') AS total_price, t.remark1, t.remark2
, fnc__dispVATType(t.is_vat) AS disp_vat_type, COALESCE(t.is_tax_inv_req, 0) AS is_tax_inv_req
, COALESCE(c.display_name, '') AS customer_name, COALESCE(c.company, '') AS company
, COALESCE(c.mobile, c.tel, '') AS contact_no, COALESCE(c.tax_id, '') AS tax_id, COALESCE(c.tax_branch, '') AS tax_branch
, COALESCE(CONCAT_WS(' ', ca.address, cap.name_th, ca.postal_code), '') AS address, t.supplier_rowid
FROM {$this->_TABLE_NAME} AS t
	LEFT OUTER JOIN pm_t_customer c ON t.customer_rowid = c.rowid 
	LEFT OUTER JOIN pm_t_customer_address ca ON ca.customer_rowid = c.rowid 
	LEFT OUTER JOIN pm_m_province cap ON cap.rowid = ca.province_rowid 
WHERE t.rowid = $RowID
EOT;
			$_master = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if (count($_master) > 0) {
				$_master = $_master[0];
			}
			
			//++ details
			$_sql = <<<EDT
SELECT d.rowid, p.rowid AS pattern_rowid, p.code, d.color
FROM t_order_premade_detail_other AS d
	INNER JOIN t_order_premade_other m ON m.rowid = d.order_rowid
	INNER JOIN m_other_premade_pattern AS p ON p.product_type_rowid = m.product_type_rowid AND p.rowid = d.pattern_rowid 
WHERE d.order_rowid = $RowID
EDT;
			$_arr1 = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			$_arrDetails = array();
			if (is_array($_arr1)) {
				foreach ($_arr1 as $_row) {
					$_row['size'] = array();
					$_arrDetails[(string)$_row['rowid']] = $_row;
				}
			}

			//++ details size
			$_sql = <<<EOT
SELECT s.* 
FROM t_order_premade_detail_other AS d 
	INNER JOIN t_order_premade_size_other AS s ON d.rowid = s.order_detail_rowid
WHERE d.order_rowid = $RowID
EOT;
			$_arr2 = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if (is_array($_arr2)) {
				foreach ($_arr2 as $_row) {
					array_push($_arrDetails[(string)$_row['order_detail_rowid']]['size'], $_row);
				}
			}
			//-- details size
			//-- details

			// ++ others price
			$_sql = <<<OTP
SELECT t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_premade_price_other t 
WHERE t.order_rowid = $RowID 
ORDER BY t.seq 
OTP;
			$_arr3 = $this->m->arr_execute($_sql);
			$_strError = $this->m->error_message;
			if ($_strError != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if ($_arr3 == FALSE) $_arr3 = array();
			$_master['others_price'] = $_arr3;

			// -- others price

			//++ screen
			$_userid = $this->session->userdata('user_id');
				$_sql =<<<QUERY
				SELECT d.position, d.detail, d.job_hist, CONCAT('กว้าง ' ,tmp.width, ' | ', 'สูง ' ,tmp.height ) as size, s.name AS disp_type, ss.name as disp_status, s.screen_type, tmp.img, tmp.rowid as prod_rowid,
				tmp.fabric_date , tmp.block_date, tmp.block_emp, tmp.approve_date
				, ARRAY_TO_JSON(ARRAY(
				SELECT UNNEST(fnc_manu_screen_avai_status(tmp.prod_status))
				INTERSECT
				SELECT UNNEST(uac.arr_avail_status)
				)) AS arr_avail_status
				FROM v_order_report o
				INNER JOIN fnc_listmanuscreen_accright_byuser($_userid) uac ON True
				INNER JOIN (
					SELECT o.product_type_rowid AS type_id, s.order_rowid, s.order_screen_rowid, s.position, s.detail, s.size, s.job_hist, s.price, s.seq
					FROM t_order_premade_other o
					INNER JOIN t_order_premade_screen_other s ON s.order_rowid = o.rowid
				) d
				ON d.type_id = o.type_id
				AND d.order_rowid = o.order_rowid
				INNER JOIN pm_m_order_screen s on s.rowid = d.order_screen_rowid
				LEFT JOIN pm_t_manu_screen_production tmp on tmp.order_screen_rowid = d.order_screen_rowid and tmp.order_rowid = d.order_rowid and tmp.seq = d.seq
				LEFT JOIN m_manu_screen_status ss ON ss.rowid = tmp.prod_status
				LEFT join m_manu_screen_type mst on mst.rowid = tmp.screen_type
				--WHERE o.ps_rowid = 10
				WHERE COALESCE(o.is_cancel, 0) < 1
				AND s.screen_type  = 2
				AND d.order_rowid = $RowID
QUERY;
			$_arrSc = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if ($_arrSc == FALSE) $_arrSc = array();
			
			$_master["screen"] = $_arrSc;
			// pass variable only no any error in every processes, otherwise just throw FALSE exit while loop and return FALSE
			// ++ weave
			$_sql =<<<QUERY
			SELECT d.position, d.detail, d.job_hist, CONCAT('กว้าง ' ,tmp.width, ' | ', 'สูง ' ,tmp.height ) as size, s.name AS disp_type, ss.name as disp_status, s.screen_type, tmp.img, tmp.rowid as prod_rowid,
			tmp.fabric_date , tmp.block_date, tmp.block_emp, tmp.approve_date
			, ARRAY_TO_JSON(ARRAY(
			SELECT UNNEST(fnc_manu_weave_avai_status(tmp.prod_status))
			INTERSECT
			SELECT UNNEST(uac.arr_avail_status)
			)) AS arr_avail_status
			FROM v_order_report o
			INNER JOIN fnc_listmanuweave_accright_byuser($_userid) uac ON True
			INNER JOIN (
				SELECT o.product_type_rowid AS type_id, s.order_rowid, s.order_screen_rowid, s.position, s.detail, s.size, s.job_hist, s.price, s.seq
				FROM t_order_premade_other o
				INNER JOIN t_order_premade_screen_other s ON s.order_rowid = o.rowid
			) d
			ON d.type_id = o.type_id
			AND d.order_rowid = o.order_rowid
			INNER JOIN pm_m_order_screen s on s.rowid = d.order_screen_rowid
			LEFT JOIN pm_t_manu_weave_production tmp on tmp.order_weave_rowid = d.order_screen_rowid and tmp.order_rowid = d.order_rowid and tmp.seq = d.seq
			LEFT JOIN m_manu_weave_status ss ON ss.rowid = tmp.prod_status
			LEFT join m_manu_weave_type mst on mst.rowid = tmp.weave_type
			--WHERE o.ps_rowid = 10
			WHERE COALESCE(o.is_cancel, 0) < 1
			and s.screen_type  = 1
			AND d.order_rowid = $RowID
QUERY;

			$_arrSc = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if ($_arrSc == FALSE) $_arrSc = array();
			$_master["weave"] = $_arrSc;
			// pass variable only no any error in every processes, otherwise just throw FALSE exit while loop and return FALSE
			$_arrReturn = $_master;
			$_whileChecker = FALSE;
			//-- screen
		}
		return $_arrReturn;
	}

	function get_detail_report($RowID) {
		$RowID = $this->db->escape((int) $RowID);
		$_arrReturn = FALSE;
		$_whileChecker = TRUE;
		while ($_whileChecker) {
			$_sql = <<<EOT
SELECT v.*
, m.file_image1, m.file_image2, m.file_image3, m.file_image4, m.file_image5, m.file_image6, m.file_image7, m.file_image8
, m.file_image9, m.remark1, m.remark2, m.create_by, m.create_date, m.update_by, m.update_date
, v.option_promotion AS promotion, qt.revision AS quotation_revision, m.supplier_rowid, v.supplier_name
, qt.qo_number as quotation_number
FROM t_order_premade_other m
	INNER JOIN v_order v ON v.type_id IN (12, 14) AND v.order_rowid = m.rowid
	LEFT OUTER JOIN t_order_add_option op ON op.order_type_id = v.type_id AND op.order_rowid = v.order_rowid
	LEFT OUTER JOIN pm_t_quotation_detail qdt ON qdt.rowid = m.quotation_detail_rowid
	LEFT OUTER JOIN pm_t_quotation qt ON qt.rowid = qdt.quotation_rowid
WHERE COALESCE(v.is_cancel, 0) < 1 
AND m.rowid = $RowID
EOT;
			$_master = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if (count($_master) > 0) {
				$_master = $_master[0];
			}
			$_master['user_name'] = '';
			$user = JFactory::getUser($_master['create_by']);
			if ($user) $_master['user_name'] = $user->username;
			
			//++ details
			$_sql = <<<EDT
SELECT d.rowid AS order_detail_rowid, p.rowid AS pattern_rowid, p.code AS code, d.color, sp.price, SUM(sp.qty) AS sum_qty
, JSON_AGG((
	SELECT ROW_TO_JSON(x) FROM ( 
		SELECT sp.order_size_rowid, COALESCE(sp.qty, 0) AS qty
	) x
)) AS size
FROM t_order_premade_detail_other d 
	INNER JOIN t_order_premade_other m ON m.rowid = d.order_rowid
	INNER JOIN m_other_premade_pattern AS p ON p.product_type_rowid = m.product_type_rowid AND p.rowid = d.pattern_rowid 
	INNER JOIN t_order_premade_size_other AS sp ON d.rowid = sp.order_detail_rowid
WHERE d.order_rowid = $RowID
GROUP BY d.rowid, p.rowid, p.code, d.color, sp.price
EDT;
			$_master["detail"] = array("template"=>array(), "data" => $this->arr_execute($_sql));
			//-- details

			// ++ others price
			//t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price
			$_sql =<<<PRC
SELECT t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_premade_price_other t 
WHERE t.order_rowid = $RowID
ORDER BY t.seq 
PRC;
			$_arr3 = $this->m->arr_execute($_sql);
			$_strError = $this->m->error_message;
			if ($_strError != '') {
				$_whileChecker = FALSE;
				continue;
			}
			//if ($_arr3 == FALSE) $_arr3 = array();
			//$_master['others_price'] = $_arr3;
			if (is_array($_arr3)) {
				$_master['others_price'] = $_arr3;
			}

			// -- others price

			//++ screen
			$_sql = <<<EOT
SELECT t.order_screen_rowid, COALESCE(m.name, '') AS order_screen, t.position, t.detail, t.size, t.job_hist
, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_premade_screen_other t 
	LEFT OUTER JOIN pm_m_order_screen m ON t.order_screen_rowid = m.rowid 
WHERE t.order_rowid = $RowID 
ORDER BY t.seq 
EOT;
			$_arrSc = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if ($_arrSc == FALSE) $_arrSc = array();			
			$_master["screen"] = $_arrSc;
			// pass variable only no any error in every processes, otherwise just throw FALSE exit while loop and return FALSE
			$_arrReturn = $_master;
			$_whileChecker = FALSE;
			//-- screen
		}		
		return $_arrReturn;
	}

	function change_status_by_id($detail_rowid, $ps_rowid, $status_remark = FALSE) {
		$_detail_rowid = $this->db->escape((int) $detail_rowid);
		$_ps_rowid = $this->db->escape((int) $ps_rowid);
		
		if ($status_remark) $this->db->set('status_remark', $status_remark);
		$this->db->set('ps_rowid', $_ps_rowid);
		$this->db->set('update_by', $this->db->escape((int)$this->session->userdata('user_id')));
		$this->db->where('rowid', $_detail_rowid);
		$this->db->update('t_order_premade_detail_other');
		
		$this->error_message = $this->db->error()['message'];
		return true;
	}

}