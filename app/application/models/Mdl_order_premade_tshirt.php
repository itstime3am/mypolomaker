<?php
class Mdl_order_premade_tshirt extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_order_premade_tshirt';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'job_number' => '',
			'ref_number' => '',
			'customer_rowid' => '',
			'order_date' => '',
			'due_date' => '',
			'deliver_date' => '',
			'is_vat' => '',
			'has_sample' => '',
			'file_image1' => '',
			'file_image2' => '',
			'total_price' => '',
			'remark1' => '',
			'remark2' => '',
			'create_by' => '',
			'create_date' => '',
			'update_by' => '',
			'update_date' => ''
			,'is_tax_inv_req' => ''
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
, TO_CHAR(t.order_date, 'DD/MM/YYYY') AS disp_order_date, TO_CHAR(t.due_date, 'DD/MM/YYYY') AS disp_due_date
, TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date, t.is_vat, t.has_sample
, t.file_image1, t.file_image2, t.remark1, t.remark2
, fnc__dispVATType(t.is_vat) AS disp_vat_type, COALESCE(t.is_tax_inv_req, 0) AS is_tax_inv_req
, v.total_price_sum, v.deposit_payment, v.arr_deposit_log, v.close_payment, v.arr_payment_log
, v.total_price_sum - v.deposit_payment - v.close_payment AS left_amount, t.supplier_rowid, v.order_detail_rowid
, fnc_order_avai_status(v.ps_rowid) AS avail_process_status, ps.code AS ps_code, ps.name AS process_status 
, (select count(m.order_rowid) from pm_t_manu_screen_production m where m.order_rowid  = t.rowid and m.prod_status = 30 ) as prod_screen_count
, (select count(m.order_rowid) from pm_t_manu_weave_production m where m.order_rowid  = t.rowid and m.prod_status = 30 ) as prod_weave_count
FROM {$this->_TABLE_NAME} t
	INNER JOIN v_order_report_status v ON v.type_id = 4 AND v.order_rowid = t.rowid 
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
				, 'company' => array("type"=>"txt", "dbcol" => 'v.company')
			)
		);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_sql .= 'ORDER BY t.rowid DESC LIMIT 3000';

		return $this->arr_execute($_sql);
	}

	function list_size_quan() {
		$_sql = <<<EOT
SELECT s.rowid, c.rowid AS cat_rowid, c.name AS category, sc.rowid as sub_rowid, sc.name AS sub_category, s.size_text, s.size_chest 
, CASE WHEN s.is_use > 0 THEN 0 ELSE 1 END AS is_expired
FROM pm_m_premade_order_size s
	INNER JOIN pm_m_order_size_cat c ON s.main_cat_rowid = c.rowid 
	INNER JOIN pm_m_order_size_sub_cat sc ON s.sub_cat_rowid = sc.rowid 
WHERE s.is_tshirt = 1 
-- AND s.is_use = 1 
ORDER BY c.rowid, sc.rowid, s.sort_index
-- CASE WHEN c.rowid = 8 THEN 0 ELSE c.rowid END, c.rowid, CASE sc.rowid WHEN 3 THEN 1 WHEN 2 THEN 2 WHEN 1 THEN 3 ELSE sc.rowid END, 
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
				$_arrData[$_row['cat_rowid']][$_row['sub_rowid']][$_row['size_text']] = array('rowid'=>$_row['rowid'], 'chest' => $_row['size_chest'], 'is_expired' => $_row['is_expired']);
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
SELECT m.rowid, m.job_number, m.ref_number, m.customer_rowid, m.order_date, m.deliver_date, m.due_date, TO_CHAR(m.order_date, 'DD/MM/YYYY') AS disp_order_date, TO_CHAR(m.due_date, 'DD/MM/YYYY') AS disp_due_date, TO_CHAR(m.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date, m.is_vat, m.has_sample, m.file_image1, m.file_image2, TO_CHAR(COALESCE(m.total_price, 0), '9G999G990D00') AS total_price, m.remark1, m.remark2
, fnc__dispVATType(m.is_vat) AS disp_vat_type, COALESCE(m.is_tax_inv_req, 0) AS is_tax_inv_req
, COALESCE(c.display_name, '') AS customer_name, COALESCE(c.company, '') AS company
, COALESCE(c.mobile, c.tel, '') AS contact_no, COALESCE(c.tax_id, '') AS tax_id, COALESCE(c.tax_branch, '') AS tax_branch
, COALESCE(CONCAT_WS(' ', ca.address, cap.name_th, ca.postal_code), '') AS address, m.supplier_rowid
FROM {$this->_TABLE_NAME} AS m
	LEFT OUTER JOIN pm_t_customer c ON m.customer_rowid = c.rowid 
	LEFT OUTER JOIN pm_t_customer_address ca ON ca.customer_rowid = c.rowid 
	LEFT OUTER JOIN pm_m_province cap ON cap.rowid = ca.province_rowid 
WHERE m.rowid = $RowID
EOT;
			$_master = $this->arr_execute($_sql);
			if ($this->error_message != '') {
				$_whileChecker = FALSE;
				continue;
			}
			if (count($_master) > 0) {
				$_master = $_master[0];
			}
			
			$_sql = <<<EDT
SELECT d.rowid, p.rowid AS pattern_rowid, p.code, d.color
FROM pm_t_order_premade_detail_tshirt AS d
	INNER JOIN pm_t_tshirt_pattern AS p ON d.pattern_rowid = p.rowid 
WHERE d.order_rowid = $RowID
EDT;
			//++ details
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
FROM pm_t_order_premade_detail_tshirt AS d 
	INNER JOIN pm_t_order_premade_size_tshirt AS s ON d.rowid = s.order_detail_rowid
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
			$_master["detail"] = $_arrDetails;
			//-- details size
			//-- details

			// ++ others price
			$_sql = "SELECT t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price ";
			$_sql .= 'FROM pm_t_order_premade_price_tshirt t WHERE t.order_rowid = ' . $RowID . ' ORDER BY t.seq ';
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
				tmp.fabric_date , tmp.block_date, tmp.block_emp, tmp.approve_date, d.seq, d.order_rowid, tmp.prod_status, tmp.status_remark
				, ARRAY_TO_JSON(ARRAY(
				SELECT UNNEST(fnc_manu_screen_avai_status(tmp.prod_status))
				INTERSECT
				SELECT UNNEST(uac.arr_avail_status)
				)) AS arr_avail_status
				FROM v_order_report o
				INNER JOIN fnc_listmanuscreen_accright_byuser($_userid) uac ON True
				INNER JOIN (
				SELECT 4 AS type_id, order_rowid, order_screen_rowid, position, detail, size, job_hist, price, seq
				FROM pm_t_order_premade_screen_tshirt
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
			
			$_master["screen_order"] = $_arrSc;
			// pass variable only no any error in every processes, otherwise just throw FALSE exit while loop and return FALSE
			// ++ weave
			$_sql =<<<QUERY
			SELECT d.position, d.detail, d.job_hist, CONCAT('กว้าง ' ,tmp.width, ' | ', 'สูง ' ,tmp.height ) as size, s.name AS disp_type, ss.name as disp_status, s.screen_type, tmp.img, tmp.rowid as prod_rowid,
			tmp.fabric_date , tmp.block_date, tmp.block_emp, tmp.approve_date, d.seq, d.order_rowid, tmp.prod_status, tmp.status_remark
			, ARRAY_TO_JSON(ARRAY(
			SELECT UNNEST(fnc_manu_weave_avai_status(tmp.prod_status))
			INTERSECT
			SELECT UNNEST(uac.arr_avail_status)
			)) AS arr_avail_status
			FROM v_order_report o
			INNER JOIN fnc_listmanuweave_accright_byuser($_userid) uac ON True
			INNER JOIN (
			SELECT 4 AS type_id, order_rowid, order_screen_rowid, position, detail, size, job_hist, price, seq
			FROM pm_t_order_premade_screen_tshirt
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
			$_master["weave_order"] = $_arrSc;
			// pass variable only no any error in every processes, otherwise just throw FALSE exit while loop and return FALSE
			$_arrReturn = $_master;
			$_whileChecker = FALSE;
			//-- screen
		}
		return $_arrReturn;
	}

	function get_detail_report($RowID) {
		$_arrReturn = FALSE;
		$_whileChecker = TRUE;
		while ($_whileChecker) {

			$_sql = <<<EOT
SELECT v.*
, m.file_image1, m.file_image2, m.file_image3, m.file_image4, m.file_image5, m.file_image6, m.file_image7, m.file_image8
, m.file_image9, m.remark1, m.remark2, m.create_by, m.create_date, m.update_by, m.update_date
, v.option_promotion AS promotion, qt.revision AS quotation_revision, m.supplier_rowid, v.supplier_name
, qt.qo_number as quotation_number
FROM pm_t_order_premade_tshirt m
	INNER JOIN v_order v ON v.type_id = 4 AND v.order_rowid = m.rowid
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
			$_arrDetails = array();
			//++ details template
			$_sql = <<<TMPL
SELECT s.rowid AS order_size_rowid, sc.rowid AS sub_cat_rowid, sc.name AS type, s.size_text AS size, s.size_chest AS chest
, CASE WHEN s.is_use > 0 THEN 0 ELSE 1 END AS is_expired
FROM (
	SELECT s.* 
	FROM pm_m_premade_order_size s
		LEFT OUTER JOIN (
			SELECT os.order_size_rowid, pdt.order_rowid, SUM(os.qty) as sum_qty
			FROM pm_t_order_premade_detail_tshirt pdt
				INNER JOIN pm_t_order_premade_size_tshirt os 
					ON os.order_detail_rowid = pdt.rowid 
			GROUP BY os.order_size_rowid, pdt.order_rowid
		) os
			ON os.order_size_rowid = s.rowid 
			AND os.order_rowid = {$RowID}
		WHERE s.is_tshirt > 0
		AND (s.is_use > 0 OR COALESCE(os.sum_qty, 0) > 0)
) s
	INNER JOIN pm_m_order_size_sub_cat sc ON s.sub_cat_rowid = sc.rowid
WHERE s.is_tshirt > 0
ORDER BY s.main_cat_rowid, sc.rowid, s.sort_index
TMPL;
			$_arr = $this->arr_execute($_sql);
			$_arrIndex = array();
			if ($_arr !== FALSE) {
				foreach ($_arr as $_row) {
					if (! array_key_exists($_row['sub_cat_rowid'], $_arrIndex)) {
						$_arrIndex[$_row['sub_cat_rowid']] = array("sub_category" => $_row["type"], "size" => array());
					}
					if (! array_key_exists($_row['order_size_rowid'], $_arrIndex[$_row['sub_cat_rowid']]["size"])) {
						$_arrIndex[$_row['sub_cat_rowid']]["size"][$_row['order_size_rowid']] = array(
							"rowid" => $_row['order_size_rowid']
							, "text" => $_row["size"]
							, "chest" => $_row["chest"]
							, "is_expired" => $_row["is_expired"]
						);
					}
				}
			}
			$_arrDetails['template'] = $_arrIndex;
			
			//++ details data
			$_sql = <<<EDT
SELECT _sq.pattern_rowid, p.code, _sq.color, _sq.price, _sq.arr_sizes AS size
FROM (
	SELECT sq.order_rowid, sq.pattern_rowid, sq.color, sq.price
	, JSON_AGG((SELECT ROW_TO_JSON(x) FROM (SELECT sq.order_size_rowid, sq.qty) AS x)) AS arr_sizes
	FROM (
		SELECT d.order_rowid, d.pattern_rowid, d.color, s.price, s.order_size_rowid, s.qty
		FROM pm_t_order_premade_size_tshirt s
			INNER JOIN pm_t_order_premade_detail_tshirt d ON d.rowid = s.order_detail_rowid
		WHERE d.order_rowid = $RowID
	) sq
	GROUP BY sq.order_rowid, sq.pattern_rowid, sq.color, sq.price
) _sq
	INNER JOIN pm_t_tshirt_pattern AS p ON _sq.pattern_rowid = p.rowid
EDT;
			$_arrDetails['data'] = $this->arr_execute($_sql);
			//-- details size
			//-- details
			$_master['detail'] = $_arrDetails;

			// ++ others price
			//t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
			$_sql = "SELECT t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price ";
			$_sql .= 'FROM pm_t_order_premade_price_tshirt t WHERE t.order_rowid = ' . $RowID . ' ORDER BY t.seq ';
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
FROM pm_t_order_premade_screen_tshirt t 
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
			
			$_master["detail"] = $_arrDetails;
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
		$this->db->update('pm_t_order_premade_detail_tshirt');
		
		$this->error_message = $this->db->error()['message'];
		return true;
	}

}