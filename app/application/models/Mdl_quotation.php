<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_quotation extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_quotation';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			//'qo_number' => ''
			'customer_rowid' => NULL
			,'branch_jug_id' => NULL
			,'start_date' => NULL
			,'day_limit' => NULL
			,'is_vat' => NULL
			,'percent_discount' => NULL
			,'payment_condition_rowid' => NULL
			,'days_credit' => NULL
			,'deposit_percent' => NULL
			,'deposit_amount' => NULL
			,'sale_rowid' => NULL
			,'is_disp_notice' => NULL
			,'remark' => NULL
			,'status_rowid' => NULL
			,'create_by' => NULL
			,'create_date' => NULL
			,'update_by' => NULL
			,'update_date' => NULL
			,'revision' => NULL
			,'status_remark' => NULL
			,'promotion' => NULL
			//,'is_deleted' => 0
		);
		//$this->_DATETIME_FIELDS = array('deposit_datetime');
	}
/*
TO_CHAR(fnc__getAmount(is_vat, COALESCE(d.sum_amount, 0)), 'FM9G999G999G999D00') AS disp_sum_net,
TO_CHAR(fnc__getAmount(is_vat, COALESCE(d.sum_amount, 0) * (COALESCE(percent_discount, 0) / -100)), 'FM9G999G999G999D00') AS disp_sum_discount,
TO_CHAR(fnc__getVAT(is_vat, (COALESCE(d.sum_amount, 0) * ((100 - COALESCE(percent_discount, 0)) / 100))), 'FM9G999G999G999D00') AS disp_sum_vat,
TO_CHAR(fnc__getAmount(is_vat, (COALESCE(d.sum_amount, 0) * ((100 - COALESCE(percent_discount, 0)) / 100))), 'FM9G999G999G999D00') AS disp_sum_amount,
*/

	function search($arrObj = array()) {
		include( APPPATH.'config/database.php' );
		$_sql = <<<QUERY
SELECT t.*
, COALESCE(ug.title, '') AS branch, COALESCE(uc.name, ' - ') AS create_user
, ARRAY_TO_JSON(ARRAY(
	SELECT UNNEST(fnc_quotation_avai_status(t.produce_status_rowid, t.sum_amount, t.deposit_amount, t.grand_total)) 
	INTERSECT 
	SELECT UNNEST(uac.arr_avail_status)
)) AS arr_avail_status
, ARRAY_TO_JSON(ARRAY(
	-- SELECT UNNEST(fnc_quotation_avai_action(GREATEST(t.deliver_status_rowid, t.produce_status_rowid))) 
	SELECT UNNEST(fnc_quotation_avai_action(t.produce_status_rowid)) 
	INTERSECT 
	SELECT UNNEST(uac.arr_avail_action)
)) AS arr_avail_action, qd.arr_details
, uac.arr_avail_action AS user_acr_action, t.grand_total - t.deposit_payment AS disp_left_amount
--, COALESCE(dlv.delivery_rowid, -1) AS link_delivery_rowid
FROM v_quotation t 
	INNER JOIN fnc_listQuation_AccRight_byUser(?) uac ON True 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users  uc ON t.sale_rowid = uc.id 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}usergroups ug ON t.branch_jug_id = ug.id 
	LEFT OUTER JOIN (
		SELECT dtl.quotation_rowid
		, JSON_AGG((
			SELECT ROW_TO_JSON(_row) 
			FROM (
				SELECT dtl."name" AS disp_type, dtl.rowid AS qod_rowid, dtl.description, dtl.qty, dtl.amount
			) _row
		)) AS arr_details
		FROM (
			SELECT d.rowid, d.rowid AS qod_rowid, d.quotation_rowid, d.description, d.qty, d.amount, dt."name"
			FROM pm_t_quotation_detail d
				INNER JOIN pm_m_quotation_detail_title dt ON dt.rowid = d.title_rowid 
			WHERE dt.order_type_id > 0
			AND COALESCE(d.is_deleted, 0) < 1
		) dtl
		GROUP BY dtl.quotation_rowid
	) qd ON qd.quotation_rowid = t.rowid
/*	LEFT OUTER JOIN (
		SELECT quotation_rowid, MIN(rowid) AS delivery_rowid 
		FROM pm_t_delivery 
		WHERE COALESCE(is_deleted, 0) < 1
		GROUP BY quotation_rowid 
	) dlv ON dlv.quotation_rowid = t.rowid
*/
WHERE COALESCE(t.is_deleted, 0) < 1

