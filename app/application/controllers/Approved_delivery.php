<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Approved_delivery extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_delivery_approve';
	}
	
	public function index($product_type_id = 0) {
		$this->add_css(array(
			'public/css/jquery/ui/1.11.4/cupertino/jquery-ui.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedcolumns-3.2.1/fixedColumns.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedheader-3.1.1/fixedHeader.jqueryui.min.css',
			/*'public/css/jquery/dataTable/extensions/colreorder-1.3.1/colReorder.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/scroller-1.4.1/scroller.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/select-1.1.2/select.jqueryui.min.css',*/
			'public/css/report/report_global.css',
			'public/css/approved_delivery/form.css',
			'public/css/jquery/ui/timepicker/1.6.1/jquery-ui-timepicker-addon.min.css',
			array('a.DTTT_button_commit_page span { background: url(public/images/ok-grey.png) no-repeat bottom right;display: inline-block;height: 24px;line-height: 24px;padding-right: 30px; }', 'custom'),
			array('a.DTTT_button_commit_page:hover span { background: url(public/images/ok-green.png) no-repeat center right; }', 'custom')
		));
		$_allowEdit = "true";
		if (! $this->_blnCheckRight('edit', 'shipping')) {
			$this->add_css('td.edit { border:0px solid transparent !important; }', 'custom');
			$_allowEdit = "false";
		}
		$this->add_js(array(
			array("var _ALLOW_EDIT = " . $_allowEdit . ";", 'custom_init'),
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/dataTable/1.10.11/jquery.dataTables.min.js',
			'public/js/jquery/dataTable/1.10.11/dataTables.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/dataTables.buttons.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/jszip-2.5.0/jszip.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/pdfmake.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/vfs_fonts.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.html5.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.print.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.colVis.min.js',
			'public/js/jquery/dataTable/extensions/fixedcolumns-3.2.1/dataTables.fixedColumns.min.js',
			'public/js/jquery/dataTable/extensions/fixedheader-3.1.1/dataTables.fixedHeader.min.js',
			//'public/js/jquery/dataTable/extensions/colreorder-1.3.1/dataTables.colReorder.min.js',
			//'public/js/jquery/dataTable/extensions/responsive-2.0.2/dataTables.responsive.min.js',
			//'public/js/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.js',
			//'public/js/jquery/dataTable/extensions/scroller-1.4.1/dataTables.scroller.min.js',
			//'public/js/jquery/dataTable/extensions/select-1.1.2/dataTables.select.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/moment_2.8.4.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/datetime-moment.js',
			'public/js/jquery/dataTable/extensions/type-detection/numeric-comma.js',
			'public/js/jquery/editable/1.7.1/jquery.editable.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/jquery/ui/timepicker/1.6.1/jquery-ui-timepicker-addon.min.js',
			'public/js/jquery/ui/timepicker/1.6.1/jquery-ui-sliderAccess.js',
			'public/js/report/report_global.js',
			'public/js/approved_delivery/form.js',
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js'
		));
		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("rowid", "", array("type"=>"hdn"));
		$this->_setController("delivery_rowid", "", array("type"=>"hdn"));
		$this->_setController("is_rejected", "", array("type"=>"hdn"));
		$this->_setController("reject_by", "", array("type"=>"hdn"));
		$this->_setController("reject_date", "", array("type"=>"hdn"));
		$this->_setController("status", "สถานะจัดส่ง", NULL, array("selectable"=>FALSE,"class"=>"center edit deliver_status_id","width"=>'60px',"order"=>1));
		$this->_setController("delivery_code", "เลขที่ใบส่งสินค้า", NULL, array("selectable"=>FALSE,"class"=>"center","width"=>60,"order"=>2));
		$this->_setController("create_user", "เซลส์", NULL, array("selectable"=>FALSE,"class"=>"center","width"=>60,"order"=>3));
		$this->_setController("customer", "ชื่อลูกค้า", NULL, array("selectable"=>FALSE,"class"=>"center","width"=>80,"order"=>4));
		$this->_setController("company", "บริษัท", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>100,"order"=>5));
		//$this->_setController("title", "การส่งมอบ", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>120,"order"=>6));
		$this->_setController("list_job_numbers", "เลขที่ใบสั่งผลิต", NULL, array("selectable"=>TRUE,"width"=>'100px',"order"=>10));
		$this->_setController("disp_total", "ราคารวม", NULL, array("selectable"=>TRUE,"width"=>60,"class"=>"default_number","order"=>11));
		$this->_setController("disp_vat", "VAT", NULL, array("selectable"=>TRUE,"width"=>60,"class"=>"default_number","order"=>12));
		$this->_setController("disp_grand_total", "รวมทั้งสิ้น", NULL, array("selectable"=>TRUE,"class"=>"default_number","width"=>80,"order"=>13));
		$this->_setController("disp_deliver_date", "วันที่กำหนดส่ง", NULL, array("selectable"=>TRUE,"width"=>"60px","class"=>"center","order"=>14));
		$this->_setController("deliver_route", "วิธีการส่ง", NULL, array("selectable"=>TRUE,"width"=>"80px","order"=>15));
		$this->_setController("address", "ที่อยู่จัดส่ง", NULL, array("selectable"=>TRUE,"width"=>"120","order"=>16));
		$this->_setController("province", "จังหวัด", NULL, array("selectable"=>TRUE,"width"=>"60","class"=>"center","order"=>17));
		$this->_setController("deliver_by", "ผู้ส่ง", NULL, array("selectable"=>FALSE,"class"=>"edit center deliver_by","width"=>"60","order"=>18));
		$this->_setController("left_amount", "เงินที่เรียกเก็บ", NULL, array("selectable"=>TRUE,"class"=>"default_number","width"=>60,"order"=>19));
		$this->_setController("collected_cash", "เงินที่เก็บได้จริง ", NULL, array("selectable"=>FALSE,"class"=>"edit default_number collected_cash","width"=>60,"order"=>20));
		$this->_setController("collected_method", "ช่องทางรับเงิน ", NULL, array("selectable"=>FALSE,"class"=>"edit center collected_method","width"=>60,"order"=>21));
		$this->_setController("actual_deliver_datetime", "เวลาส่ง", NULL, array("selectable"=>FALSE,"width"=>"60","class"=>"center edit actual_deliver_datetime","order"=>22));
		$this->_setController("deliver_terminal", "ขนส่ง", NULL, array("selectable"=>FALSE,"width"=>"60","class"=>"center edit deliver_terminal","order"=>23));
		$this->_setController("terminal_recordno", "เล่มที่/เลขที่", NULL, array("selectable"=>FALSE,"width"=>"40","class"=>"center edit terminal_recordno","order"=>24));
		$this->_setController("terminal_contactno", "เบอร์โทรขนส่ง", NULL, array("selectable"=>FALSE,"width"=>"60","class"=>"center edit terminal_contactno","order"=>25));
		$this->_setController("total_packs", "จำนวนถุง", NULL, array("selectable"=>FALSE,"class"=>"edit default_int center total_packs","width"=>40,"order"=>26));
		$this->_setController("total_items", "จำนวนเสื้อ", NULL, array("selectable"=>FALSE,"class"=>"edit default_int center total_items","width"=>40,"order"=>27));
		$this->_setController("delivering_fee", "ค่าขนส่ง", NULL, array("selectable"=>FALSE,"class"=>"edit default_number delivering_fee","width"=>60,"order"=>28));
		$this->_setController("disp_remark", "หมายเหตุ(ใบนำส่ง)", NULL, array("selectable"=>TRUE,"class"=>"","width"=>"150","order"=>29));
		$this->_setController("remark", "บันทึกฝ่ายจัดส่ง", NULL, array("selectable"=>TRUE,"class"=>"edit remark","width"=>"200","order"=>30));

		$pass['left_panel'] = $this->add_view('_public/_search_panel', $this->_arrSearchControllers(), TRUE);
		
		$_custom_columns = array();
		if ($this->_blnCheckRight('view', 'shipping')) {
			$_custom_columns[] = array(
				"column" => <<<CCLMS
{"sTitle":"PDF","sWidth":"20px","sClass":"center","mData":'rowid',"mRender": function(data,type,full) { return '<img class="tblButton" command="pdf" src="./public/images/quotation-1-32.png" title="Export to pdf" />'; }, "bSortable": false}
CCLMS
				, "order" => 7
			);
		}

		$pass['work_panel'] = $this->add_view(
			'_public/_list', 
			array(
				'index'=>0,
				'list_editable'=>FALSE,
				'list_viewable'=>FALSE,
				'list_deleteable'=>FALSE,
				'list_insertable'=>FALSE,
				'dataview_fields'=>$this->_arrDataViewFields,
				'custom_columns'=>$_custom_columns
			), TRUE
		);
		$pass['work_panel'] .= <<<SCR
	<script language="javascript">
	if (_tableToolButtons) {
		_tableToolButtons.push({"text": "&nbsp;","className": "DTTT_button_space"});
		_tableToolButtons.push({
			"text": "บันทึกข้อมูล"
			, "className": "DTTT_button_commit_page DTTT_button_disabled"
			, "action": function ( nButton, oConfig, oFlash ) {
				if (! _fnCheckDataChanged()) return false;
				if ($('td.edit form').length > 0) return false; //Editing
				_doCommitPage();
			}
		});
	}
	</script>
