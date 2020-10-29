<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_report extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_sales_report';
	}

	public function index($branch_id = 20) { //default is polo otop
		$this->load->model('mdl_master_table', 'mt');
		$_strBranchName = $this->mt->get_branch_name($branch_id);

		$this->add_css(array(
			'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
			'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
			'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css'
		));
		$this->add_js(array(
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
/*
			'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
			'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
*/
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js',
			'public/js/jquery/flot/0.8.2/jquery.flot.js',
			'public/js/jquery/flot/0.8.2/jquery.flot.time.js',
			'public/js/jquery/flot/0.8.2/jquery.flot.selection.js', 
			'public/js/jquery/flot/0.8.2/jquery.flot.canvas.js',
			'public/js/jquery/flot/0.8.2/jquery.flot.legendoncanvas.js',
			'public/js/jquery/flot/0.8.2/jquery.flot.crosshair.min.js',
			'public/js/downloadify/downloadify.min.js', 
			'public/js/downloadify/swfobject.js',
			array('public/js/downloadify/excanvas.min.js', 'ie'),
			array('var _BRANCH_ID = ' . $branch_id . ';', 'custom')
		));

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel'
			, $this->_arrSearchParams($branch_id)
			, TRUE
		);
		$this->add_onload_js_file('public/js/sales_report/form.js');
		
		$pass['work_panel'] = $this->add_view('sales_report/display_panel', '', TRUE);
		
		$pass['title'] = "ยอดขายสาขา : " . $_strBranchName;
		$this->_DISABLE_ON_LOAD_SEARCH = TRUE;
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}

	function _arrSearchParams($branch_id) {
		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P1M'));
		$_arrLayout = array(
			array('date_from'),
			array('date_to'),
			"เซลส์" => array('return <div id="div_user_select" class="cls-user-select"></div>')
		);
		/*
		foreach ($this->_arrUsers as $_row) {
			$_id = 'chk-user_list-' . $_row['id'];
			array_push($_arrLayout["เซลส์"], array('return <input type="checkbox" class="search-param cls-toggle-label" id="' . $_id . '" checked name="user_list[]" value="' . $_row['id'] . '" /><label class="cls-toggle-label" for="' . $_id . '" >' . $_row['name'] . '</label>'));
		}
		*/
		return array(
			'controls' => array(
				array(
					"type" => "dpk"
					,"label" => "จากวันที่"
					,"name" => "date_from"
					,"value" => $_frm->format('d/m/Y')
				),
				array(
					"type" => "dpk"
					,"label" => "ถึงวันที่"
					,"name" => "date_to"
					,"value" => $_to->format('d/m/Y')
				)
			),
			'layout' => $_arrLayout,
			'search_onload' => FALSE
		);		
	}
	
	function json_list_available_sales() {
		$_arrReturn = array(
			'success' => FALSE
			,'error' => 'Unknown error'
		);
		$_arr = $this->__getAjaxPostParams();
		if (is_array($_arr) && isset($_arr['date_from'])) {
			$this->load->model('mdl_master_table', 'mt');

			$_branch_id = $_arr['branch_id'];
			$_date_from = $_arr['date_from'];
			$_date_to = (isset($_arr['date_to'])) ? $_arr['date_to'] : '';
			$_arrResult = $this->mt->list_available_joomla_users_sales_report($_branch_id, $_date_from, $_date_to);
			if (($this->mt->error_message == '')) {
				$_arrReturn['success'] = TRUE;
				if (! is_array($_arrResult)) $_arrResult = array();
				$_arrReturn['data'] = $_arrResult;
				unset($_arrReturn['error']);
			} else {
				$_arrReturn['error'] = $this->mt->error_message;
			}
		}
		$_json = json_encode($_arrReturn);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$_json.")":$_json;
	}

	function json_sales_report() {
		$_blnSuccess = FALSE;
		$_strError = 'Invalid user_list params. ( expecting array )';
		$_arrData = array();
		$_arr = $this->__getAjaxPostParams();
		if (($_arr !== FALSE) && (array_key_exists('user_list', $_arr))) {
			$this->load->model($this->modelName, 'm');
			$_arrQuery = $this->m->list_sales_report($_arr);
			$_strError = $this->m->error_message;
			if ($_strError == '') {
				$_blnSuccess = TRUE;
//print_r($_arrQuery);exit;
				if (is_array($_arrQuery)) {
					$_index = 1;
					$_arrCount = array();
					$_arrQty = array();
					$_arrAmount = array();
					$_arrTicks = array();
					foreach ($_arrQuery as $_row) {
						array_push($_arrTicks, array($_index, $_row['sales_name']));
						array_push($_arrCount, (float) $_row['count_order']);
						array_push($_arrQty, (float) $_row['sum_qty']);
						array_push($_arrAmount, (float) $_row['sum_amount']);					
						$_index++;
					}
					$_arrData = array(
						"ticks"=>$_arrTicks
						,"plot1"=> $_arrCount
						,"plot2"=> $_arrQty
						,"plot3"=> $_arrAmount
					);
				} 
			}
		}
		$json = json_encode(
			array(
				'success' => $_blnSuccess,
				'error' => $_strError,
				'data' => $_arrData
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")":$json;
	}

}