QUERY;
/*
(COALESCE(d.sum_amount, 0) * ((100 - COALESCE(percent_discount, 0)) / 100) * (CASE COALESCE(is_vat, 0) WHEN 0 THEN 0 ELSE 0.07 END)) AS sum_vat,
(COALESCE(d.sum_amount, 0) * ((100 - COALESCE(percent_discount, 0)) / 100)) * (CASE COALESCE(is_vat, 0) WHEN 0 THEN 1 ELSE 1.07 END) AS sum_amount, 

COALESCE(uu.name, ' - ') AS update_user, 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uu ON c.update_by = uu.id 
*/
		$_params = array($this->db->escape((int)$this->session->userdata('user_id')));
		if (isset($arrObj['is_active_status']) && ($arrObj['is_active_status'])) {
			$_sql .= "\nAND (COALESCE(t.status_rowid, 10) < 170)\n";
			unset($arrObj['is_active_status']);
		}

		$_arrSpecSearch = array(
				'qo_number' => array("type"=>"txt")
				, 'display_name_company' => array("type"=>"txt", "dbcol" => 't.display_name_company')
				, 'display_name' => array("type"=>"txt", "dbcol" => 't.display_name')
				, 'company' => array("type"=>"txt", "dbcol" => 't.customer_company')
				, 'branch_id' => array('type'=>'int', 'dbcol'=>'ug.id')
				, 'date_from' => array('type'=>'dat', 'dbcol'=>'t.start_date', 'operand'=>'>=')
				, 'date_to' => array('type'=>'dat', 'dbcol'=>'t.start_date', 'operand'=>'<=')
				, 'create_user_id' => array('type'=>'int', 'dbcol'=>'uc.id')
			);

		/* ++ from aac Ajax auto complete input element to search customer name along with company */
/*
		if (array_key_exists('display_name_company', $arrObj) && (trim($arrObj['display_name_company']) != '')) {
			$_name_company = $arrObj['display_name_company'];
			if (strpos($_name_company, " [") != false) {
				unset($arrObj['display_name_company']);
				$_arr = explode('[', $_name_company);
				if (count($_arr) > 1) {
					$arrObj['display_name'] = $this->db->escape_str(trim($_arr[0]));
					$arrObj['company'] = $this->db->escape_str(trim($_arr[1]));
				}
			} else {
				$_arrSpecSearch['display_name_company'] = array(
					"type"=>"raw", 
					"dbcol"=>"CONCAT(display_name, COALESCE('[' || company || ']', ''))", 
					"operand"=>"LIKE", 
					"val"=>"CONCAT('%', '" . $this->db->escape_str(trim($arrObj['display_name_company'])) . "', '%')"
				);
			}
		}
*/
		/* -- from aac Ajax auto complete input element to search customer name along with company */
		$_sql .= $this->_getSearchConditionSQL($arrObj, $_arrSpecSearch);
		$_sql .= $this->_getCheckAccessRight__jug_id("t.sale_rowid", "t.branch_jug_id", "quotation");
		$_sql .= "ORDER BY t.rowid DESC";
//echo $_sql;exit;
		return $this->arr_execute($_sql, $_params);
	}
	
	function get_detail_report($rowid) {
		include( APPPATH.'config/database.php' );
		$_rowid = $this->db->escape((int) $rowid);
		$_return = array();
		$_sql = <<<QRY
SELECT t.*
, REGEXP_REPLACE(t.disp_header_address, '\n', '<br>') AS disp_pdf_header_address
, REGEXP_REPLACE(t.disp_footer_bank_account, '\n', '<br>') AS disp_pdf_bank_account
, TO_CHAR(t.sum_amount, '9G999G990D00') AS disp_sum_net
, TO_CHAR(t.sum_discount, '9G999G990D00') AS disp_sum_discount
, TO_CHAR(t.sum_after_discount, '9G999G990D00') AS disp_sum_after_discount
, TO_CHAR(t.sum_vat, '9G999G990D00') AS disp_sum_vat
, t.grand_total AS sum_amount, TO_CHAR(t.grand_total, '9G999G990D00') AS disp_sum_amount
, t.qo_number as quotation_number
, COALESCE(ug.title, '') AS branch, COALESCE(uc.name, ' - ') AS create_user
FROM v_quotation t 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON t.sale_rowid = uc.id 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}usergroups ug ON t.branch_jug_id = ug.id 
WHERE t.rowid = $_rowid	
AND COALESCE(t.is_deleted, 0) < 1

QRY;
		$_sql .= $this->_getCheckAccessRight__jug_id("t.sale_rowid", "t.branch_jug_id", "quotation");
		
		$_arr = $this->arr_execute($_sql);
		if (is_array($_arr) && (count($_arr) > 0)) {
			$_return = $_arr[0];
		}
		
		$_sql = <<<QRY
