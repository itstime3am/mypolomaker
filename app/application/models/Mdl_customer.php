<?php

class Mdl_customer extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_customer';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'display_name' => '',
			'company' => '',
			'position' => '',
			'mobile' => '',
			'tel' => '',
			'fax' => '',
			'email' => '',
			'tax_id' => '',
			'tax_branch' => '',
			//'is_new_customer' => '',
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL
		);
	}
	function search($arrObj = array()) {
		include( APPPATH.'config'.DS.'database.php' );
		$_strJoomlaDB = $db['joomla']['database'];
		$_strJoomlaPF = $db['joomla']['dbprefix'];
		$_params = array();
		/* ++ issues 20170805 #2 change customer search criteria */
		$_sql = <<<QUERY
SELECT t.*, a.province_rowid, a.postal_code, a.address, a.province_name_th AS province
, COALESCE(uc.name, ' - ') AS create_user
, COALESCE((SELECT TO_CHAR(MAX(max_order_date), 'DD/MM/YYYY') FROM pm_v__list_last_order_date WHERE customer_rowid = t.rowid), '') AS disp_last_order_date 
, ub.branch_name AS disp_branch
FROM pm_t_customer t 
	LEFT OUTER JOIN {$_strJoomlaDB}.{$_strJoomlaPF}users uc ON COALESCE(t.update_by, t.create_by) = uc.id 
	LEFT OUTER JOIN v_customer_address a ON a.customer_rowid = t.rowid 
	LEFT OUTER JOIN v_joomla_user_min_branch ub ON ub.id = COALESCE(t.update_by, t.create_by)
WHERE true 

QUERY;

		$_arrSpecSearch = array(
				'display_name' => array("type"=>"txt"),
				'company' => array("type"=>"txt"),
				'province_rowid' => array("dbcol" => 'a.province_rowid'),
				'email' => array("type"=>"txt"),
				'create_user_id' => array('type'=>'int', 'dbcol'=>'uc.id')
				,'create_branch_id' => array('type'=>'int', 'dbcol'=>'ub.branch_id')
			);
		/* -- issues 20170805 #2 change customer search criteria */
				//'new_customer' => array("type"=>"int", "dbcol"=>"is_new_customer", "val"=>1),

		/* ++ from aac Ajax auto complete input element to search customer name along with company */
		if (array_key_exists('display_name_company', $arrObj) && (trim($arrObj['display_name_company']) != '')) {
			if ((trim($arrObj['display_name_company']) == 'คุณ')) {
				unset($arrObj['display_name_company']);
				return array();
			} else {
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
						"dbcol"=>"CONCAT(display_name, COALESCE(' [' || company || ']', ''))", 
						"operand"=>"LIKE", 
						"val"=>"CONCAT('%', '" . $this->db->escape_str(trim($arrObj['display_name_company'])) . "', '%')"
					);
				}
			}
		}
		/* -- from aac Ajax auto complete input element to search customer name along with company */
		if (array_key_exists('contact_no', $arrObj) && (trim($arrObj['contact_no']) != '')) {
			$_arrSpecSearch['contact_no'] = array(
				"type"=>"raw", 
				"dbcol"=>"CONCAT(t.mobile, t.tel, t.fax)", 
				"operand"=>"LIKE", 
				"val"=>"CONCAT('%', '" . $this->db->escape_str(trim($arrObj['contact_no'])) . "', '%')"
			);
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, $_arrSpecSearch);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "customer");
		$_sql .= "ORDER BY t.rowid DESC";

		return $this->arr_execute($_sql, $_params);
	}
	function list_select() {
		$_sql = "SELECT rowid, CONCAT(display_name, IF(COALESCE(company,'') = '', '', CONCAT(' [', company, ']')) ) AS display_name ";
		$_sql .= "FROM " . $this->_TABLE_NAME . " c ";
		$_sql .= $this->_getCheckAccessRight("c.create_by", "customer");
		return $this->arr_execute($_sql);
	}
	function list_select_company() {
		$_sql = "SELECT DISTINCT company ";
		$_sql .= "FROM " . $this->_TABLE_NAME . " c ";
		$_sql .= "WHERE c.company IS NOT NULL ";
		$_sql .= $this->_getCheckAccessRight("c.create_by", "customer");
		return $this->arr_execute($_sql);
	}
	
	function list_customer_with_address() {
		$_sql = <<<QUERY
SELECT c.rowid, c.display_name AS customer_name, 
CONCAT(c.display_name, ' [' || c.company || ']') AS display_name, 
COALESCE(c.company, '') AS company,
a.full_address AS display_address, 
CONCAT(c.mobile, ' ' || c.tel) AS tel 
FROM pm_t_customer c
	LEFT OUTER JOIN v_customer_address a ON c.rowid = a.customer_rowid 
QUERY;
		$_sql .= $this->_getCheckAccessRight("c.create_by", "customer");
		$_sql .= "ORDER BY c.display_name ";

		return $this->arr_execute($_sql);
	}
	function list_customer_job($customer_rowid) {
		$customer_rowid = $this->db->escape((int)trim($customer_rowid));
		$_sql = <<<SQL
SELECT t.order_type_id, t.order_rowid, t.category, t.type, t.job_number, t.raw_order_date, t.raw_due_date, t.raw_deliver_date
, TO_CHAR(t.raw_order_date, 'DD/MM/YYYY') AS order_date, TO_CHAR(t.raw_due_date, 'DD/MM/YYYY') AS due_date
, TO_CHAR(t.raw_deliver_date, 'DD/MM/YYYY') AS deliver_date
, t.customer_rowid, t.customer, t.company, t.create_by, t.sum_qty, t.total_price_sum, t.order_status, t.payment_status
FROM v_order_report_status t
WHERE t.customer_rowid = $customer_rowid 
SQL;
		$_sql .= $this->_getCheckAccessRight("t.create_by", "customer");
		$_sql .= "ORDER BY t.raw_order_date ";

		return $this->arr_execute($_sql);
	}
	function search_acc($arrObj = array()) {
		$_params = array();
		$_sql = <<<QUERY
SELECT t.rowid, CONCAT(t.display_name, COALESCE(' [' || t.company || ']', '')) AS display_name_company
, t.display_name, t.tel, t.mobile, t.company
, a.full_address AS address, a.arr_full_address
, COALESCE(t.mobile, t.tel, '') AS contact_no, COALESCE(t.tax_id, '') AS tax_id, COALESCE(t.tax_branch, '') AS tax_branch
FROM pm_t_customer t 
	LEFT OUTER JOIN v_customer_address a ON a.customer_rowid = t.rowid
WHERE TRUE

QUERY;
		$_arrSpecSearch = array(
				'display_name' => array("type"=>"txt"),
				'company' => array("type"=>"txt")
			);

		/* ++ from aac Ajax auto complete input element to search customer name along with company */
		if (array_key_exists('display_name_company', $arrObj) && (trim($arrObj['display_name_company']) != '')) {
			if ((trim($arrObj['display_name_company']) == 'คุณ')) {
				unset($arrObj['display_name_company']);
				return array();
			} else {
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
						"dbcol"=>"CONCAT(t.display_name, COALESCE(' [' || t.company || ']', ''))", 
						"operand"=>"LIKE", 
						"val"=>"CONCAT('%', '" . $this->db->escape_str(trim($arrObj['display_name_company'])) . "', '%')"
					);
				}
			}
		}
		/* -- from aac Ajax auto complete input element to search customer name along with company */
		$_sql .= $this->_getSearchConditionSQL($arrObj, $_arrSpecSearch);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "customer");
		$_sql .= "ORDER BY t.rowid DESC LIMIT 300 ";
