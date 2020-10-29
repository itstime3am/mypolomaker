<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_flap_type extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_flap_type';
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

		$this->_setController("name", "แบบชายเสื้อ", array(), array("selectable"=>TRUE,"width"=>"300px", "class"=>"center","default"=>TRUE,"order"=>0));
		//$this->_setController("disp_polo", "สำหรับเสื้อโปโล", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center apply","order"=>1));
		//$this->_setController("disp_tshirt", "สำหรับเสื้อยืด", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center apply","order"=>2));
		
		$_arr_custom_columns = array(
			array(
				"column" => '{"sTitle": "สำหรับเสื้อโปโล", "sClass": "center", "mData": "is_polo", "mRender": function(data,type,full) { if (full.is_polo == 1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_polo"}'
				, "order" => 1
			)
			, array(
				"column" => '{"sTitle": "สำหรับเสื้อยืด", "sClass": "center", "mData": "is_tshirt", "mRender": function(data,type,full) { if (full.is_tshirt==1) { return \'<img src="public/images/checkbox_yes.png" class="cls-is-true">\'; } else { return ""; } }, "orderable": true, "targets": "is_tshirt"}'
				, "order" => 2
			)
		);
		$this->_setController("is_polo", "เสื้อโปโล", array("type"=>"chk"));
		$this->_setController("is_tshirt", "เสื้อยืด", array("type"=>"chk"));
		$this->_setController("remark", "ข้อมูลเพิ่มเติม", array("type"=>"txa"));

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel', 
			array(
				'controls' => array(
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('name'),
						"name" => "name"
					),
					array(
						"type" => "chk",
						"label" => "เสื้อโปโล",
						"name" => "is_polo"/*,
						"value" => TRUE*/
					),
					array(
						"type" => "chk",
						"label" => "เสื้อยืด",
						"name" => "is_tshirt"/*,
						"value" => TRUE*/
					)
				)
				, "layout" => array(
					array("return &nbsp;")
					, array("name")
					, array("is_polo", "is_tshirt")
				)
			),
			TRUE
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

		$pass['title'] = "ประเภทชายเสื้อ";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}