SELECT tb.seq::INT AS seq, COALESCE(((tb._js_premade_dtl)->>'indx')::INT, ((tb._jsDtl)->>'indx')::INT, 0)::INT AS sub_seq
, 0 AS rownum, tb.rowid AS qod_rowid, tb.description, NULL AS qty, tb.remark
, '' AS disp_price -- TO_CHAR(COALESCE(tb.price, 0), '9G999G990D00')
, '' AS disp_amount -- TO_CHAR(COALESCE(tb.amount, 0), '9G999G990D00')
, CONCAT(
	'<span style="font-weight:bold;text-decoration:underline;">รายละเอียด', tb.title_code, '</span>'		
	, CASE 
		WHEN tb.order_type_id IN (1, 2) THEN CONCAT('<br>'
			, NULLIF(((tb._jsDtl)->>'fabric_name')::TEXT, '')
			, ', ' || NULLIF(((tb._jsDtl)->>'main_color')::TEXT, '')
			, ' ' || NULLIF(((tb._jsDtl)->>'line_color')::TEXT, '')
			, ', ', NULLIF(((tb._jsDtl)->>'standard_pattern_name')::TEXT, '') || ' '
			, NULLIF(((tb._jsDtl)->>'sub_color1')::TEXT, '')
			, ' ' || NULLIF(((tb._jsDtl)->>'sub_color2')::TEXT, '')
			, ' ' || NULLIF(((tb._jsDtl)->>'sub_color3')::TEXT, '')
			, ' ' || NULLIF(((tb._jsDtl)->>'color_detail')::TEXT, '')
			, ', ' || NULLIF(CONCAT_WS(' ', ((tb._jsDtl)->>'collar_name')::TEXT
			, ((tb._jsDtl)->>'collar_detail')::TEXT
			, ((tb._jsDtl)->>'collar_detail2')::TEXT), '')
			, ', ชาย-' || NULLIF(((tb._jsDtl)->>'m_clasper_name')::TEXT, '')
			, ' หญิง-' || NULLIF(((tb._jsDtl)->>'f_clasper_name')::TEXT, '')
			, ' แบบกระดุม-' || NULLIF(((tb._jsDtl)->>'clasper_pattern_name')::TEXT, '')
			, ' ' || NULLIF(CONCAT_WS(' ', ((tb._jsDtl)->>'clasper_detail')::TEXT
			, ((tb._jsDtl)->>'clasper_detail2')::TEXT), '')
			, ', ชาย-' || NULLIF(((tb._jsDtl)->>'m_sleeves_name')::TEXT, '')
			, ' หญิง-' || NULLIF(((tb._jsDtl)->>'f_sleeves_name')::TEXT, '')
			, ' ' || NULLIF(((tb._jsDtl)->>'sleeves_detail')::TEXT, '')
			, ', ' || NULLIF(CONCAT_WS(' ', ((tb._jsDtl)->>'pocket_type_name')::TEXT, ((tb._jsDtl)->>'pocket_type_detail')::TEXT), '')
			, ', ' || NULLIF(CONCAT_WS(' ', ((tb._jsDtl)->>'flap_side_ptrn_name')::TEXT, ((tb._jsDtl)->>'flap_side_ptrn_detail')::TEXT), '')
		)
		WHEN tb.order_type_id IN (3, 4, 7, 8, 12, 14) THEN CONCAT(': '
			, NULLIF(((tb._js_premade_dtl)->>'pattern_name')::TEXT, '')
			, ' ' || NULLIF(((tb._js_premade_dtl)->>'main_color')::TEXT, '')
		)
		WHEN tb.order_type_id IN (5, 6, 9, 10, 11, 15) THEN CONCAT('<br>'
			, NULLIF(tb.title_code, '')
			, ' ชนิดผ้า ' || NULLIF(((tb._jsDtl)->>'fabric_type')::TEXT, '')
			, ' สีผ้าหลัก ' || NULLIF(((tb._jsDtl)->>'main_color')::TEXT, '')
			, ' สีผ้าตัดต่อ' || NULLIF(((tb._jsDtl)->>'sub_color1')::TEXT, '')
			, ' สีเพิ่มเติม ' || NULLIF(((tb._jsDtl)->>'color_detail')::TEXT, '')
			, ' ทรง ', NULLIF(((tb._jsDtl)->>'pattern')::TEXT, '') || ' '
			, ' ลักษณะพิเศษ ', NULLIF(((tb._jsDtl)->>'detail1')::TEXT, '') || ' ' -- , ', ', NULLIF(((tb._jsDtl)->>'detail2')::TEXT, '') || ' '
		)
		ELSE tb.description
	END
	, '<br><span style="font-weight:bold;text-decoration:underline;">ไซส์</span><br>'
) AS title
FROM (
	SELECT _tb.*
	, JSON_ARRAY_ELEMENTS(COALESCE(JSON(_tb._jsDtl->>'js_premade_detail'), '[{}]'::JSON)) AS _js_premade_dtl
	FROM (
		SELECT t.seq, t.rowid, t.title_rowid, COALESCE(dt.name, ' - ') AS title_code, COALESCE(t.description, '') AS description, t.qty
		, COALESCE(t.remark, '') AS remark, COALESCE(t.price, CASE WHEN t.qty > 0 THEN t.amount / t.qty END, 0) AS price
		, COALESCE(t.amount, (t.price * t.qty), 0) AS amount, dt.order_type_id
		, fnc_v_quotation_detail_get_order_detail(t.rowid) AS _jsDtl
		FROM pm_t_quotation_detail t 
			LEFT OUTER JOIN pm_m_quotation_detail_title dt ON dt.rowid = t.title_rowid
		WHERE t.quotation_rowid = ?
		AND COALESCE(t.is_deleted, 0) < 1
		ORDER BY t.seq, t.rowid
	) _tb
) tb