//echo $_sql;exit;
		return $this->arr_execute($_sql, $_params);
	}
}


/*
CALL p_customer_last_order();
SELECT DISTINCT t.*, a.province_rowid, a.postal_code, a.address, COALESCE(p.name_th, ' - ') AS province, COALESCE(uc.name, ' - ') AS create_user, 
CASE t.is_new_customer 
	WHEN 1 THEN 'New' 
	ELSE ''
END AS disp_is_new_customer 
, CONCAT(display_name, IF(COALESCE(company,'') = '', '', CONCAT(' [', company, ']')) ) AS display_name_company
, COALESCE(lo.job_number, '') AS last_job_number 
, COALESCE(TO_CHAR(lo.order_date, 'DD/MM/YYYY'), '') AS disp_last_order_date 
FROM pm_t_customer t 
	LEFT OUTER JOIN (
		SELECT a1.* 
		FROM pm_t_customer_address a1 
			INNER JOIN (
				SELECT MAX(rowid) AS max_rowid, customer_rowid 
				FROM pm_t_customer_address 
				GROUP BY customer_rowid 
			) a2 ON a1.rowid = a2.max_rowid 
	) a ON t.rowid = a.customer_rowid 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON t.create_by = uc.id 
	LEFT OUTER JOIN pm_m_province p ON a.province_rowid = p.rowid 
	LEFT OUTER JOIN tmp_customer_last_order lo ON t.rowid = lo.customer_rowid
WHERE 0=0 
*/

