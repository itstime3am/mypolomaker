<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation_payment_log extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_quotation_payment_log';
	}
	
	function update_approval_status() {
		$_blnSuccess = FALSE;
		$_strError = "Unknown error";
		$_strMessage = "";
		
		$_arrData = $this->__getAjaxPostParams();
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$_arrData['approve_date'] = new DateTime();
			$_arrData['approve_by'] = $this->session->userdata('user_id');
			try {
				$this->load->model($this->modelName, 'm');
				$_strMessage = $this->m->commit($_arrData, FALSE);
				$_strError = $this->m->error_message;
			} catch (Exception $e) {
				$_strError = $e->getMessage();
			}
		}
		if (empty($_strError)) {
			$_blnSuccess = TRUE;
		} else {
			$_blnSuccess = FALSE;
		}
		$json = json_encode(
			array(
				'success' => $_blnSuccess,
				'error' => $_strError,
				'message' => $_strMessage
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")": $json;
	}
}