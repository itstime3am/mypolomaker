<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_account_bank_state extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_order_status_bank';
	}

	public function index($customer_rowid = -1) {
		$this->load->model('Mdl_customer', 'c');
		$_strStartScript = '';
		if ($customer_rowid > 0) {
			$_arr = $this->c->get_by_id($customer_rowid);
			if (is_array($_arr) && isset($_arr['display_name'])) {
				$_strStartScript = "$(function() { _doCreateNew(" . $customer_rowid . ", '" . $_arr['display_name'] . "');});\n";
			}
		}
		$this->add_css(array(
			/*
			'public/css/jquery/ui/1.10.4/dark-hive/jquery-ui.css',
			*/
			'public/css/jquery/ui/1.11.4/cupertino/jquery-ui.min.css',
			//'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css',
			/*
			'public/css/jquery/dataTable/1.10.11/jquery.dataTables.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.dataTables.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.bootstrap.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.bootstrap.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.foundation.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.foundation.min.css',
			*/
			'public/css/jquery/dataTable/1.10.11/dataTables.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/colreorder-1.3.1/colReorder.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedcolumns-3.2.1/fixedColumns.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedheader-3.1.1/fixedHeader.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/scroller-1.4.1/scroller.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/select-1.1.2/select.jqueryui.min.css',
			'public/css/jquery/ui/timepicker/1.6.1/jquery-ui-timepicker-addon.min.css',
			'public/css/jquery/fileupload/fileupload.css',
			'public/css/order/form.css',
			'public/css/order/_detail_premade.css',
			'public/css/quotation/form.css'
		));
		$this->add_js(array(
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/jquery/dataTable/1.10.11/jquery.dataTables.min.js',
			/*
			'public/js/jquery/dataTable/1.10.11/dataTables.bootstrap.min.js',
			'public/js/jquery/dataTable/1.10.11/dataTables.foundation.min.js',
			*/
			'public/js/jquery/dataTable/1.10.11/dataTables.jqueryui.min.js',
			/*
			'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
			'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.js',
			*/
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/dataTables.buttons.min.js',
			/*
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.bootstrap.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.foundation.min.js',
			*/
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/jszip-2.5.0/jszip.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/pdfmake.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/vfs_fonts.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.html5.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.print.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.colVis.min.js',
			'public/js/jquery/dataTable/extensions/colreorder-1.3.1/dataTables.colReorder.min.js',
			'public/js/jquery/dataTable/extensions/fixedcolumns-3.2.1/dataTables.fixedColumns.min.js',
			'public/js/jquery/dataTable/extensions/fixedheader-3.1.1/dataTables.fixedHeader.min.js',
			'public/js/jquery/dataTable/extensions/responsive-2.0.2/dataTables.responsive.min.js',
			'public/js/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/scroller-1.4.1/dataTables.scroller.min.js',
			'public/js/jquery/dataTable/extensions/select-1.1.2/dataTables.select.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/moment_2.8.4.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/datetime-moment.js',
			'public/js/jquery/dataTable/extensions/type-detection/numeric-comma.js',
			'public/js/jquery/ui/timepicker/1.6.1/jquery-ui-timepicker-addon.min.js',
			'public/js/jquery/ui/timepicker/1.6.1/jquery-ui-sliderAccess.js',
			'public/js/jquery/fileupload/load-image.min.js',
			'public/js/jquery/fileupload/canvas-to-blob.min.js',
			'public/js/jquery/fileupload/jquery.iframe-transport.js',
			'public/js/jquery/fileupload/jquery.fileupload.js',
			'public/js/jquery/fileupload/jquery.fileupload-process.js',
			'public/js/jquery/fileupload/jquery.fileupload-image.js',
			'public/js/jquery/fileupload/jquery.form.js',
			'public/js/_public/_fmg_controller.js'
			, 'public/js/jsGlobal.js'
			, 'public/js/jsUtilities.js'
			, 'public/js/jsGlobalConstants.js'
			, array(<<<SCRPT
		$.fn.dataTable.moment( 'YYYY/MM/DD', moment.locale('en') );
		$.fn.dataTable.moment( 'DD/MM/YYYY', moment.locale('en') );
		$.fn.dataTable.moment( 'DD MM YYYY', moment.locale('en') );

SCRPT
			, 'custom')
		));
		
		$this->load->model('mdl_master_table', 'mt');
		$this->load->model($this->modelName, 'm');

		$this->_selOptions["branches"] = $this->mt->list_joomla_user_branch();
		array_unshift($this->_selOptions["branches"], array('id'=>'', 'title'=>''));
		$this->_selOptions["users"] = $this->mt->list_joomla_users();
		array_unshift($this->_selOptions["users"], array('id'=>'', 'name'=>''));
		$this->_selOptions["payment_route"] = $this->mt->list_where('order_payment_route', 'is_cancel=0 AND is_deposit=1', 'sort_index', 'm_');

		// $_strNextQONumber = $this->m->getNextQONumber();
		
		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();

		//++ set special attributes		
		$this->_setController("payment_route", "ช่องทางการชำระ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("payment_disp_date", "วันที่ชำระ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>2));
		$this->_setController("disp_type", "ประเภทการชำระ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>4));
		$this->_setController("amount", "ยอดเงิน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>5));
		$this->_setController("qo_number", "เลขที่ใบเสนอราคา", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>6));
		$this->_setController("job_number", "เลขที่ใบสั่งตัด", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>7));
		$this->_setController("customer", "ชื่อลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>8));
		$this->_setController("company", "ชื่อบริษัท", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>9));
		//-- set special attribute

	
		$_custom_columns = array();
		$pass['left_panel'] = $this->add_view('_public/_search_panel', $this->_arrSearchParams(), TRUE);

		$this->load->helper('order_detail_helper');
		$template = array(
			'index' => 0,
			'list_viewable' => FALSE,
			'list_insertable' => $this->_blnCheckRight('insert', 'quotation'),
			'list_editable' => FALSE,
			'list_deleteable' => FALSE,
			'dataview_fields' => $this->_arrDataViewFields,
			'custom_columns' => $_custom_columns
			//, 'jqDataTable' => '1.10.11'
		);		
		// $template['edit_template'] = $this->_getEditTemplate();
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);
		$pass['title'] = "รายงาน Bank Statement";
		
		$this->add_js('public/js/quotation/form.js');
		$this->add_js('public/js/quotation/detail.js');
		//$this->add_js('public/js/quotation/payment.js');
		
		if ($_strStartScript != '') $this->add_js($_strStartScript, 'custom');

		
		$qo_status = $this->mt->list_where('quotation_status', 'is_cancel=0', NULL, 'm_');
		$this->add_js("var _ARR_QO_STATUS = " . json_encode($qo_status) . ";", 'custom');

		$this->_DISABLE_ON_LOAD_SEARCH = True;
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}


	function _arrSearchParams() {
		$this->load->model('Mdl_customer', 'c');
		$_arrRoute = $this->m->list_select_route();
		if (is_array($_arrRoute)) array_unshift($_arrRoute, array('rowid'=>'', 'payment_route'=>''));

		// echo '<pre>'; print_r($_arrRoute); echo '</pre>';exit;
		// echo '<pre>'; print_r($_arrCompanySearch); echo '</pre>';exit;
		//---
		// print_r($_arrCompanySearch);exit;
		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P3D'));
		return array(
				'controls' => array(
					array(
						"type" => "sel"
						, "label" => "ธนาคาร"
						, "name" => "payment_route"
						, "sel_options" => $_arrRoute
						, "sel_val" => "payment_route"
						, "sel_text" => "payment_route"
					),
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
						//,"value" => $_to->format('d/m/Y')
					),
					array(
						"type" => "info",
						"value" => "* จำกัดจำนวนแสดงผลไว้ที่ 1,000 แถว เพื่อประสิทธิภาพในการทำงานของโปรแกรม"
					)
				),
				'search_onload' => TRUE
		);		
	}
	
	// function get_pdf($quotation_rowid) {
	// 	$this->load->model($this->modelName, 'm');
	// 	$pass['data'] = $this->m->get_detail_report($quotation_rowid);
	// 	if ($pass['data'] == FALSE) {
	// 		echo "Error get report data: " . $this->m->error_message;
	// 		return;
	// 	} else {
	// 		$_status_rowid = (isset($pass['data']['status_rowid'])) ? $pass['data']['status_rowid'] : 10;
	// 		$html = '';
	// 		mb_internal_encoding("UTF-8");
	// 		$this->load->helper('exp_pdf_helper');
			
	// 		$now = new DateTime();
	// 		$strNow = $now->format('YmdHis');
	// 		$file_name = 'quotation_' . $strNow . '.pdf';
	// 		$this->load->library('mpdf8');
	// 		$pass['title'] = 'ใบเสนอราคา';
	// 		$html = $this->load->view('quotation/pdf/quotation', $pass, TRUE);

	// 		if(!isset($pass['data']['is_vat']) || ($pass['data']['is_vat'] == 0 )){
	// 			$temp_name = 'quotation-no-logo';
	// 		}else{
	// 			$temp_name = 'quotation';
	// 		}

	// 		if ($_status_rowid >= 40) {
	// 			$this->mpdf8->exportMPDF_Template($html, $temp_name, $file_name);
	// 		} else {
	// 			$this->mpdf8->exportMPDF_Template_withWaterMark($html, $temp_name, $file_name);
	// 		}
	// 	}
	// }
	
	

	
	
	
}