// 20170908
/*
SELECT t.*, a.province_rowid, a.postal_code, a.address, COALESCE(p.name_th, ' - ') AS province
, COALESCE(uc.name, ' - ') AS create_user
, COALESCE((SELECT TO_CHAR(order_date, 'DD/MM/YYYY') FROM pm_v__filter_last_order_each_type WHERE customer_rowid = t.rowid ORDER BY order_date DESC, create_date DESC LIMIT 1), '') AS disp_last_order_date 
FROM pm_t_customer t 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON COALESCE(t.update_by, t.create_by) = uc.id 
	LEFT OUTER JOIN (
		SELECT a1.* 
		FROM pm_t_customer_address a1 
			INNER JOIN (
				SELECT MAX(rowid) AS max_rowid, customer_rowid 
				FROM pm_t_customer_address 
				GROUP BY customer_rowid 
			) a2 ON a1.rowid = a2.max_rowid 
	) a ON t.rowid = a.customer_rowid 
	LEFT OUTER JOIN pm_m_province p ON a.province_rowid = p.rowid 
WHERE true 
$_strAddCond
*/

/*
SELECT DISTINCT t.*, a.province_rowid, a.postal_code, a.address, COALESCE(p.name_th, ' - ') AS province
, COALESCE(uc.name, ' - ') AS create_user
, CASE t.is_new_customer 
	WHEN 1 THEN 'New' 
	ELSE ''
END AS disp_is_new_customer 
, CONCAT(display_name, IF(COALESCE(company,'') = '', '', CONCAT(' [', company, ']')) ) AS display_name_company
, COALESCE(fo.job_number, '') AS last_job_number
, COALESCE(TO_CHAR(fo.order_date, 'DD/MM/YYYY'), '') AS disp_last_order_date
FROM pm_t_customer t 
	LEFT OUTER JOIN (
		SELECT a1.* 
		FROM pm_t_customer_address a1 
			INNER JOIN (
				SELECT MAX(rowid) AS max_rowid, customer_rowid 
				FROM pm_t_customer_address 
				GROUP BY customer_rowid 
			) a2 ON a1.rowid = a2.max_rowid 
	) a ON t.rowid = a.customer_rowid 
	LEFT OUTER JOIN (
		SELECT t.*
		FROM pm_v__filter_last_order_each_type t
			INNER JOIN (
				SELECT customer_rowid, MAX(create_date) AS max_create_date
				FROM pm_v__filter_last_order_each_type
				GROUP BY customer_rowid
			) mt ON mt.customer_rowid = t.customer_rowid
				AND mt.max_create_date = t.create_date
	) fo ON fo.customer_rowid = t.rowid
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON t.create_by = uc.id 
	LEFT OUTER JOIN pm_m_province p ON a.province_rowid = p.rowid 
WHERE true 
$_strAddCond
*/

/*
SELECT DISTINCT t.*, a.province_rowid, a.postal_code, a.address, COALESCE(p.name_th, ' - ') AS province, COALESCE(uc.name, ' - ') AS create_user, 
CASE t.is_new_customer 
	WHEN 1 THEN 'New' 
	ELSE ''
END AS disp_is_new_customer 
, CONCAT(display_name, IF(COALESCE(company,'') = '', '', CONCAT(' [', company, ']')) ) AS display_name_company
, COALESCE((SELECT job_number FROM pm_v__filter_last_order_each_type WHERE customer_rowid = t.rowid ORDER BY order_date DESC, create_date DESC LIMIT 1), '') AS last_job_number 
, COALESCE((SELECT TO_CHAR(order_date, 'DD/MM/YYYY') FROM pm_v__filter_last_order_each_type WHERE customer_rowid = t.rowid ORDER BY order_date DESC, create_date DESC LIMIT 1), '') AS disp_last_order_date 
FROM pm_t_customer t 
	LEFT OUTER JOIN (
		SELECT a1.* 
		FROM pm_t_customer_address a1 
			INNER JOIN (
				SELECT MAX(rowid) AS max_rowid, customer_rowid 
				FROM pm_t_customer_address 
				GROUP BY customer_rowid 
			) a2 ON a1.rowid = a2.max_rowid 
	) a ON t.rowid = a.customer_rowid 
	LEFT OUTER JOIN {$db['joomla']['database']}.{$db['joomla']['dbprefix']}users uc ON t.create_by = uc.id 
	LEFT OUTER JOIN pm_m_province p ON a.province_rowid = p.rowid 
WHERE 0=0 
*/
