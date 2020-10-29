<?php
class Mdl_customer_address extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_customer_address';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'customer_rowid' => '',
			'is_default' => '',	
			'address' => '',
			'province_rowid' => '',
			'postal_code' => '',
			'tel' => '',
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL
		);
	}
	function search($arrObj = array()) {
		if (! array_key_exists('customer_rowid', $arrObj)) return FALSE;
		$_sql = "SELECT a.*, COALESCE(p.name_th, ' -- ') AS province ";
		$_sql .= "FROM " . $this->_TABLE_NAME . " a ";
		$_sql .= "LEFT OUTER JOIN pm_m_province p ON a.province_rowid = p.rowid ";
		$_sql .= "WHERE customer_rowid = " . $arrObj["customer_rowid"] . " ";
		$_sql .= "ORDER BY a.rowid DESC";
		//echo $_sql;exit;
		return $this->arr_execute($_sql);
	}
	//override case search found no rows then first added row set as default
	/*function commit($arrObj) {
		foreach ($arrObj as $key=>$value) {
			if (array_key_exists($key, $this->_FIELDS)) {
				$this->_FIELDS[$key] = $value;
			}
		}
		$this->_FIELDS['customer_rowid'] = $this->CUSTOMER_ROWID;

		$_blnIsInsert = FALSE;
		if (!(array_key_exists('rowid', $arrObj))) {
			$_blnIsInsert = TRUE;
		} else {
			if (trim($arrObj['rowid']) == '') {
				$_blnIsInsert = TRUE;
			}
		}
		if ($_blnIsInsert) {
			$this->_FIELDS['rowid'] = null;
			if ($this->FIRST_ROW === TRUE) {
				$this->_FIELDS['is_default'] = 1;
			}
			return $this->insert();
		} else {
			return $this->update(array('rowid' => $arrObj['rowid']));
		}
	}*/
}