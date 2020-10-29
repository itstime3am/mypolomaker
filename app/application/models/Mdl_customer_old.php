<?php
class Mdl_customer_old extends MY_Model {
	function __construct()
	{
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
			'address' => '',
			'province_rowid' => 0,
			'mobile' => '',
			'tel' => '',
			'fax' => '',
			'email' => '',
			'position' => '',
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL
		);
	}
	function search($name = '', $company = '', $province_id = 0, $contact_no = '', $email = '') {
		if (! is_integer((int) $province_id)) {
			$province_id = 0;
		}
		
		$_sql = "SELECT c.*, COALESCE(p.name_th, ' - ') AS province FROM " . $this->_TABLE_NAME . " c LEFT OUTER JOIN pm_m_province p ON c.province_rowid = p.rowid WHERE True ";
		if ($name != '') $_sql .= "AND display_name LIKE '%" . $name . "%' ";
		if ($company != '') $_sql .= "AND company LIKE '%" . $company . "%' ";
		if ($email != '') $_sql .= "AND email LIKE '%" . $email . "%' ";
		if ($contact_no != '') $_sql .= "AND (mobile LIKE '%" . $contact_no . "%' OR tel LIKE '%" . $contact_no . "%' OR fax LIKE '%" . $contact_no . "%') "; 
		if ($province_id != 0) $_sql .= "AND province_rowid = " . $province_id;
		//echo $_sql;exit;
		return $this->arr_execute($_sql);
	}
	
	function commit_customer($arrObj) {
		foreach ($arrObj as $key=>$value) {
			//echo strtolower($key) . ', ';
			if (array_key_exists($key, $this->_FIELDS)) {
				$this->_FIELDS[$key] = $value;
			}
		}
		if (!(array_key_exists('rowid', $arrObj))) {
			$this->_FIELDS['rowid'] = null;
			return $this->insert();
		} else {
			return $this->update(array('rowid' => $arrObj['rowid']));
		}
	}

	function _strConvertDisplayDateFormat($dtDate = null) 
	{
		if ($dtDate == null) {
			//$dtDate = new Date();
			return '';
		} else {
			//Server Thai Locale
			//return ($dtDate->format('Y') + 543) . '/' . $dtDate->format('m/d');
			return $dtDate->format('Y/m/d');
		}
	}
}