UNION ALL 

SELECT t.seq::INT AS seq, COALESCE(s.indx, 0)::INT AS sub_seq, sz.sort_index AS rownum
, t.rowid AS qod_rowid, t.description, s.qty, t.remark, TO_CHAR(COALESCE(s.price, 0), '9G999G990D00') AS disp_price
, TO_CHAR(COALESCE(s.price * s.qty, 0), '9G999G990D00') AS disp_amount
, CONCAT('&nbsp;-&nbsp;', COALESCE(NULLIF(CONCAT_WS(': ', c.name, s.disp_type), ''), 'Custom Size'), ': ' || CONCAT(UPPER(s.size_text), ' ( ' || s.size_chest || '" )')) AS title
FROM pm_t_quotation_detail t, fnc_get_order_sizequan_from_qod(t.rowid) s
	LEFT OUTER JOIN pm_m_order_size_cat c ON c.rowid = s.main_cat_rowid
	LEFT OUTER JOIN pm_m_order_size sz ON sz.rowid = s.order_size_rowid
WHERE t.quotation_rowid = ?
AND COALESCE(t.is_deleted, 0) < 1

UNION ALL

SELECT 100 AS seq, t.seq::INT AS sub_seq, t.sub_seq::INT AS rownum, t.rowid AS qod_rowid
, NULL::TEXT AS description, t.qty AS qty, NULL::TEXT AS remark
, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS disp_price
, TO_CHAR(COALESCE(t.price * t.qty, 0), '9G999G990D00') AS disp_amount
, CONCAT_WS(' ', m.name, t.position, t.detail, t.size, t.job_hist) AS title
FROM (
	SELECT s.rowid, s.seq, s.qty, ((s._jsSC)->>'order_screen_rowid')::INT AS order_screen_rowid, ((s._jsSC)->>'position')::TEXT AS position
	, ((s._jsSC)->>'detail')::TEXT AS detail, ((s._jsSC)->>'size')::TEXT AS size, ((s._jsSC)->>'job_hist')::TEXT AS job_hist
	, ((s._jsSC)->>'price')::NUMERIC(14, 5) AS price, ((s._jsSC)->>'seq')::INT AS sub_seq
	FROM (
		SELECT rowid, seq, qty, JSON_ARRAY_ELEMENTS((json_others->>'screen')::JSON)::JSON AS _jsSC
		FROM pm_t_quotation_detail
		WHERE quotation_rowid = ?
		AND COALESCE(is_deleted, 0) < 1
	) s
) t
	LEFT OUTER JOIN pm_m_order_screen m ON t.order_screen_rowid = m.rowid 

UNION ALL

SELECT 300 AS seq, t.seq::INT AS sub_seq, t.sub_seq::INT AS rownum, t.rowid AS qod_rowid
, NULL::TEXT AS description, t.qty AS qty, NULL::TEXT AS remark, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS disp_price
, TO_CHAR(COALESCE(t.price * t.qty, 0), '9G999G990D00') AS disp_amount, t.detail AS title
FROM (
	SELECT s.rowid, s.seq, 1 AS qty, ((s._jsOP)->>'detail')::TEXT AS detail
	, ((s._jsOP)->>'price')::NUMERIC(14, 5) AS price, ((s._jsOP)->>'seq')::INT AS sub_seq
	FROM (
		SELECT rowid, seq, qty, JSON_ARRAY_ELEMENTS((json_others->>'others_price')::JSON)::JSON AS _jsOP 
		FROM pm_t_quotation_detail 
		WHERE quotation_rowid = ? 
		AND COALESCE(is_deleted, 0) < 1
	) s
) t