SCR;

		$this->load->model('mdl_master_table', 'mst');		
		$_arrMaster = $this->_prepareSelectOptions(array(
			'status'=>array('table_name'=>'m_deliver_status', 'where'=>"is_cancel = 0", 'order_by'=>'sort_index')
		));
		$_toDTEdit = array();		
		foreach ($_arrMaster as $_key=>$_arrData) {
			if (is_array($_arrData)) {
				$_toDTEdit[$_key] = array("-1"=>"");
				foreach ($_arrData as $_arrRow) {
					if (isset($_arrRow['rowid']) && isset($_arrRow['name'])) $_toDTEdit[$_key][$_arrRow['rowid']] = $_arrRow['name'];
				}
			}
		}
		$_arrCPR = $this->mst->list_where('order_payment_route', "is_cancel = 0 AND is_close = 1", 'sort_index', 'm_');
		$_toDTEdit['collected_method'] = array("-" => "");
		foreach ($_arrCPR as $_row) {
			$_toDTEdit['collected_method'][$_row["name"]] = $_row["name"];
		}
		/*
		$_toDTEdit['collected_method'] = array(
			""=>""
			,"เงินสด" => "เงินสด"
			,"SCB POLO" => "SCB POLO"
			,"KBank POLO" => "KBank POLO"
			,"SCB สุนิสา" => "SCB สุนิสา"
			,"KBank สุนิสา" => "KBank สุนิสา"
			,"Cheque" => "Cheque"
			,"CREDIT" => "CREDIT"
		);
		*/

		$this->add_js('var _MASTER_DT_EDITABLE = ' . json_encode($_toDTEdit) . ";\n", 'custom_init');
		$this->add_js("var _TMPL_TBL_SEARCH = `" . $this->strGetTableTemplate() . "`;\n", "custom_init");
		$pass['title'] = "ฝ่ายขนส่ง (สินค้าพร้อมจัดส่ง)";
		
		$pass["autosearch"] = FALSE;
		$this->_DISABLE_ON_LOAD_SEARCH = True;
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
//			<th>การส่งมอบ</th>
	function strGetTableTemplate() {
		$_tmpl = <<<TBL
<table id="tblSearchResult" class="cls-tbl-list">
	<thead>
		<tr>
			<th>สถานะจัดส่ง</th>
			<th>ใบส่งสินค้า</th>
			<th>เซลส์</th>
			<th>ชื่อลูกค้า</th>
			<th>บริษัท</th>
TBL;
		if ($this->_blnCheckRight('view', 'shipping')) $_tmpl .= '<th>PDF</th>';
		
		$_tmpl .= <<<TBL
			<th>เลขที่ใบสั่งตัด</th>
			<th>ราคารวม</th>
			<th>VAT</th>
			<th>รวมทั้งสิ้น</th>
			<th>วันที่กำหนดส่ง</th>
			<th>วิธีการส่ง</th>
			<th>ที่อยู่จัดส่ง</th>
			<th>จังหวัด</th>
			<th>ผู้ส่ง</th>
			<th>เงินที่เรียกเก็บ</th>
			<th>เงินที่เก็บได้จริง</th>
			<th>ช่องทางรับเงิน</th>
			<th>เวลาส่งจริง</th>
			<th>ขนส่ง</th>
			<th>เล่มที่/เลขที่</th>
			<th>เบอร์โทรขนส่ง</th>
			<th>จำนวนถุง</th>
			<th>จำนวนเสื้อ</th>
			<th>ค่าขนส่ง</th>
			<th>หมายเหตุ(ใบนำส่ง)</th>
			<th>บันทึกฝ่ายจัดส่ง</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

TBL;
		return $_tmpl;//preg_replace('/(\n|\t)+/', '', $_tmpl);
	}

	function _arrSearchControllers() {
		$this->load->model('Mdl_customer', 'c');
		$_arrCompanySearch = $this->c->list_select_company();
		if (is_array($_arrCompanySearch)) array_unshift($_arrCompanySearch, array('rowid'=>'', 'company'=>''));
		$this->load->model('mdl_master_table', 'mt');
		$_arrStatus = $this->mt->list_where('deliver_status', "is_cancel = 0", 'sort_index', 'm_');
		array_unshift($_arrStatus, array('rowid'=>'', 'name'=>''));
		$_arrJoomlaUsers = $this->mt->list_joomla_users();
		array_unshift($_arrJoomlaUsers, array('id'=>'', 'name'=>''));

		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P3D'));

		$_arrControls = array(
				array(
					"type" => "sel",
					"label" => "กลุ่มสินค้า",
					"name" => "category_id",
					"sel_options" => array(
						array('id'=>'', 'name'=>''),
						array('id'=>1, 'name'=>'สั่งตัด'),
						array('id'=>2, 'name'=>'สำเร็จรูป')
					),
					"sel_val" => "id",
					"sel_text" => "name"
				),
				array(
					"type" => "txt",
					"label" => "เลขที่ใบงาน",
					"name" => "job_number"
				),
				array(
					"type" => "sel",
					"label" => "สถานะ",
					"name" => "deliver_status_id",
					"sel_options" => $_arrStatus,
					"sel_val" => "rowid",
					"sel_text" => "name"
				),
				array(
					"type" => "aac"
					, "label" => "ลูกค้า"
					, "name" => "customer_rowid"
					, "url" => "./customer/json_search"
					, "min_length" => 3
					, "sel_val" => "rowid"
					, "sel_text" => "display_name_company"
					, "on_select" => <<<OSL
				var _aac_text = '';
				if (ui.item) {
					_aac_text = ui.item.value || '';
					_aac_text = _aac_text.toString().trim();
				}
				if (_aac_text != '') {
					var _match = /\[(.+)\]/.exec(_aac_text);
					if ((_match) && (_match.length > 0)) $('#sel-company', $(this).parents('form').get(0)).combobox('setValue', _match[1]);
				}

OSL
				),
				array(
					"type" => "sel"
					, "label" => "บริษัท"
					, "name" => "company"
					, "sel_options" => $_arrCompanySearch
					, "sel_val" => "company"
					, "sel_text" => "company"
					, "on_changed" => <<<OCH
function(str, event, ui) {
	var _str = str || '';
	_str = _str.toString().trim();
	if (_str != '') {
		$('#tblSearchPanel #aac-customer_rowid').autocomplete('search', _str);
	}
}

OCH
				),
				array(
					"type" => "sel",
					"label" => "เซลส์",
					"name" => "create_user_id",
					"sel_options" => $_arrJoomlaUsers,
					"sel_val" => "id",
					"sel_text" => "name"
				),
				array(
					"type" => "rdo"
					, "name" => "date_type"
					, "sel_options" => array(
							array("rowid"=>"1", "name"=>"วันที่เปิดใบงาน")
							, array("rowid"=>"2", "name"=>"วันที่ส่งจริง")
						)
					, "sel_val" => "rowid"
					, "sel_text" => "name"
					, "value" => 1
				),
				array(
					"type" => "dpk",
					"label" => "จากวันที่",
					"name" => "date_from",
					"value" => $_frm->format('d/m/Y')
				),
				array(
					"type" => "dpk",
					"label" => "ถึงวันที่",
					"name" => "date_to",
					"value" => $_to->format('d/m/Y')
				)
		);
		
		$_arrLayout = array(
			array('category_id')
			, array('job_number')
			, array('customer_rowid')
			, array('company')
			, array('create_user_id')
			, array('deliver_status_id')
			, array("เงื่อนไขวันที่" => array(
				array('date_type')
				, array('date_from')
				, array('date_to')
			))
		);
		
		return array(
			'controls' => $_arrControls
			, 'layout' => $_arrLayout
		);
	}

	function commit() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_strMessage = '';
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$this->load->model($this->modelName, 'mcommit');
			if ($this->mcommit->commit($_arrData)) {
				$_strMessage = $this->mcommit->success_rows . ' row(s) committed.';
				$_blnSuccess = TRUE;
			} else {
				$_strError = $this->mcommit->error_message;
			}
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