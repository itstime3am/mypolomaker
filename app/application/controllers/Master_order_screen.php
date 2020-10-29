<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_order_screen extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_order_screen';
	}
	
	public function index()
	{
		$this->add_css(
			array(
				'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
				'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
				'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css'
			)
		);
		$this->add_js(
			array(
				'public/js/jquery/1.11.0/jquery.js',
				'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
				'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				//'public/js/jquery/dataTable/1.10.0/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/TableTools.min.js',
				//'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);
		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();

		$this->_setController("disp_screen_type", "ประเภท", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center apply","order"=>0));
		$this->_setController("name", "ชื่อ", NULL, array("selectable"=>TRUE, "width"=>"300px", "class"=>"center", "default"=>TRUE,"order"=>1));
		
		$_arr_custom_columns = array(
			array(
				"column" => '{"sTitle": "เสื้อโปโล", "sClass": "center", "mData": "is_polo", "mRender": function(data,type,full) { if (full.is_polo == 1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_polo"}'
				, "order" => 2
			)
			, array(
				"column" => '{"sTitle": "เสื้อยืด", "sClass": "center", "mData": "is_tshirt", "mRender": function(data,type,full) { if (full.is_tshirt==1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_tshirt"}'
				, "order" => 3
			)
			, array(
				"column" => '{"sTitle": "แจ็กเก็ต", "sClass": "center", "mData": "is_jacket", "mRender": function(data,type,full) { if (full.is_jacket==1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_jacket"}'
				, "order" => 4
			)
			, array(
				"column" => '{"sTitle": "หมวก", "sClass": "center", "mData": "is_cap", "mRender": function(data,type,full) { if (full.is_cap==1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_cap"}'
				, "order" => 5
			)
		);
		$this->_arrOptions = array(
			array("rowid"=>0, "text"=>"&nbsp;")
			, array("rowid"=>1, "text"=>"งานปัก")
			, array("rowid"=>2, "text"=>"งานสกรีน")
		);
		$this->_setController("screen_type", "ประเภท", array("type"=>"sel", "sel_options"=>$this->_arrOptions, "sel_val"=>"rowid", "sel_text"=>"text"));
		$this->_setController("is_polo", "เสื้อโปโล", array("type"=>"chk"));
		$this->_setController("is_tshirt", "เสื้อยืด", array("type"=>"chk"));
		$this->_setController("is_jacket", "เสื้อแจ๊กเก็ต", array("type"=>"chk"));
		$this->_setController("is_cap", "หมวก", array("type"=>"chk"));
		$this->_setController("remark", "ข้อมูลเพิ่มเติม", array("type"=>"txa"));

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel'
			, array(
				'controls' => array(
					array("type" => "txt", "label" => $this->_getDisplayLabel('name'), "name" => "name")
					, array("type" => "sel", "label" => $this->_getDisplayLabel('screen_type'), "name" => "screen_type", "sel_options"=>$this->_arrOptions, "sel_val"=>"rowid", "sel_text"=>"text")
					, array("type" => "chk", "label" => "เสื้อโปโล", "name" => "is_polo") /*, "value" => TRUE*/
					, array("type" => "chk", "label" => "เสื้อยืด", "name" => "is_tshirt") /*, "value" => TRUE*/
					, array("type" => "chk", "label" => "แจ็กเก็ต", "name" => "is_jacket") /*, "value" => TRUE*/
					, array("type" => "chk", "label" => "หมวก", "name" => "is_cap") /*, "value" => TRUE*/
				)
				, "layout" => array(
					array("return &nbsp;")
					, array("name")
					, array("screen_type")
					, array("is_polo", "is_tshirt")
					, array("is_jacket", "is_cap")
				)
			)
			, TRUE
		);
		$template['dataview_fields'] = $this->_arrDataViewFields;
		$template['custom_columns'] = $_arr_custom_columns;
		$template['list_deleteable'] = False;
		$template['list_cancelable'] = True;
		$template['edit_template'] = $this->load->view(
			'_public/_form'
			, array(
				'controls' => $this->_arrGetEditControls()
				, 'layout' => $this->_arrControlLayout
			)
			, TRUE
		);
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);
		$pass['title'] = "งานปัก / สกรีน";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}