UNION ALL

SELECT 500 AS seq, t.seq::INT AS sub_seq, 0 AS rownum, t.rowid AS qod_rowid
, NULL::TEXT AS description, NULL AS qty, NULL::TEXT AS remark
, '' AS disp_price
, '' AS disp_amount
, CONCAT_WS('<br>'
		, '<span style="font-weight:bold;text-decoration:underline;">หมายเหตุ:</span>&nbsp;&nbsp;' || t.description
		, '<hr style="width:60%;">'
) AS title
FROM pm_t_quotation_detail t 
WHERE t.quotation_rowid = ?
AND COALESCE(t.is_deleted, 0) < 1

ORDER BY 4, 1, 2, 3 ASC;

QRY;
		//$_sql .= $this->_getCheckAccessRight__jug_id("t.create_by", $_return['branch_jug_id'], "quotation");

		$_arr = $this->arr_execute($_sql, array($_rowid, $_rowid, $_rowid, $_rowid, $_rowid));
		if (is_array($_arr) && (count($_arr) > 0)) {
			$_return['details'] = $_arr;
		} else {
			$_return['details'] = array();
		}

		return $_return;
	}
	
	function getNextQONumber($str_startdate = '') {
		$_thyear = '';
		$_year = '';
		if (strlen(trim($str_startdate)) >= 4) {
			$_year = substr(trim($str_startdate), 0, 4);
		}
		if ( ! is_numeric($_year)) $_year = date('Y');
		if ($_year < 2500) {
			$_thyear = $_year + 543;
		} else {
			$_thyear = $_year;
			$_year = $_year - 543;
		}
		$_ThaiYear = substr(sprintf('%04d', $_thyear), -2);
		$_QueryYear = sprintf('%04d', $_year);

		$_sql = <<<QRY
SELECT CONCAT('QT{$_ThaiYear}', TO_CHAR(COALESCE(COUNT(SUBSTR(qo_number, 5, 5)::INT), 0) + 1, 'FM00000')) AS qo_number
FROM pm_t_quotation 
WHERE TO_CHAR(start_date, 'YYYY') = '$_QueryYear';
QRY;

		$_arr = $this->arr_execute($_sql);
		if (is_array($_arr) && (count($_arr) > 0)) {
			return $_arr[0]['qo_number'];			
		}
		return '-ERROR_QO_NUMBER-';
	}

	function change_status_by_id($rowid, $status_rowid, $status_remark = FALSE) {
		$_rowid = $this->db->escape((int) $rowid);
		$_status_id = $this->db->escape((int) $status_rowid);
		
		if ($status_remark) $this->db->set('status_remark', $status_remark);
		$this->db->set('status_rowid', $_status_id);
		$this->db->set('update_by', $this->db->escape((int)$this->session->userdata('user_id')));
		$this->db->where('rowid', $_rowid);
		$this->db->update($this->_TABLE_NAME);
		
		$this->error_message = $this->db->error()['message'];
		return true;
	}

	function change_status_by_code($arrObj) {
		if (! isset($arrObj['rowid'])) $this->error_message .= ' "rowid" not found,';
		if (! isset($arrObj['status_text'])) $this->error_message .= ' "status_text" not found,';
		if (strlen($this->error_message) > 0) return false;
		
		$_rowid = $this->db->escape((int) $arrObj['rowid']);
		$_status_text = $this->db->escape_str($arrObj['status_text']);
		
		return $this->arr_execute('SELECT fnc__quotationUpdateStatusByCode(?, ?, ?);', array($_rowid, $_status_text, $this->db->escape((int)$this->session->userdata('user_id'))));
	}
	
	function insert_delivery_order($arrObj) {
		if (! isset($arrObj['rowid'])) $this->error_message .= ' "rowid" not found,';
		if (strlen($this->error_message) > 0) return false;
		
		$_rowid = $this->db->escape((int) $arrObj['rowid']);
		
		return $this->arr_execute('SELECT fnc__quotationInsertDeliveryOrder(?, ?);', array($_rowid, $this->db->escape((int)$this->session->userdata('user_id'))));
	}
}