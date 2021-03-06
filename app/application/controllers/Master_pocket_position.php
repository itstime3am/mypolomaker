<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pocket_position extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_pocket_position';
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
				//'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
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

		$this->_setController("name", "ตำแหน่งกระเป๋า", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>1));
		/*
		$_arr_custom_columns = array(
			array(
				"column" => '{"sTitle": "สำหรับเสื้อโปโล", "sClass": "center", "mData": "is_polo", "mRender": function(data,type,full) { if (full.is_polo == 1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_polo"}'
				, "order" => 1
			)
			, array(
				"column" => '{"sTitle": "สำหรับเสื้อยืด", "sClass": "center", "mData": "is_tshirt", "mRender": function(data,type,full) { if (full.is_tshirt==1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_tshirt"}'
				, "order" => 2
			)
			, array(
				"column" => '{"sTitle": "สำหรับเสื้อแจ๊กเก็ต", "sClass": "center", "mData": "is_jacket", "mRender": function(data,type,full) { if (full.is_jacket==1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_tshirt"}'
				, "order" => 2
			)
		);
		*/
		$this->_setController("is_jacket", "", array("type"=>"hdn","value"=>"1"));
		$this->_setController("remark", "ข้อมูลเพิ่มเติม", array("type"=>"txa"), array("selectable"=>TRUE,"default"=>TRUE,"order"=>3));

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel'
			, array(
				'controls' => array(
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('name'),
						"name" => "name"
					)
/*
					, array(
						"type" => "chk",
						"label" => "เสื้อโปโล",
						"name" => "is_polo"
					)
					, array(
						"type" => "chk",
						"label" => "เสื้อยืด",
						"name" => "is_tshirt"
					)
*/
				)
				, "layout" => array(
					array("return &nbsp;")
					, array("name")
//					, array("is_polo", "is_tshirt")
				)
			)
			, TRUE
		);
		
		$template['dataview_fields'] = $this->_arrDataViewFields;
//		$template['custom_columns'] = $_arr_custom_columns;
		$template['list_deleteable'] = FALSE;
		$template['list_cancelable'] = TRUE;
		$template['edit_template'] = $this->load->view(
			'_public/_form', 
			array(
				'controls' => $this->_arrGetEditControls(),
				'layout' => $this->_arrControlLayout
			),
			TRUE
		);
			
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);

		$pass['title'] = "ตำแหน่งกระเป๋า ( สำหรับเสื้อแจ๊กเก็ต )";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}