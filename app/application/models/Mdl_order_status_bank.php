<?php
class Mdl_order_status_bank extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_order_status';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'order_type_id' => '',
			'order_rowid' => '',
			'order_status_id' => '',
			'is_screen' => '',
			'screen_status_id' => '',
			'is_weave' => '',
			'weave_status_id' => '',
			'net_amount' => '',
			'vat' => '',
			'total_amount' => '',
			'deposit_payment' => '',
			'deposit_route_id' => '',
			'deposit_date' => '',
			'status_deliver_date' => '',
			'close_payment_route_id' => '',
			'close_payment_date' => '',
			'close_payment_amount' => '',
			'close_payment_wht' => '',
			'payment_status_id' => '',
			'deliver_remark' => '',
			'account_remark' => '',
			'status_update_by' => NULL,
			'status_update_date' => NULL,
			'deliver_by' => NULL,
			'deliver_date' => NULL,
			'account_by' => 0,
			'account_date' => NULL
		);
	}
	
	function search($arrObj = array()) {
		include ( APPPATH.'config/database.php' );
		$_sql = <<<QUERY
		SELECT  * FROM fnc_account_bank_statement() o
		WHERE COALESCE(o.is_cancel, 0) < 1  
QUERY;
		$_arrSpecWhere = array(
			'order_status' => array('dbcol'=>'t.order_status_id', 'type'=>'int'),
			'job_number' => array('dbcol'=>'t.job_number', 'type'=>'txt'),
			'customer_rowid' => array('dbcol'=>'t.customer_rowid'),
			'create_user_id' => array('dbcol'=>'uc.id', 'type'=>'int')
		);

		//search date 
		$_condition_date_field = 'o.payment_date';
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
		//search date 
		if (array_key_exists('payment_route', $arrObj)) {
			if ($arrObj['payment_route'] >= 0) {
				$_arrSpecWhere['payment_route'] = array(
					"type"=>"raw", 
					"dbcol"=>"o.payment_route", 
					"val"=> "'".$arrObj['payment_route']."'"
				);
			} else {
				unset($arrObj['deposit_route_id']);
			}
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, $_arrSpecWhere);

		$_sql .= "ORDER BY o.payment_date DESC ";	
		//$_sql .= "LIMIT 100";	
		// echo $_sql;exit;
		return $this->arr_execute($_sql);
	}

	function list_select_route() {
		$this->_TABLE_NAME = 'm_order_payment_route';
		$_sql = "SELECT name AS payment_route ";
		$_sql .= "FROM " . $this->_TABLE_NAME . " r ";
		$_sql .= "WHERE r.is_cancel = 0 ";
		// $_sql .= $this->_getCheckAccessRight("c.create_by", "customer");
		return $this->arr_execute($_sql);
	}
	
}